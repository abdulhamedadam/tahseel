<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin;
use App\Models\Admin\FinancialTransaction;
use App\Models\Admin\Invoice;
use App\Models\Admin\Revenue;
use App\Models\Admin\Subscription;
use App\Models\Clients;
use App\Notifications\InvoicePaidNotification;
use App\Notifications\InvoiceRedoNotification;
use App\Services\InvoiceService;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    use ImageProcessing;
    use ValidationMessage;

    protected $admin_view = 'dashbord.invoices';
    protected $ClientsRepository;
    protected $invoiceService;
    protected $SubscriptionRepository;
    protected $InvoiceRepository;

    public function __construct(BasicRepositoryInterface $basicRepository, InvoiceService $invoiceService)
    {
        $this->middleware('can:list_invoices')->only('index', 'dueMonthlyInvoices', 'newlyPaidInvoices');
        $this->middleware('can:delete_invoice')->only('destroy');
        $this->middleware('can:pay_invoice')->only('pay_invoice');
        $this->middleware('can:view_invoice_details')->only('show_details');
        $this->middleware('can:print_invoice')->only('print_invoice');
        $this->middleware('can:redo_invoice')->only('redo_invoice');

        $this->InvoiceRepository = createRepository($basicRepository, new Invoice());
        $this->SubscriptionRepository = createRepository($basicRepository, new Subscription());
        $this->ClientsRepository = createRepository($basicRepository, new Clients());
        $this->invoiceService = $invoiceService;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $allData = $this->InvoiceRepository->getWithRelations(['client', 'employee', 'subscription'])->toQuery()->whereNull('deleted_at')->orderBy('id', 'desc')->get();
            $invoices = $this->InvoiceRepository->getWithRelations(['client', 'employee', 'subscription', 'revenues.user']);
            $query = $invoices->isNotEmpty() ? $invoices->toQuery()
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc') : collect();

            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }

            if ($request->filled('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            if ($request->filled('subscription_id') && $request->subscription_id != '') {
                $query->whereHas('subscription', function($q) use ($request) {
                    $q->where('id', $request->subscription_id);
                });
            }

            if ($request->filled('collector_id') && $request->collector_id != '') {
                $query->whereHas('revenues.user', function($q) use ($request) {
                    $q->where('id', $request->collector_id);
                });
            }

            if ($request->filled('min_amount') && $request->min_amount != '') {
                $query->where('amount', '>=', $request->min_amount);
            }
            if ($request->filled('max_amount') && $request->max_amount != '') {
                $query->where('amount', '<=', $request->max_amount);
            }

            if ($request->filled('from_date') && $request->from_date != '') {
                $query->whereDate('created_at', '>=', $request->from_date);
            }
            if ($request->filled('to_date') && $request->to_date != '') {
                $query->whereDate('created_at', '<=', $request->to_date);
            }
            return Datatables::of($query)
                ->addColumn('id', function ($row) {
                    return $row->id ?? 'N/A';
                })
                ->addColumn('invoice_number', function ($row) {
                    // return $row->invoice_number ?? 'N/A';
                    $prefix = $row->client && $row->client->client_type == 'satellite' ? 'SA-' : 'IN-';
                    return $prefix . $row->invoice_number;
                })
                ->addColumn('client', function ($row) {
                    if ($row->client) {
                        $url = route('admin.client_paid_invoices', $row->client->id);
                        return '<a href="' . $url . '" class="text-primary fw-bold" style="text-decoration: underline;">' . $row->client->name . '</a>';
                    }
                    return 'N/A';
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount ?? 'N/A';
                })
                ->addColumn('paid_amount', function ($row) {
                    // return $row->amount - $row->remaining_amount;
                    return $row->paid_amount ?? 'N/A';
                })
                ->addColumn('remaining_amount', function ($row) {
                    return $row->remaining_amount ?? 'N/A';
                })
                ->addColumn('due_date', function ($row) {
                    return $row->due_date ?? 'N/A';
                })
                ->addColumn('paid_date', function ($row) {
                    return $row->paid_date
                        ? Carbon::parse($row->paid_date)->format('Y-m-d h:i A')
                        : 'N/A';
                })
                ->addColumn('collected_by', function ($row) {
                    $latestRevenue = $row->revenues->sortByDesc('created_at')->first();

                    if ($latestRevenue && $latestRevenue->user) {
                        return $latestRevenue->user->name;
                    }
                    // return trans('invoices.not_found');
                    return 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $status = $row->status ?? 'N/A';
                    $class = match ($status) {
                        'paid' => 'badge bg-success text-white',
                        'partial' => 'badge bg-warning text-dark',
                        'unpaid' => 'badge bg-danger text-white',
                    };
                    return '<span class="' . $class . 'px-4 py-3 rounded-pill fw-bold fs-5">' . trans('invoices.' . $status) . '</span>';
                })
                ->addColumn('subscription', function ($row) {
                    return $row->subscription ? $row->subscription->name : '<span class="badge bg-success text-white px-4 py-3 rounded-pill fw-bold fs-5">' . trans('invoices.service') . '</span>';
                })
                ->addColumn('month_year', function ($row) {
                    return $row->enshaa_date ? Carbon::parse($row->enshaa_date)->format('F Y') : 'N/A';
                })
                ->addColumn('notes', function ($row) {
                    return $row->notes ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="btn-group btn-group-sm">';

                    if (($row->status == 'unpaid' || $row->status == 'partial') && auth()->user()->can('pay_invoice')) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="showPayModal(\'' . route('admin.pay_invoice', $row->id) . '\', ' . $row->remaining_amount . ', ' . $row->amount . ', `'.str_replace('`', '\`', $row->notes ?? '').'`)"
                                class="btn btn-sm btn-success" title="' . trans('invoices.mark_as_paid') . '" style="font-size: 16px;">
                                <i class="bi bi-check-circle"></i>
                            </a>';
                    }
                    if (auth()->user()->can('print_invoice')) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="print_invoice(\'' . route('admin.print_invoice', $row->id) . '\')"
                                class="btn btn-sm btn-warning" title="' . trans('invoices.print') . '" style="font-size: 16px;">
                                <i class="bi bi-printer"></i>
                            </a>';
                    }

                    if (auth()->user()->can('view_invoice_details')) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="invoice_details(\'' . route('admin.invoice_details', $row->id) . '\')"
                                class="btn btn-sm btn-info" title="' . trans('invoices.view_details') . '" style="font-size: 16px;">
                                <i class="bi bi-eye"></i>
                            </a>';
                    }

                    // if (($row->status == 'paid' || $row->status == 'partial') && $row->subscription_id != null) {
                    if (($row->status == 'paid' || $row->status == 'partial') && auth()->user()->can('redo_invoice')) {
                        $buttons .= '
                            <a onclick="return confirm(\'' . trans('invoices.confirm_redo') . '\')"
                                href="' . route('admin.redo_invoice', $row->id) . '"
                                class="btn btn-sm btn-secondary" title="' . trans('invoices.redo_invoice') . '" style="font-size: 16px;">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>';
                    }
                    if (auth()->user()->can('delete_invoice')) {
                        $buttons .= '
                            <a onclick="return confirm(\'' . trans('employees.confirm_delete') . '\')"
                                href="' . route('admin.delete_invoice', $row->id) . '"
                                class="btn btn-sm btn-danger" title="' . trans('clients.delete') . '" style="font-size: 16px;">
                                <i class="bi bi-trash3"></i>
                            </a>';
                    }
                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['subscription', 'action', 'client', 'status', 'month_year', 'invoice_number'])
                ->make(true);
        }

        $data['clients'] = $this->ClientsRepository->getAll();
        $data['subscriptions'] = Subscription::all();
        $data['collectors'] = Admin::whereHas('revenues')->get();

        return view($this->admin_view . '.index', $data);
    }

    /***********************************************/
    public function destroy(string $id)
    {
        try {
            $invoice = $this->InvoiceRepository->getById($id);

            if (!$invoice) {
                return redirect()->back()->withErrors(['error' => trans('invoices.invoice_not_found')]);
            }

            $invoice->revenues()->delete();
            $this->InvoiceRepository->delete($id);

            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.invoices.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function pay_invoice1($id, Request $request)
    {
        $request->validate([
            'invoice_amount' => 'required|numeric|min:1',
            'paid_amount' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);
        // dd($request->all());
        try {
            $result = $this->invoiceService->payInvoice($id, $request);
            $invoice = Invoice::findOrFail($id);
            $admins = Admin::where('status', '1')
                ->whereNull('deleted_at')
                // ->where('id', '!=', auth()->id())
                ->get();

            foreach ($admins as $admin) {
                $admin->notify(new InvoicePaidNotification(
                    $invoice,
                    $request->paid_amount,
                    auth()->user(),
                    'تم دفع فاتورة رقم ' . $invoice->id . ' بقيمة ' . $request->paid_amount . ' جنيه'
                ));
            }

            return $result;
            // toastr()->addSuccess(trans('forms.success'));
            // return redirect()->back()->with('success', trans('forms.success'));
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function pay_invoice($id, Request $request)
    {
        $request->validate([
            'invoice_amount' => 'required|numeric|min:1',
            'paid_amount' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $result = $this->invoiceService->payInvoice($id, $request);
            $invoice = Invoice::findOrFail($id);

            $notificationMessage = 'تم دفع فاتورة رقم ' . $invoice->invoice_number . ' بقيمة ' . $request->paid_amount . ' جنيه';

            $admins = Admin::where('status', '1')
                ->whereNull('deleted_at')
                // ->whereNotNull('onesignal_id')
                ->get();

            $playerIds = $admins->pluck('onesignal_id')->filter()->toArray();

            foreach ($admins as $admin) {
                $admin->notify(new InvoicePaidNotification(
                    $invoice,
                    $request->paid_amount,
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
                        'amount' => $request->paid_amount,
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

            DB::commit();

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show_details($id)
    {
        $data['all_data'] = Invoice::with('client', 'subscription', 'employee')->findOrFail($id);
        // dd($data);
        return view($this->admin_view . '.details', $data);
    }

    public function print_invoice($id)
    {
        $data['all_data'] = Invoice::findOrFail($id);
        return view($this->admin_view . '.print', $data);
    }

    public function redo_invoice1($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            $lastPayment = Revenue::where('invoice_id', $id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$lastPayment) {
                return redirect()->back()->withErrors(['error' => 'لا توجد دفعات سابقة لهذه الفاتورة!']);
            }

            $invoice->remaining_amount += $lastPayment->amount;
            $invoice->paid_amount -= $lastPayment->amount;

            $client = Clients::find($invoice->client_id);
            if (!$client) {
                return redirect()->back()->withErrors(['error' => 'العميل غير موجود!']);
            }

            if ($invoice->remaining_amount == $invoice->amount) {
                $invoice->status = 'unpaid';
                // $invoice->remaining_amount = 0.0;
                $invoice->paid_date = null;
                // if ($invoice->invoice_type == 'subscription') {
                //     $invoice->amount = $client->price;
                // }
            } elseif ($invoice->remaining_amount > 0) {
                $invoice->status = 'partial';
            }

            $invoice->save();

            $financialTransaction = FinancialTransaction::where('account_id', auth()->user()->account_id)
                ->where('amount', $lastPayment->amount)
                ->where('created_by', auth()->id())
                ->orderBy('created_at', 'desc')
                ->first();

            if ($financialTransaction) {
                $financialTransaction->delete();
            }

            $lastPayment->delete();
            $admins = Admin::where('status', '1')
                ->whereNull('deleted_at')
                // ->where('id', '!=', auth()->id())
                ->get();

            foreach ($admins as $admin) {
                $admin->notify(new InvoiceRedoNotification(
                    $invoice,
                    $lastPayment->amount,
                    auth()->user(),
                    'تم التراجع عن دفع فاتورة رقم ' . $invoice->id . ' بقيمة ' . $lastPayment->amount . ' جنيه'
                ));
            }

            return redirect()->back()->with(['success' => trans('messages.redo_successfully')]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function redo_invoice($id)
    {
        try {
            DB::beginTransaction();

            $invoice = Invoice::findOrFail($id);
            $lastPayment = Revenue::where('invoice_id', $id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$lastPayment) {
                return redirect()->back()->withErrors(['error' => 'لا توجد دفعات سابقة لهذه الفاتورة!']);
            }

            $invoice->remaining_amount += $lastPayment->amount;
            $invoice->paid_amount -= $lastPayment->amount;

            $client = Clients::find($invoice->client_id);
            if (!$client) {
                return redirect()->back()->withErrors(['error' => 'العميل غير موجود!']);
            }

            if ($invoice->remaining_amount == $invoice->amount) {
                $invoice->status = 'unpaid';
                $invoice->paid_date = null;
            } elseif ($invoice->remaining_amount > 0) {
                $invoice->status = 'partial';
            }

            $invoice->save();

            $financialTransaction = FinancialTransaction::where('account_id', auth()->user()->account_id)
                ->where('amount', $lastPayment->amount)
                // ->where('created_by', auth()->id())
                ->orderBy('created_at', 'desc')
                ->first();

            if ($financialTransaction) {
                $financialTransaction->delete();
            }

            $lastPayment->delete();

            $notificationMessage = 'تم التراجع عن دفع فاتورة رقم ' . $invoice->id . ' بقيمة ' . $lastPayment->amount . ' جنيه';

            $admins = Admin::where('status', '1')
                ->whereNull('deleted_at')
                // ->whereNotNull('onesignal_id')
                ->get();

            $playerIds = $admins->pluck('onesignal_id')->filter()->toArray();

            foreach ($admins as $admin) {
                $admin->notify(new InvoiceRedoNotification(
                    $invoice,
                    $lastPayment->amount,
                    auth()->user(),
                    $notificationMessage
                ));
            }

            if (!empty($playerIds)) {
                sendOneSignalNotification1(
                    $playerIds,
                    $notificationMessage,
                    [
                        'invoice_id' => $invoice->id,
                        'type' => 'invoice_payment_redo',
                        'amount' => $lastPayment->amount,
                        'initiator' => auth()->user()->name,
                        'invoice_details' => [
                            'number' => $invoice->invoice_number,
                            'client' => $client->name ?? 'Unknown',
                            'status' => $invoice->status
                        ]
                    ],
                    null
                );
            }

            DB::commit();
            return redirect()->back()->with(['success' => trans('messages.redo_successfully')]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function dueMonthlyInvoices(Request $request)
    {
        if ($request->ajax()) {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $allData = Invoice::with(['client', 'employee', 'subscription'])
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->where('remaining_amount', '>', 0)
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->get();

            return Datatables::of($allData)
                ->addColumn('id', function ($row) {
                    return $row->id ?? 'N/A';
                })
                ->addColumn('invoice_number', function ($row) {
                    $prefix = $row->client && $row->client->client_type == 'satellite' ? 'SA-' : 'IN-';
                    return $prefix . $row->invoice_number;
                })
                ->addColumn('client', function ($row) {
                    if ($row->client) {
                        $url = route('admin.client_paid_invoices', $row->client->id);
                        return '<a href="' . $url . '" class="text-primary fw-bold" style="text-decoration: underline;">' . $row->client->name . '</a>';
                    }
                    return 'N/A';
                })
                ->addColumn('subscription', function ($row) {
                    return $row->subscription ? $row->subscription->name : '<span class="badge bg-success text-white px-4 py-3 rounded-pill fw-bold fs-5">' . trans('invoices.service') . '</span>';
                })
                ->addColumn('due_date', function ($row) {
                    return $row->due_date ?? 'N/A';
                })
                ->addColumn('paid_date', function ($row) {
                    return $row->paid_date ? $row->paid_date : 'N/A';
                })
                ->addColumn('collected_by', function ($row) {
                    $latestRevenue = $row->revenues->sortByDesc('created_at')->first();

                    if ($latestRevenue && $latestRevenue->user) {
                        return $latestRevenue->user->name;
                    }
                    return 'N/A';
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount ?? 'N/A';
                })
                ->addColumn('paid_amount', function ($row) {
                    // return $row->amount - $row->remaining_amount;
                    return $row->paid_amount ?? 'N/A';
                })
                ->addColumn('remaining_amount', function ($row) {
                    return $row->remaining_amount ?? 'N/A';
                })
                ->addColumn('notes', function ($row) {
                    return $row->notes ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $status = $row->status ?? 'N/A';
                    $class = match ($status) {
                        'paid' => 'badge bg-success text-white',
                        'partial' => 'badge bg-warning text-dark',
                        'unpaid' => 'badge bg-danger text-white',
                    };
                    return '<span class="' . $class . 'px-4 py-3 rounded-pill fw-bold fs-5">' . trans('invoices.' . $status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="btn-group btn-group-sm">';

                    if (($row->status == 'unpaid' || $row->status == 'partial') && auth()->user()->can('pay_invoice')) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="showPayModal(\'' . route('admin.pay_invoice', $row->id) . '\', ' . $row->remaining_amount . ', ' . $row->amount . ', `'.str_replace('`', '\`', $row->notes ?? '').'`)"
                                class="btn btn-sm btn-success" title="' . trans('invoices.mark_as_paid') . '" style="font-size: 16px;">
                                <i class="bi bi-check-circle"></i>
                            </a>';
                    }
                    if (auth()->user()->can('print_invoice')) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="print_invoice(\'' . route('admin.print_invoice', $row->id) . '\')"
                                class="btn btn-sm btn-warning" title="' . trans('invoices.print') . '" style="font-size: 16px;">
                                <i class="bi bi-printer"></i>
                            </a>';
                    }

                    if (auth()->user()->can('view_invoice_details')) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="invoice_details(\'' . route('admin.invoice_details', $row->id) . '\')"
                                class="btn btn-sm btn-info" title="' . trans('invoices.view_details') . '" style="font-size: 16px;">
                                <i class="bi bi-eye"></i>
                            </a>';
                    }
                    // if (($row->status == 'paid' || $row->status == 'partial') && $row->subscription_id != null) {
                    if (($row->status == 'paid' || $row->status == 'partial') && auth()->user()->can('redo_invoice')) {
                        $buttons .= '
                            <a onclick="return confirm(\'' . trans('invoices.confirm_redo') . '\')"
                                href="' . route('admin.redo_invoice', $row->id) . '"
                                class="btn btn-sm btn-secondary" title="' . trans('invoices.redo_invoice') . '" style="font-size: 16px;">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>';
                    }

                    if (auth()->user()->can('delete_invoice')) {
                        $buttons .= '
                            <a onclick="return confirm(\'' . trans('employees.confirm_delete') . '\')"
                                href="' . route('admin.delete_invoice', $row->id) . '"
                                class="btn btn-sm btn-danger" title="' . trans('clients.delete') . '" style="font-size: 16px;">
                                <i class="bi bi-trash3"></i>
                            </a>';
                    }
                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['subscription', 'action', 'client', 'status', 'invoice_number'])
                ->make(true);
        }
        return view($this->admin_view . '.monthly_due_invoices');
    }

    public function newlyPaidInvoices(Request $request)
    {
        if ($request->ajax()) {
            $allData = Invoice::with(['client', 'employee', 'subscription'])
                ->whereIn('status', ['paid', 'partial'])
                ->whereDate('paid_date', '>=', Carbon::now()->subDays(10))
                ->whereNull('deleted_at')
                ->orderBy('paid_date', 'desc')
                ->get();

            return Datatables::of($allData)
                ->addColumn('id', function ($row) {
                    return $row->id ?? 'N/A';
                })
                ->addColumn('invoice_number', function ($row) {
                    $prefix = $row->client && $row->client->client_type == 'satellite' ? 'SA-' : 'IN-';
                    return $prefix . $row->invoice_number;
                })
                ->addColumn('client', function ($row) {
                    if ($row->client) {
                        $url = route('admin.client_paid_invoices', $row->client->id);
                        return '<a href="' . $url . '" class="text-primary fw-bold" style="text-decoration: underline;">' . $row->client->name . '</a>';
                    }
                    return 'N/A';
                })
                ->addColumn('amount', function ($row) {
                    // return $row->amount - $row->remaining_amount;
                    return $row->amount ?? 'N/A';
                })
                ->addColumn('paid_amount', function ($row) {
                    // return $row->amount - $row->remaining_amount;
                    return $row->paid_amount ?? 'N/A';
                })
                ->addColumn('remaining_amount', function ($row) {
                    return $row->remaining_amount ?? 'N/A';
                })
                ->addColumn('due_date', function ($row) {
                    return $row->due_date ?? 'N/A';
                })
                ->addColumn('paid_date', function ($row) {
                    return $row->paid_date ? $row->paid_date : 'N/A';
                })
                ->addColumn('collected_by', function ($row) {
                    $latestRevenue = $row->revenues->sortByDesc('created_at')->first();

                    if ($latestRevenue && $latestRevenue->user) {
                        return $latestRevenue->user->name;
                    }
                    return 'N/A';
                })
                ->addColumn('notes', function ($row) {
                    return $row->notes ?? 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $status = $row->status;
                    $class = match ($status) {
                        'paid' => 'badge bg-success text-white',
                        'partial' => 'badge bg-warning text-dark',
                        default => 'badge bg-secondary text-white',
                    };
                    return '<span class="' . $class . ' px-4 py-3 rounded-pill fw-bold fs-5">' . trans('invoices.' . $status) . '</span>';
                })
                ->addColumn('subscription', function ($row) {
                    return $row->subscription ? $row->subscription->name : '<span class="badge bg-success text-white px-4 py-3 rounded-pill fw-bold fs-5">' . trans('invoices.service') . '</span>';
                })
                // ->addColumn('month_year', function ($row) {
                //     return $row->enshaa_date ? Carbon::parse($row->enshaa_date)->format('F Y') : 'N/A';
                // })
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="btn-group btn-group-sm">';

                    if (auth()->user()->can('print_invoice')) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="print_invoice(\'' . route('admin.print_invoice', $row->id) . '\')"
                                class="btn btn-sm btn-warning" title="' . trans('invoices.print') . '" style="font-size: 16px;">
                                <i class="bi bi-printer"></i>
                            </a>';
                    }

                    if (auth()->user()->can('view_invoice_details')) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="invoice_details(\'' . route('admin.invoice_details', $row->id) . '\')"
                                class="btn btn-sm btn-info" title="' . trans('invoices.view_details') . '" style="font-size: 16px;">
                                <i class="bi bi-eye"></i>
                            </a>';
                    }


                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['subscription', 'action', 'client', 'status', 'invoice_number'])
                ->make(true);
        }

        return view($this->admin_view . '.new_paid_invoices');
    }

    public function generate()
    {
        return $this->invoiceService->generateMonthlyInvoices();
    }
}
