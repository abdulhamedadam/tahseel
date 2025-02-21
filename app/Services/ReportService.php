<?php


namespace App\Services;


use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Invoice;
use App\Models\Admin\Revenue;
use App\Models\Admin\Subscription;
use App\Models\Clients;
use App\Traits\ImageProcessing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportService
{

    use ImageProcessing;
    protected $InvoiceRepository;
    public function __construct(BasicRepositoryInterface $basicRepository)
    {
        $this->InvoiceRepository   = createRepository($basicRepository, new Invoice());
    }

    public function getFilteredInvoices(Request $request)
    {
        // $query = $this->InvoiceRepository->getWithRelations(['client', 'employee', 'subscription']);
        $query = Invoice::with(['client', 'employee', 'subscription']);
        // Log::info($request->filled('type'));
        if ($request->filled('client_id')) {
            $query = $query->where('client_id', $request->client_id);
        }

        if ($request->filled('type')) {
            $query = $query->where('invoice_type', $request->type);
        }

        if ($request->filled('status')) {
            $query = $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            // Log::info($request->filled('month'));
            $monthYear = Carbon::parse($request->month);
            $query = $query->whereMonth('enshaa_date', $monthYear->month)
                ->whereYear('enshaa_date', $monthYear->year);
        }

        if ($request->filled('from_date')) {
            $query = $query->where('enshaa_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query = $query->where('enshaa_date', '<=', $request->to_date);
        }
        $allData = $query->get();
        // Log::info($allData);
        return $allData;
    }
}
