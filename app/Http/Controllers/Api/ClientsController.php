<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Http\Resources\InvoiceResource;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Invoice;
use App\Models\Clients;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    use ResponseApi;

    protected $ClientsRepository;
    protected $InvoiceRepository;

    public function __construct(BasicRepositoryInterface $basicRepository)
    {
        $this->ClientsRepository = createRepository($basicRepository, new Clients());
        $this->InvoiceRepository = createRepository($basicRepository, new Invoice());
    }

    public function index(Request $request)
    {
        try {
            $query = Clients::query();
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('user', 'like', "%{$search}%")
                    ->orWhere('address1', 'like', "%{$search}%")
                    ->orWhere('box_switch', 'like', "%{$search}%")
                    ->orWhere('client_type', 'like', "%{$search}%")
                    ->orWhereHas('subscription', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            }

            $clients = $query->whereNull('deleted_at')->orderBy('created_at', 'desc')->get();
            $data = [
                'clients' => ClientResource::collection($clients)
            ];
            return $this->responseApi($data, 'تم استرجاع الزبائن بنجاح');
        } catch (\Exception $e) {
            return $this->responseApiError('حدث خطأ ما.');
        }
    }

    // public function clientInvoices($id)
    // {
    //     try {
    //         $client = $this->ClientsRepository->getById($id);

    //         if (!$client) {
    //             return $this->responseApiError('العميل غير موجود');
    //         }
    //         $oneYearAgo = Carbon::now()->subYear();

    //         $unpaidAndPartialInvoices = Invoice::with(['client', 'employee', 'subscription'])
    //             ->where('client_id', $id)
    //             ->whereIn('status', ['unpaid', 'partial'])
    //             ->where('created_at', '>=', $oneYearAgo)
    //             ->orderBy('created_at', 'desc')
    //             ->get();

    //         $paidInvoices = Invoice::with(['client', 'employee', 'subscription'])
    //             ->where('client_id', $id)
    //             ->whereIn('status', ['paid', 'partial'])
    //             ->where('created_at', '>=', $oneYearAgo)
    //             ->orderBy('created_at', 'desc')
    //             ->get();

    //         $data = [
    //             'client' => new ClientResource($client),
    //             'paid_invoices' => [
    //                 'count' => $paidInvoices->count(),
    //                 'total_paid_amount' => $paidInvoices->sum('amount'),
    //                 'invoices' => InvoiceResource::collection($paidInvoices)
    //             ],
    //             'unpaid_and_partial_invoices' => [
    //                 'count' => $unpaidAndPartialInvoices->count(),
    //                 'total_unpaidAndPartial_amount' => $unpaidAndPartialInvoices->sum('remaining_amount'),
    //                 'invoices' => InvoiceResource::collection($unpaidAndPartialInvoices)
    //             ],
    //         ];

    //         return $this->responseApi($data, 'تم استرجاع فواتير العميل بنجاح');
    //     } catch (\Exception $e) {
    //         return $this->responseApiError('حدث خطأ ما.');
    //     }
    // }
    public function clientInvoices($id)
    {
        try {
            $client = $this->ClientsRepository->getById($id);

            if (!$client) {
                return $this->responseApiError('العميل غير موجود');
            }
            $oneYearAgo = Carbon::now()->subYear();

            $unpaidAndPartialInvoices = Invoice::with(['client', 'employee', 'subscription'])
                ->where('client_id', $id)
                ->whereIn('status', ['unpaid', 'partial'])
                ->where('created_at', '>=', $oneYearAgo)
                ->orderBy('created_at', 'desc')
                ->get();

            $user = auth('api')->user();

            $paidInvoices = Invoice::with(['client', 'employee', 'subscription', 'revenues' => function($q) use ($user) {
                    $q->where('collected_by', $user->id)
                    ->orderBy('received_at', 'desc');
                }])
                ->where('client_id', $id)
                ->whereIn('status', ['paid', 'partial'])
                ->where('created_at', '>=', $oneYearAgo)
                ->get();

                // dd($paidInvoices);
                $processedPaidInvoices = [];
                foreach ($paidInvoices as $invoice) {
                    if ($invoice->revenues->count() > 0) {
                        foreach ($invoice->revenues as $revenue) {

                            // $paidBeforeThisRevenue = $revenue->amount + $revenue->remaining_amount;

                            $processedPaidInvoices[] = [
                                'id' => $invoice->id,
                                'invoice_number' => ($invoice->client->client_type == 'satellite' ? 'SA-' : 'IN-') . $invoice->invoice_number,
                                'client_id' => $invoice->client->id,
                                'client_name' => $invoice->client->name,
                                'client_phone' => $invoice->client->phone,
                                'client_address' => $invoice->client->address1,
                                'subscription_id' => $invoice->subscription_id,
                                'subscription' => $invoice->subscription ? $invoice->subscription->name : trans('invoices.service'),
                                'amount' => $invoice->amount,
                                'paid_amount' => $revenue->amount,
                                // 'remaining_before_payment' => $paidBeforeThisRevenue,
                                'remaining_amount' => $revenue->remaining_amount,
                                'due_date' => $invoice->due_date ?? 'N/A',
                                'paid_date' => $revenue->received_at,
                                'collected_by' => $revenue->user->name,
                                // 'status' => $revenue->status,
                                'status' => 'paid',
                                'invoice_type' => $invoice->invoice_type,
                                'notes' => $revenue->notes,
                                'currency' => get_app_config_data('currency')
                            ];
                        }
                    }
                }

                $data = [
                    'client' => new ClientResource($client),
                    'paid_invoices' => [
                        'count' => count($processedPaidInvoices),
                        'total_paid_amount' => $paidInvoices->sum('paid_amount'),
                        'invoices' => $processedPaidInvoices
                    ],
                    'unpaid_and_partial_invoices' => [
                        'count' => $unpaidAndPartialInvoices->count(),
                        'total_unpaidAndPartial_amount' => $unpaidAndPartialInvoices->sum('remaining_amount'),
                        'invoices' => InvoiceResource::collection($unpaidAndPartialInvoices)
                    ],
                ];

            return $this->responseApi($data, 'تم استرجاع فواتير العميل بنجاح');
        } catch (\Exception $e) {
            return $this->responseApiError('حدث خطأ ما.');
        }
    }
}
