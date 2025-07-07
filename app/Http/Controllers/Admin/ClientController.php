<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\clients\ImportClientsRequest;
use App\Http\Requests\Admin\clients\SaveRequests;
use App\Http\Requests\Admin\clients\UpdateRequests;
use App\Imports\ClientsImport;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin;
use App\Models\Admin\FinancialTransaction;
use App\Models\Admin\Invoice;
use App\Models\Admin\Revenue;
use App\Models\Admin\Subscription;
use App\Models\Clients;
use App\Notifications\InvoicePaidNotification;
use App\Services\ClientService;
use App\Services\CompanyService;
use App\Services\ProjectsService;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    use ImageProcessing;
    use ValidationMessage;

    protected $admin_view = 'dashbord.clients';
    protected $NotificationsControllerNotificationsController;
    protected $clientService;
    protected $ClientsRepository;
    protected $SubscriptionRepository;
    protected $InvoiceRepository;

    public function __construct(BasicRepositoryInterface $basicRepository, ClientService $clientService, CompanyService $companyService, ProjectsService $projectsService)
    {
        $this->middleware('can:list_clients')->only('index');
        $this->middleware('can:create_client')->only('create', 'store');
        $this->middleware('can:update_client')->only('edit', 'update');
        $this->middleware('can:delete_client')->only('destroy');
        $this->middleware('can:view_client_unpaid_invoices')->only('client_unpaid_invoices');
        // $this->middleware('can:view_client_paid_invoices')->only('client_paid_invoices');
        $this->middleware('can:view_client_invoices')->only('client_invoices');
        $this->middleware('can:add_client_invoice')->only('client_add_invoice');


        $this->SubscriptionRepository = createRepository($basicRepository, new Subscription());
        $this->ClientsRepository = createRepository($basicRepository, new Clients());
        $this->clientService = $clientService;
        $this->InvoiceRepository   = createRepository($basicRepository, new Invoice());
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $allData = Clients::with('subscription')
            //     ->withSum('invoices', 'remaining_amount')
            //     ->orderBy('created_at', 'desc')
            //     ->get();
            // $counter = 0;

            $query = Clients::with('subscription')
                ->withSum('invoices', 'remaining_amount')
                ->orderBy('created_at', 'desc');

            if ($request->has('name_search') && !empty($request->name_search)) {
                $query->where('name', 'like', '%' . $request->name_search . '%');
            }

            if ($request->has('other_fields_search') && !empty($request->other_fields_search)) {
                $searchTerm = $request->other_fields_search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('phone', 'like', '%' . $searchTerm . '%')
                    ->orWhere('address1', 'like', '%' . $searchTerm . '%')
                    ->orWhere('notes', 'like', '%' . $searchTerm . '%')
                    ->orWhere('client_type', 'like', '%' . $searchTerm . '%')
                    ->orWhere('price', 'like', '%' . $searchTerm . '%')
                    ->orWhere('box_switch', 'like', '%' . $searchTerm . '%')
                    ->orWhere('start_date', 'like', '%' . $searchTerm . '%')
                    ->orWhere('user', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('subscription', function($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', '%' . $searchTerm . '%');
                    });
                });
            }

            $allData = $query->get();
            $counter = 0;

            return Datatables::of($allData)
                ->addColumn('id', function () use (&$counter) {
                    $counter++;
                    return $counter;
                })
                ->addColumn('name', function ($row) {
                    return $row->name ?? 'N/A';
                })
                ->addColumn('phone', function ($row) {
                    return $row->phone ?? 'N/A';
                })
                // ->addColumn('email', function ($row) {
                //     return $row->email ?? 'N/A';
                // })
                ->addColumn('client_type', function ($row) {
                    return $row->client_type ?? 'N/A';
                })
                ->addColumn('user', function ($row) {
                    return $row->user ?? 'N/A';
                })
                ->addColumn('box_switch', function ($row) {
                    return $row->box_switch ?? 'N/A';
                })
                ->addColumn('address1', function ($row) {
                    return $row->address1 ?? 'N/A';
                })
                ->addColumn('subscription', function ($row) {
                    // return $row->subscription ? $row->subscription->name : '<span class="badge bg-success text-white px-4 py-3 rounded-pill fw-bold fs-5">' . trans('invoices.service') . '</span>';
                    return $row->subscription ? $row->subscription->name : 'N\A';
                })
                ->addColumn('price', function ($row) {
                    return $row->price ?? 'N/A';
                })
                ->addColumn('notes', function ($row) {
                    return $row->notes ? $row->notes : 'N/A';
                })
                ->addColumn('start_date', function ($row) {
                    return $row->start_date ? $row->start_date : 'N/A';
                })
                ->addColumn('remaining_amount', function ($row) {
                    return $row->invoices_sum_remaining_amount ?? 0;
                })
                // ->addColumn('is_active', function ($row) {
                //     return $row->is_active
                //         ? '<span class="badge bg-success">'.trans('clients.active').'</span>'
                //         : '<span class="badge bg-danger">'.trans('clients.inactive').'</span>';
                // })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '1') {
                        $title = trans('clients.active');
                        $class = 'success';
                        $icon = '<i class="bi bi-check-circle-fill"></i>';
                    } else {
                        $title = trans('clients.inactive');
                        $class = 'danger';
                        $icon = '<i class="bi bi-x-circle-fill"></i>';
                    }

                    if (auth()->user()->can('update_client')) {
                        return '<a href="' . route('admin.clients.change_status', [$row->id, $row->is_active]) . '"
                                class="btn btn-' . $class . ' btn-sm"
                                onclick="return confirm(\'' . trans('clients.change_status_msg') . '\');">'
                                . $icon . ' ' . $title . '</a>';
                    } else {
                        return '<span class="badge bg-' . $class . '">' . $icon . ' ' . $title . '</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $actionButtons = '<div class="btn-group">';
                    $actionButtons .= '<button type="button" style="font-size: 16px" class="btn btn-sm btn-secondary">' . trans('employees.actions') . '</button>';
                    $actionButtons .= '<button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-icon" data-bs-toggle="dropdown" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button>';
                    $actionButtons .= '<ul class="dropdown-menu">';
                    $actionButtons .= '<li><a style="font-size: 14px" class="hover-effect dropdown-item" href="javascript:void(0)" onclick="showClientDetails(' . $row->id . ')"><i class="bi bi-eye-fill"></i> ' . trans('clients.view_details') . '</a></li>';
                    if (auth()->user()->can('update_client')) {
                        $actionButtons .= '<li><a style="font-size: 14px" class="hover-effect dropdown-item" href="' . route('admin.clients.edit', $row->id) . '" target="_blank"><i class="bi bi-pencil-square"></i> ' . trans('clients.edit_clients') . '</a></li>';
                    }

                    // if (auth()->user()->can('delete_client')) {
                    //     $actionButtons .= '<li><a style="font-size: 14px" class="hover-effect dropdown-item text-danger" onclick="return confirm(\'' . trans('clients.confirm_delete') . '\')" href="' . route('admin.delete_client', $row->id) . '"><i class="bi bi-trash-fill"></i> ' . trans('clients.client_delete') . '</a></li>';
                    // }

                    // if (auth()->user()->can('view_client_paid_invoices')) {
                    $actionButtons .= '<li><a style="font-size: 14px" class="hover-effect dropdown-item" href="' . route('admin.client_paid_invoices', $row->id) . '"><i class="bi bi-currency-dollar"></i> ' . trans('clients.client_invoices') . '</a></li>';
                    // }

                    // if (auth()->user()->can('view_client_unpaid_invoices')) {
                    //     $actionButtons .= '<li><a style="font-size: 14px" class="hover-effect dropdown-item" href="' . route('admin.client_unpaid_invoices', $row->id) . '"><i class="bi bi-receipt-cutoff"></i> ' . trans('clients.client_unpaid_invoices') . '</a></li>';
                    // }

                    if (auth()->user()->can('add_client_invoice')) {
                        $actionButtons .= '<li><a style="font-size: 14px" class="hover-effect dropdown-item" href="' . route('admin.client_invoices', $row->id) . '"><i class="bi bi-file-earmark-plus"></i> ' . trans('clients.client_add_invoice') . '</a></li>';
                    }

                    $actionButtons .= '</ul>';
                    $actionButtons .= '</div>';

                    return $actionButtons;
                })
                ->rawColumns(['subscription', 'action', 'is_active'])
                ->make(true);
        }
        return view($this->admin_view . '.index');
    }

    /***********************************************/
    public function create()
    {
        $data['client_code'] = $this->ClientsRepository->getLastFieldValue('client_code');
        $data['subscriptions'] = $this->SubscriptionRepository->getAll();
        return view($this->admin_view . '.form', $data);
    }

    /***********************************************/
    public function store(SaveRequests $request)
    {
        try {
            $this->clientService->store($request);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.clients.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /***********************************************/
    public function show(string $id)
    {
        //
    }

    /***********************************************/
    public function edit(string $id)
    {
        $data['subscriptions'] = $this->SubscriptionRepository->getAll();
        $data['all_data'] =  $this->ClientsRepository->getById($id);
        return view($this->admin_view . '.edit', $data);
    }
    /***********************************************/
    public function update(UpdateRequests $request, string $id)
    {
        try {
            $this->clientService->update($request, $id);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.clients.index');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /***********************************************/
    public function destroy(string $id)
    {
        try {
            $this->ClientsRepository->delete($id);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.clients.index');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function get_price($id)
    {
        $subscription = $this->SubscriptionRepository->getById($id);

        if ($subscription) {
            return response()->json(['price' => $subscription->price]);
        }

        return response()->json(['price' => '0']);
    }

    /***********************************************/

    public function client_unpaid_invoices($id)
    {
        $data['all_data'] = $this->ClientsRepository->getById($id);
        $data['unpaid_data'] = Invoice::with(['client', 'employee', 'subscription'])
            ->where('client_id', $id)
            ->where('status', 'unpaid')
            ->get();
        $data['paid_data'] = Invoice::with(['client', 'employee', 'subscription'])
            ->where('client_id', $id)
            ->whereIn('status', ['paid', 'partial'])
            ->get();
        // dd($data);
        return view($this->admin_view . '.client_unpaid_invoices', $data);
    }
    /***********************************************/
    public function client_paid_invoices($id)
    {
        $data['all_data'] = $this->ClientsRepository->getById($id);
        $data['unpaid_data'] = Invoice::with(['client', 'employee', 'subscription'])
            ->where('client_id', $id)
            ->where('status', 'unpaid')
            ->get();
        $data['paid_data'] = Invoice::with(['client', 'employee', 'subscription'])
            ->where('client_id', $id)
            ->whereIn('status', ['paid', 'partial'])
            ->get();
        $data['total_unpaid'] = $data['unpaid_data']->sum('amount');
        $data['total_paid'] = $data['paid_data']->sum('paid_amount');
        // dd($data);
        return view($this->admin_view . '.invoices.invoices', $data);
    }
    /***********************************************/
    public function client_invoices($id)
    {
        $data['all_data'] = $this->ClientsRepository->getById($id);
        $data['unpaid_data'] = Invoice::where('client_id', $id)
            ->where('status', 'unpaid')
            ->get();
        $data['paid_data'] = Invoice::where('client_id', $id)
            ->whereIn('status', ['paid', 'partial'])
            ->get();
        // $data['invoiceNumber'] = $this->InvoiceRepository->getLastFieldValue('invoice_number');
        $lastInvoice = Invoice::withTrashed()->orderBy('id', 'desc')->first();
        $data['invoiceNumber'] = $lastInvoice->invoice_number + 1;
        $data['subscriptions'] = $this->SubscriptionRepository->getAll();

        $data['total_unpaid'] = $data['unpaid_data']->sum('amount');
        $data['total_paid'] = $data['paid_data']->sum('paid_amount');
        // dd($data);
        return view($this->admin_view . '.add_invoice.add_invoice', $data);
    }
    /***********************************************/
    public function client_add_invoice(Request $request, $id)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:tbl_invoices,invoice_number',
            'invoice_type' => 'required|in:service,subscription',
            'subscription_id' => 'nullable|exists:tbl_subscriptions,id',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,unpaid',
            // 'remaining_amount' => 'nullable|numeric|min:0|max:' . $request->amount,
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            // if ($request->remaining_amount == 0) {
            //     $status = 'paid';
            // } elseif ($request->remaining_amount > 0 && $request->remaining_amount < $request->amount) {
            //     $status = 'partial';
            // } else {
            //     $status = 'unpaid';
            // }

            // $remainingAmount = $request->remaining_amount ?? 0;
            // $paidAmount = $request->amount - $remainingAmount;

            $remainingAmount = $request->status === 'paid' ? 0.00 : $request->amount;
            $paidAmount = $request->status === 'paid' ? $request->amount : 0.00;
            $paidDate = $request->status === 'paid' ? now() : null;

            $invoiceData = [
                'invoice_number' => $request->invoice_number,
                'client_id' => $id,
                'subscription_id' => $request->invoice_type === 'subscription' ? $request->subscription_id : null,
                'amount' => $request->amount,
                'remaining_amount' => $remainingAmount,
                'paid_amount' => $paidAmount,
                'enshaa_date' => now()->format('Y-m-d'),
                'invoice_type' => $request->invoice_type,
                'notes' => $request->notes,
                'paid_date' => $paidDate,
                'due_date' => Carbon::parse($request->due_date)->format('Y-m-d'),
                'created_by' => auth()->user()->id,
                'status' => $request->status,
                'auto_generated' => $request->invoice_type === 'subscription'
            ];

            // dd($invoiceData);

            $invoice = $this->InvoiceRepository->create($invoiceData);

            // if ($request->remaining_amount < $request->amount) {
            // $admin = auth()->user();
            // $collectedBy = $admin && $admin->is_employee ? $admin->emp_id : auth()->user()->id;
            if ($request->status === 'paid') {
                Revenue::create([
                    'invoice_id' => $invoice->id,
                    'client_id' => $id,
                    'amount' => $request->amount,
                    'collected_by' => auth()->id(),
                    'status' => 'paid',
                    'remaining_amount' => 0,
                    'received_at' => now(),
                ]);

                $accountId = auth()->user()->account_id ?? null;
                if ($accountId) {
                    FinancialTransaction::create([
                        'account_id'    => $accountId,
                        'amount'        => $request->amount,
                        'date'          => now()->toDateString(),
                        'time'          => now()->toTimeString(),
                        'month'         => now()->month,
                        'year'          => now()->year,
                        'notes'         => 'سداد مستحقات الفاتورة رقم #' . $invoice->invoice_number,
                        'type'          => 'qapd',
                        'created_by'    => auth()->id(),
                    ]);
                }

                $paymentType = '';
                if ($request->invoice_type === 'subscription') {
                    $paymentType = ' (دفعة مقدمة للاشتراك)';
                } else {
                    $paymentType = ' (دفعة مقابل خدمة)';
                }

                $notificationMessage = sprintf(
                    'تم دفع مبلغ %s %s%s للعميل %s، %s. (تمت العملية بواسطة: %s)',
                    number_format($request->amount, 2),
                    get_app_config_data('currency'),
                    $paymentType,
                    $invoice->client->name ?? 'غير محدد',
                    $request->invoice_type === 'subscription' ?
                        'لاشتراك ' . ($invoice->subscription->name ?? '') :
                        'لخدمة ' . ($invoice->notes ?? ''),
                    auth()->user()->name
                );

                $admins = Admin::where('status', '1')
                    ->whereNull('deleted_at')
                    ->whereHas('roles', function($query) {
                        $query->whereIn('id', [1, 7]);
                    })
                    ->get();

                foreach ($admins as $admin) {
                    $admin->notify(new InvoicePaidNotification(
                        $invoice,
                        $request->amount,
                        auth()->user(),
                        $notificationMessage
                    ));
                }

                if (!empty($admins)) {
                    sendOneSignalNotification1(
                        $admins,
                        $notificationMessage,
                        [
                            'invoice_id' => $invoice->id,
                            'type' => 'invoice_paid',
                            'amount' => $request->amount,
                            'initiator' => auth()->user()->name,
                            'invoice_details' => [
                                'number' => $invoice->invoice_number,
                                'date' => $invoice->paid_date,
                                'client' => $invoice->client->name ?? 'Unknown'
                            ]
                        ],
                        null
                    );
                }
            }

            DB::commit();

            return redirect()->route('admin.client_paid_invoices', $id)->with('success', trans('clients.invoice_created_successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', trans('clients.failed_to_create_invoice.'));
        }
    }

    public function change_status($id, $status)
    {
        try {
            $client = $this->ClientsRepository->getById($id);
            if ($client) {
                $data['is_active'] = $status == '1' ? '0' : '1';

                $this->ClientsRepository->update($id, $data);
                toastr()->addSuccess(trans('users.status_changed_successfully'));
                return redirect()->route('admin.clients.index');
            }
            return redirect()->route('admin.clients.index');
        } catch (\Exception $e) {
            test($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**============================================================== */

    public function showImportForm()
    {
        $lastClientCode = $this->ClientsRepository->getLastFieldValue('client_code');

        $subscriptions = $this->SubscriptionRepository->getAll();

        $defaultSubscriptionDate = now()->format('Y-m-d');
        return view('dashbord.clients.import', [
            'last_client_code' => $lastClientCode,
            'subscriptions' => $subscriptions,
            'default_subscription_date' => $defaultSubscriptionDate,
        ]);
    }
    public function import1(ImportClientsRequest $request)
    {
        set_time_limit(300);
        try {
            $subscriptionDate = $request->input('subscription_date');
            $file = $request->file('file');

            $import = new ClientsImport($subscriptionDate);
            Excel::import($import, $file);

            $successCount = $import->getSuccessCount();
            $failures = $import->getFailures();

            if (count($failures) > 0) {
                return redirect()
                    ->back()
                    ->with('failures', $failures)
                    ->with('success_count', $successCount)
                    ->with('success', trans('clients.import_partial_success', ['count' => $successCount]));
            }

            toastr()->addSuccess(trans('clients.import_success', ['count' => $successCount]));
            return redirect()->route('admin.clients.index');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => trans('clients.import_error') . $e->getMessage()]);
        }
    }

    public function import2(ImportClientsRequest $request)
    {
        set_time_limit(600);
        ini_set('memory_limit', '512M');

        try {
            $subscriptionDate = $request->input('subscription_date');
            $file = $request->file('file');

            $this->validateImportFile($file);

            $import = new ClientsImport($subscriptionDate);

            Excel::import($import, $file);

            $successCount = $import->getSuccessCount();
            $failures = $import->getFailures();

            Log::info('Client import completed', [
                'success_count' => $successCount,
                'failure_count' => count($failures),
                'user_id' => auth()->id()
            ]);

            if (count($failures) > 0) {
                return redirect()
                    ->back()
                    ->with('failures', $failures)
                    ->with('success_count', $successCount)
                    ->with('warning', trans('clients.import_partial_success', ['count' => $successCount]));
            }

            toastr()->addSuccess(trans('clients.import_success', ['count' => $successCount]));
            return redirect()->route('admin.clients.index');

        } catch (\Exception $e) {
            Log::error('Client import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return redirect()
                ->back()
                ->withErrors(['error' => trans('clients.import_error') . ': ' . $e->getMessage()]);
        }
    }
    public function import(ImportClientsRequest $request)
    {
        set_time_limit(600);
        ini_set('memory_limit', '512M');

        try {
            $subscriptionDate = $request->input('subscription_date');
            $file = $request->file('file');

            // Log::info('Starting import process', [
            //     'file_name' => $file->getClientOriginalName(),
            //     'file_size' => $file->getSize(),
            //     'file_extension' => $file->getClientOriginalExtension(),
            //     'subscription_date' => $subscriptionDate
            // ]);

            // $reader = Excel::toCollection(null, $file);
            // Log::info('File contents debug', [
            //     'sheets_count' => $reader->count(),
            //     'first_sheet_rows' => $reader->first()->count(),
            //     'first_5_rows' => $reader->first()->take(5)->toArray()
            // ]);

            $import = new ClientsImport($subscriptionDate);
            Excel::import($import, $file);

            $successCount = $import->getSuccessCount();
            $failures = $import->getFailures();

            // Log::info('Import completed', [
            //     'success_count' => $successCount,
            //     'failure_count' => count($failures),
            //     'failures' => $failures
            // ]);

            if (count($failures) > 0) {
                // dd($failures);
                return redirect()
                    ->back()
                    ->with('failures', $failures)
                    ->with('success_count', $successCount)
                    ->with('warning', trans('clients.import_partial_success', [
                        'success_count' => $successCount,
                        'failure_count' => count($failures)
                    ]));
            }

            return redirect()
                ->route('admin.clients.index')
                ->with('success', trans('clients.import_success', [
                    'count' => $successCount
                ]));

        } catch (\Exception $e) {
            // Log::error('Import failed', [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString()
            // ]);

            // return redirect()
            //     ->back()
            //     ->withErrors(['error' => trans('clients.import_error_message', [
            //         'error' => $e->getMessage()
            //     ])]);
            return redirect()
                ->back()
                ->withErrors(['error' => trans('clients.import_error')]);
        }
    }

    protected function validateImportFile($file)
    {
        $allowedExtensions = ['xlsx', 'xls', 'csv'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            throw new \InvalidArgumentException('Invalid file format. Only Excel and CSV files are allowed.');
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new \InvalidArgumentException('File size too large. Maximum 10MB allowed.');
        }
    }

    public function getClientDetails($id)
    {
        $client = Clients::with(['subscription', 'invoices'])
            ->withSum('invoices', 'remaining_amount')
            ->findOrFail($id);

        return view($this->admin_view . '.details_modal', compact('client'))->render();
    }

}
