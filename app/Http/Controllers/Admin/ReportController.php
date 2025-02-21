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
                ->rawColumns(['subscription', 'status', 'client', 'month_year', 'invoice_number'])
                ->make(true);
        }
    }
}
