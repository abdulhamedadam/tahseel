<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Invoice;
use App\Models\Admin\Revenue;
use App\Models\Admin\Subscription;
use App\Models\Clients;
use App\Services\InvoiceService;
use App\Services\ReportService;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class ReportController extends Controller
{
    use ImageProcessing;
    use ValidationMessage;

    protected $admin_view = 'dashbord.reports';
    protected $ClientsRepository;
    protected $invoiceService;
    protected $SubscriptionRepository;
    protected $InvoiceRepository;
    protected $reportService;

    public function __construct(BasicRepositoryInterface $basicRepository, ReportService $reportService)
    {
        $this->middleware('can:view_reports')->only('reports');
        // $this->middleware('can:generate_reports')->only('index');

        $this->InvoiceRepository = createRepository($basicRepository, new Invoice());
        $this->SubscriptionRepository = createRepository($basicRepository, new Subscription());
        $this->ClientsRepository = createRepository($basicRepository, new Clients());
        $this->reportService = $reportService;
    }

    public function reports()
    {
        $clients = $this->ClientsRepository->getAll();

        return view($this->admin_view . '.index', compact('clients'));
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $allData = $this->reportService->getFilteredInvoices($request);
            $data = $allData['invoices'];
            $totals = $allData['totals'];

            return Datatables::of($data)
                ->addColumn('id', function ($row) {
                    return $row->id ?? 'N/A';
                })
                ->addColumn('invoice_number', function ($row) {
                    // return $row->invoice_number ?? 'N/A';
                    // $prefix = $row->client && $row->client->client_type == 'satellite' ? 'SA-' : 'IN-';
                    // return $prefix . $row->invoice_number;
                    $prefix = $row->client && $row->client->client_type == 'satellite' ? 'SA-' : 'IN-';
                    if ($row->invoice_number) {
                        return '<a href="javascript:void(0)" onclick="invoice_details(\'' . route('admin.invoice_details', $row->id) . '\')"
                                class="text-primary fw-bold" style="text-decoration: underline;" title="' . trans('invoices.view_details') . '">
                                ' . $prefix . ($row->invoice_number ?? 'N/A') . '
                            </a>';
                    }

                    return 'N/A';
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

                    if (($row->status == 'unpaid' || $row->status == 'partial') && auth()->user()->can('pay_invoice')) {
                        $buttons .= '
                            <a href="javascript:void(0)" onclick="showPayModal(\'' . route('admin.pay_invoice', $row->id) . '\', ' . $row->remaining_amount . ', ' . $row->amount . ')"
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
                ->with('totals', $totals)
                ->rawColumns(['subscription', 'status', 'client', 'month_year', 'invoice_number', 'action'])
                ->make(true);
        }
    }
}
