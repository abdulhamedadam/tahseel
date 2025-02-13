<?php

namespace App\Providers;

use App\Console\Commands\AddNewInvoices;
use App\Models\Admin;
use App\Models\Admin\Invoice;
use App\Models\Clients;
use App\Notifications\InvoiceReminderNotification;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->commands([
        //     AddNewInvoices::class,
        // ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFour();
        Lang::handleMissingKeysUsing(function ($key) {
            if (strpos($key, 'flasher') !== false) {
                return $key;
            }

            // Add custom logic to handle missing keys
            // For example, you can log the missing key
            Log::info("Missing translation key: $key");

            // You can also add the missing key to the language file dynamically
            $keyParts = explode('.', $key);
            if (count($keyParts) >= 2) {
                $group = $keyParts[0];
                $item = $keyParts[1];

                $langPath = base_path("lang/" . app()->getLocale() . "/$group.php");

                if (File::exists($langPath)) {
                    $translations = File::getRequire($langPath);
                    $translations[$item] = $item;
                    File::put($langPath, '<?php return ' . var_export($translations, true) . ';');
                } else {
                    File::put($langPath, '<?php return ' . var_export([$item => $item], true) . ';');
                }
            }

            // Return the key as the translation (optional)
            return $key;
        });

        if (Carbon::now()->day == 1) {
            // dd(Carbon::now()->day);
            $clients = Clients::whereNull('deleted_at')->get();
            foreach ($clients as $client) {
                $currentMonth = Carbon::now()->format('Y-m');

                $existingInvoice = Invoice::where('client_id', $client->id)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->exists();

                if (!$existingInvoice) {
                    $lastInvoice = Invoice::where('client_id', $client->id)->latest()->first();

                    if ($lastInvoice) {
                        $dueDate = Carbon::parse($lastInvoice->due_date)->addMonth();
                    } else {
                        $dueDate = Carbon::parse($client->start_date)->addMonth();
                    }

                    Invoice::create([
                        'client_id' => $client->id,
                        'invoice_number' => getLastFieldValue(Invoice::class, 'invoice_number'),
                        'amount' => $client->price,
                        'remaining_amount' => $client->price,
                        'subscription_id' => $client->subscription_id,
                        'enshaa_date' => Carbon::now()->startOfMonth(),
                        'due_date' => $dueDate,
                        'status' => 'unpaid',
                    ]);
                }
            }
        }


        $this->sendOverdueInvoiceNotifications();
        // $this->generateInvoicesOncePerDay();
    }

    private function sendOverdueInvoiceNotifications()
    {
        $today = Carbon::today();
        // dd($today);
        if (Carbon::parse(Cache::get('last_invoice_notification_date')) == $today) {
            return;
        }

        $admins = Admin::where('status', '1')->whereNull('deleted_at')->get();

        $overdueInvoices = Invoice::where('status', 'unpaid')
            ->where(function ($query) use ($today) {
                $query->whereNull('last_notified_at')
                    ->orWhereRaw("COALESCE(DATE_FORMAT(last_notified_at, '%Y-%m-%d'), '2000-01-01') < due_date");
            })
            ->get();
        // dd($admins, $overdueInvoices);
        foreach ($overdueInvoices as $invoice) {
            if ($today->toDateString() >= Carbon::parse($invoice->due_date)->toDateString()) {
                // dd('ddd');
                foreach ($admins as $admin) {
                    $admin->notify(new InvoiceReminderNotification($invoice));
                }

                $invoice->updateQuietly(['last_notified_at' => $today]);
            }
        }

        Cache::put('last_invoice_notification_date', $today, now()->endOfDay());
    }


    private function generateInvoicesOncePerDay()
    {
        $today = Carbon::today()->toDateString();

        if (!Cache::has('last_invoice_run') || Cache::get('last_invoice_run') != $today) {
            $clients = Clients::whereNull('deleted_at')->get();

            foreach ($clients as $client) {
                $startDate = Carbon::parse($client->start_date);
                $enshaaDate = Carbon::now()->startOfMonth();
                $dueDate = $startDate->copy()->addMonthsNoOverflow(1);

                if (Carbon::now()->format('d') == $startDate->format('d')) {
                    $existingInvoice = Invoice::where('client_id', $client->id)
                        ->whereYear('enshaa_date', $enshaaDate->year)
                        ->whereMonth('enshaa_date', $enshaaDate->month)
                        ->exists();

                    if (!$existingInvoice) {
                        Invoice::create([
                            'client_id' => $client->id,
                            'invoice_number' => getLastFieldValue(Invoice::class, 'invoice_number'),
                            'amount' => $client->price,
                            'remaining_amount' => $client->price,
                            'subscription_id' => $client->subscription_id,
                            'enshaa_date' => $enshaaDate,
                            'due_date' => $dueDate,
                            'status' => 'unpaid',
                        ]);
                    }
                }
            }

            Cache::put('last_invoice_run', $today, now()->endOfDay());
        }
    }

    // private function sendOverdueInvoiceNotifications()
    // {
    //     $today = Carbon::today();

    //     if ($today->day > 5) {
    //         return;
    //     }

    //     $currentMonth = $today->format('Y-m');

    //     $admins = Admin::where('status', '1')->whereNull('deleted_at')->get();

    //     $overdueInvoices = Invoice::where('status', 'unpaid')
    //         ->where('due_date', '<', $today)
    //         ->where(function ($query) use ($currentMonth) {
    //             $query->whereNull('last_notified_at')
    //                 ->orWhereRaw("DATE_FORMAT(last_notified_at, '%Y-%m') != ?", [$currentMonth]);
    //         })
    //         ->get();

    //     foreach ($overdueInvoices as $invoice) {
    //         foreach ($admins as $admin) {
    //             $admin->notify(new InvoiceReminderNotification($invoice));
    //         }

    //         $invoice->update(['last_notified_at' => $today]);
    //     }
    // }


}
