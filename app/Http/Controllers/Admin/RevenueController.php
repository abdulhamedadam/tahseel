<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\clients\SaveRequests;
use App\Http\Requests\Admin\clients\updateRequests;
use App\Http\Requests\Admin\company\SaveRequest;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\AreaSetting;
use App\Models\Admin\Branch;
use App\Models\Admin\Employee;
use App\Models\Admin\EmployeeFiles;
use App\Models\Admin\Invoice;
use App\Models\Admin\Revenue;
use App\Models\Admin\Subscription;
use App\Models\Clients;
use App\Models\ClientsCompanies;
use App\Models\ClientsProjects;
use App\Services\ClientService;
use App\Services\CompanyService;
use App\Services\InvoiceService;
use App\Services\ProjectsService;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Illuminate\Http\Request;
use DataTables;

class RevenueController extends Controller
{
    use ImageProcessing;
    use ValidationMessage;

    protected $admin_view = 'dashbord.revenues';
    protected $RevenueRepository;
    protected $revenueService;

    public function __construct(BasicRepositoryInterface $basicRepository, InvoiceService $revenueService)
    {
        $this->RevenueRepository = createRepository($basicRepository, new Revenue());
        $this->revenueService = $revenueService;
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $allData = $this->RevenueRepository->getWithRelations(['client', 'invoice']);
            return Datatables::of($allData)
                ->addColumn('id', function ($row) {
                    return $row->id ?? 'N/A';
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount ?? 'N/A';
                })
                ->addColumn('invoice_number', function ($row) {
                    $prefix = $row->client && $row->client->client_type == 'satellite' ? 'SA-' : 'IN-';
                    return '<a href="javascript:void(0)" onclick="invoice_details(\''. route('admin.invoice_details', $row->id) .'\')"
                            class="text-primary fw-bold" title="'. trans('invoices.view_details') .'">
                            ' . $prefix .($row->invoice->invoice_number ?? 'N/A') . '
                        </a>';
                })
                ->addColumn('client', function ($row) {
                    return $row->client ? $row->client->name : 'N/A';
                })
                ->addColumn('received_at', function ($row) {
                    return $row->received_at ?? 'N/A';
                })
                ->addColumn('collected_by', function ($row) {
                    // return $row->user->name ?? 'N/A';
                    return $row->collected_by_name ?? 'N/A';
                })
                // ->addColumn('action', function ($row) {
                //     return '
                //     <div class="btn-group btn-group-sm">
                //         <a href="javascript:void(0)" onclick="showPayModal(\''. route('admin.pay_invoice', $row->id) .'\')" class="btn btn-sm btn-success" title="'. trans('invoices.mark_as_paid') .'" style="font-size: 16px;">
                //             <i class="bi bi-check-circle"></i>
                //         </a>
                //         <a onclick="return confirm(\'' . trans('employees.confirm_delete') . '\')"  href="' . route('admin.delete_invoice', $row->id) . '"  class="btn btn-sm btn-danger" title="' . trans('clients.delete') . '" style="font-size: 16px;">
                //             <i class="bi bi-trash3"></i>
                //         </a>
                //     </div>
                // ';
                // <a href="' . route('admin.invoices.markAsPaid', $row->id) . '" class="btn btn-sm btn-success" title="' . trans('invoices.mark_as_paid') . '" style="font-size: 16px;">

                // <a href="' . route('admin.invoices.edit', $row->id) . '" class="btn btn-sm btn-primary" title="' . trans('clients.edit') . '" style="font-size: 16px;">
                        //     <i class="bi bi-pencil-square"></i>
                        // </a>
                // })
                ->rawColumns(['action', 'invoice_number'])
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
    // public function destroy(string $id)
    // {
    //     try {
    //         $this->InvoiceRepository->delete($id);
    //         toastr()->addSuccess(trans('forms.success'));
    //         return redirect()->route('admin.invoices.index');
    //     } catch (\Exception $e) {
    //         // dd($e->getMessage());
    //         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }

}
