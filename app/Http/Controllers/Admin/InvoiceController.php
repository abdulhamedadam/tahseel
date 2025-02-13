<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Invoice;
use App\Models\Admin\Revenue;
use App\Models\Admin\Subscription;
use App\Models\Clients;
use App\Services\InvoiceService;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

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
        $this->InvoiceRepository = createRepository($basicRepository, new Invoice());
        $this->SubscriptionRepository = createRepository($basicRepository, new Subscription());
        $this->ClientsRepository = createRepository($basicRepository, new Clients());
        $this->invoiceService = $invoiceService;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $allData = $this->InvoiceRepository->getWithRelations(['client', 'employee', 'subscription']);
            return Datatables::of($allData)
                ->addColumn('id', function ($row) {
                    return $row->id ?? 'N/A';
                })
                ->addColumn('invoice_number', function ($row) {
                    // return $row->invoice_number ?? 'N/A';
                    $prefix = $row->client && $row->client->client_type == 'satellite' ? 'SA-' : 'IN-';
                    return $prefix . $row->invoice_number;
                })
                ->addColumn('client', function ($row) {
                    return $row->client ? $row->client->name : 'N/A';
                })
                ->addColumn('remaining_amount', function ($row) {
                    return $row->remaining_amount ?? 'N/A';
                })
                ->addColumn('paid_amount', function ($row) {
                    return $row->amount - $row->remaining_amount;
                })
                ->addColumn('due_date', function ($row) {
                    return $row->due_date ?? 'N/A';
                })
                ->addColumn('paid_date', function ($row) {
                    return $row->paid_date
                        ? Carbon::parse($row->paid_date)->format('Y-m-d h:i A')
                        : 'N/A';
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
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="btn-group btn-group-sm">';

                    if ($row->remaining_amount > 0) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="showPayModal(\''. route('admin.pay_invoice', $row->id) .'\', '. $row->remaining_amount .')"
                                class="btn btn-sm btn-success" title="'. trans('invoices.mark_as_paid') .'" style="font-size: 16px;">
                                <i class="bi bi-check-circle"></i>
                            </a>';
                    }

                    $buttons .= '
                        <a href="javascript:void(0)" onclick="print_invoice(\''. route('admin.print_invoice', $row->id) .'\')"
                            class="btn btn-sm btn-warning" title="'. trans('invoices.print') .'" style="font-size: 16px;">
                            <i class="bi bi-printer"></i>
                        </a>';

                    $buttons .= '
                        <a href="javascript:void(0)" onclick="invoice_details(\''. route('admin.invoice_details', $row->id) .'\')"
                            class="btn btn-sm btn-info" title="'. trans('invoices.view_details') .'" style="font-size: 16px;">
                            <i class="bi bi-eye"></i>
                        </a>';

                    if (($row->status == 'paid' || $row->status == 'partial') && $row->subscription_id != null) {
                        $buttons .= '
                            <a onclick="return confirm(\'' . trans('invoices.confirm_redo') . '\')"
                                href="' . route('admin.redo_invoice', $row->id) . '"
                                class="btn btn-sm btn-secondary" title="' . trans('invoices.redo_invoice') . '" style="font-size: 16px;">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>';
                    }

                    $buttons .= '
                        <a onclick="return confirm(\'' . trans('employees.confirm_delete') . '\')"
                            href="' . route('admin.delete_invoice', $row->id) . '"
                            class="btn btn-sm btn-danger" title="' . trans('clients.delete') . '" style="font-size: 16px;">
                            <i class="bi bi-trash3"></i>
                        </a>';

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['subscription', 'action', 'status', 'month_year', 'invoice_number'])
                ->make(true);
        }
        return view($this->admin_view . '.index');
    }

    /***********************************************/
    // public function create()
    // {
    //     $data['invoice_number'] = $this->InvoiceRepository->getLastFieldValue('invoice_number');
    //     $data['subscriptions'] = $this->SubscriptionRepository->getAll();
    //     $data['clients'] = $this->ClientsRepository->getAll();

    //     return view($this->admin_view . '.form', $data);
    // }

    /***********************************************/
    // public function store(SaveRequests $request)
    // {
    //     try {
    //         $this->invoiceService->store($request);
    //         toastr()->addSuccess(trans('forms.success'));
    //         return redirect()->route('admin.invoices.index');
    //     } catch (\Exception $e) {
    //         dd($e->getMessage());
    //         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }

    /***********************************************/
    // public function show(string $id)
    // {
    //     //
    // }

    /***********************************************/
    // public function edit(string $id)
    // {
    //     $data['subscriptions'] = $this->SubscriptionRepository->getAll();
    //     $data['clients'] = $this->ClientsRepository->getAll();

    //     $data['all_data'] =  $this->InvoiceRepository->getById($id);

    //     return view($this->admin_view . '.edit', $data);
    // }
    /***********************************************/
    // public function update(UpdateRequests $request, string $id)
    // {
    //     try {
    //         $this->invoiceService->update($request, $id);
    //         toastr()->addSuccess(trans('forms.success'));
    //         return redirect()->route('admin.invoices.index');
    //     } catch (\Exception $e) {
    //         dd($e->getMessage());
    //         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }
    /***********************************************/
    public function destroy(string $id)
    {
        try {
            $this->InvoiceRepository->delete($id);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.invoices.index');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function pay_invoice($id, Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = $request->input('amount');

        $invoice = Invoice::findOrFail($id);

        if ($amount > $invoice->remaining_amount) {
            return redirect()->back()->with('error', trans('invoices.The payment amount cannot be greater than the remaining amount.'));
        }

        try {
            $invoice->markAsPaid($amount);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.invoices.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
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

    public function redo_invoice($id)
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

            if ($invoice->remaining_amount == $invoice->amount) {
                $invoice->status = 'unpaid';
            } elseif ($invoice->remaining_amount > 0) {
                $invoice->status = 'partial';
            }

            $invoice->save();

            $lastPayment->delete();

            return redirect()->back()->with(['success' => 'تم إلغاء آخر دفعة بنجاح.']);

        } catch (\Exception $e) {
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
                        ->get();

            return Datatables::of($allData)
                ->addColumn('id', function($row) {
                    return $row->id ?? 'N/A';
                })
                ->addColumn('invoice_number', function ($row) {
                    $prefix = $row->client && $row->client->client_type == 'satellite' ? 'SA-' : 'IN-';
                    return $prefix . $row->invoice_number;
                })
                ->addColumn('client', function($row) {
                    return $row->client ? $row->client->name : 'N/A';
                })
                ->addColumn('subscription', function ($row) {
                    return $row->subscription ? $row->subscription->name : '<span class="badge bg-success text-white px-4 py-3 rounded-pill fw-bold fs-5">' . trans('invoices.service') . '</span>';
                })
                ->addColumn('due_date', function($row) {
                    return $row->due_date ?? 'N/A';
                })
                ->addColumn('paid_date', function ($row) {
                    return $row->paid_date ? $row->paid_date : 'N/A';
                })
                ->addColumn('paid_amount', function ($row) {
                    return $row->amount - $row->remaining_amount;
                })
                ->addColumn('remaining_amount', function ($row) {
                    return $row->remaining_amount ?? 'N/A';
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

                    if ($row->remaining_amount > 0) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="showPayModal(\''. route('admin.pay_invoice', $row->id) .'\', '. $row->remaining_amount .')"
                                class="btn btn-sm btn-success" title="'. trans('invoices.mark_as_paid') .'" style="font-size: 16px;">
                                <i class="bi bi-check-circle"></i>
                            </a>';
                    }

                    $buttons .= '
                        <a href="javascript:void(0)" onclick="print_invoice(\''. route('admin.print_invoice', $row->id) .'\')"
                            class="btn btn-sm btn-warning" title="'. trans('invoices.print') .'" style="font-size: 16px;">
                            <i class="bi bi-printer"></i>
                        </a>';

                    $buttons .= '
                        <a href="javascript:void(0)" onclick="invoice_details(\''. route('admin.invoice_details', $row->id) .'\')"
                            class="btn btn-sm btn-info" title="'. trans('invoices.view_details') .'" style="font-size: 16px;">
                            <i class="bi bi-eye"></i>
                        </a>';
                    if (($row->status == 'paid' || $row->status == 'partial') && $row->subscription_id != null) {
                        $buttons .= '
                            <a onclick="return confirm(\'' . trans('invoices.confirm_redo') . '\')"
                                href="' . route('admin.redo_invoice', $row->id) . '"
                                class="btn btn-sm btn-secondary" title="' . trans('invoices.redo_invoice') . '" style="font-size: 16px;">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>';
                    }

                    $buttons .= '
                        <a onclick="return confirm(\'' . trans('employees.confirm_delete') . '\')"
                            href="' . route('admin.delete_invoice', $row->id) . '"
                            class="btn btn-sm btn-danger" title="' . trans('clients.delete') . '" style="font-size: 16px;">
                            <i class="bi bi-trash3"></i>
                        </a>';

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['subscription', 'action', 'status', 'invoice_number'])
                ->make(true);
        }
        return view($this->admin_view . '.monthly_due_invoices');
    }

    public function newlyPaidInvoices(Request $request)
    {
        if ($request->ajax()) {
            $allData = Invoice::with(['client', 'employee', 'subscription'])
                        ->whereIn('status', ['paid', 'partial'])
                        ->whereDate('created_at', '>=', Carbon::now()->subDays(7));

            return Datatables::of($allData)
                ->addColumn('id', function ($row) {
                    return $row->id ?? 'N/A';
                })
                ->addColumn('invoice_number', function ($row) {
                    $prefix = $row->client && $row->client->client_type == 'satellite' ? 'SA-' : 'IN-';
                    return $prefix . $row->invoice_number;
                })
                ->addColumn('client', function ($row) {
                    return $row->client ? $row->client->name : 'N/A';
                })
                ->addColumn('paid_amount', function ($row) {
                    return $row->amount - $row->remaining_amount;
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
                    $buttons = '<div class="btn-group btn-group-sm">
                        <a href="javascript:void(0)" onclick="print_invoice(\''. route('admin.print_invoice', $row->id) .'\')"
                            class="btn btn-sm btn-warning" title="'. trans('invoices.print') .'" style="font-size: 16px;">
                            <i class="bi bi-printer"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="invoice_details(\''. route('admin.invoice_details', $row->id) .'\')"
                            class="btn btn-sm btn-info" title="'. trans('invoices.view_details') .'" style="font-size: 16px;">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>';
                    return $buttons;
                })
                ->rawColumns(['subscription', 'action', 'status', 'invoice_number'])
                ->make(true);
        }

        return view($this->admin_view . '.new_paid_invoices');
    }

}
