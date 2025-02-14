<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\clients\SaveRequests;
use App\Http\Requests\Admin\clients\UpdateRequests;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Invoice;
use App\Models\Admin\Revenue;
use App\Models\Admin\Subscription;
use App\Models\Clients;
use App\Services\ClientService;
use App\Services\CompanyService;
use App\Services\ProjectsService;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Illuminate\Http\Request;
use DataTables;

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
        $this->SubscriptionRepository = createRepository($basicRepository, new Subscription());
        $this->ClientsRepository = createRepository($basicRepository, new Clients());
        $this->clientService = $clientService;
        $this->InvoiceRepository   = createRepository($basicRepository, new Invoice());

    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $allData = Clients::with('subscription')->select('*');

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
                    return $row->subscription ? $row->subscription->name : '<span class="badge bg-success text-white px-4 py-3 rounded-pill fw-bold fs-5">' . trans('invoices.service') . '</span>';
                })
                ->addColumn('price', function ($row) {
                    return $row->price ?? 'N/A';
                })
                ->addColumn('subscription_date', function ($row) {
                    return $row->subscription_date ? $row->subscription_date : 'N/A';
                })
                ->addColumn('start_date', function ($row) {
                    return $row->start_date ? $row->start_date : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="btn-group">
                        <button type="button" style="font-size: 16px" class="btn btn-sm btn-secondary">' . trans('employees.actions') . '</button>
                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-icon" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a style="font-size: 14px" class="hover-effect dropdown-item" href="' . route('admin.clients.edit', $row->id) . '">
                                    <i class="bi bi-pencil-square"></i> ' . trans('clients.edit_clients') . '
                                </a>
                            </li>
                            <li>
                                <a style="font-size: 14px" class="hover-effect dropdown-item text-danger"
                                onclick="return confirm(\'' . trans('clients.confirm_delete') . '\')"
                                href="' . route('admin.delete_client', $row->id) . '">
                                <i class="bi bi-trash-fill"></i> ' . trans('clients.client_delete') . '
                                </a>
                            </li>
                            <li>
                                <a style="font-size: 14px" class="hover-effect dropdown-item" href="' . route('admin.client_paid_invoices', $row->id) . '">
                                    <i class="bi bi-currency-dollar"></i> ' . trans('clients.client_paid_invoices') . '
                                </a>
                            </li>
                            <li>
                                <a style="font-size: 14px" class="hover-effect dropdown-item" href="' . route('admin.client_unpaid_invoices', $row->id) . '">
                                    <i class="bi bi-receipt-cutoff"></i> ' . trans('clients.client_unpaid_invoices') . '
                                </a>
                            </li>
                            <li>
                                <a style="font-size: 14px" class="hover-effect dropdown-item" href="' . route('admin.client_invoices', $row->id) . '">
                                    <i class="bi bi-file-earmark-plus"></i> ' . trans('clients.client_add_invoice') . '
                                </a>
                            </li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['subscription', 'action'])
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
            dd($e->getMessage());
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
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function get_price($id){
        $subscription = $this->SubscriptionRepository->getById($id);

        if ($subscription) {
            return response()->json(['price' => $subscription->price]);
        }

        return response()->json(['price' => '0']);
    }

    /***********************************************/

    public function client_unpaid_invoices($id){
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
    public function client_paid_invoices($id){
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
        return view($this->admin_view . '.client_paid_invoices', $data);
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
        $data['invoiceNumber'] = $this->InvoiceRepository->getLastFieldValue('invoice_number');
        $data['subscriptions'] = $this->SubscriptionRepository->getAll();
        // dd($data);
        return view($this->admin_view . '.client_invoices', $data);
    }
    /***********************************************/
    public function client_add_invoice(Request $request, $id)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:tbl_invoices,invoice_number',
            'invoice_type' => 'required|in:service,subscription',
            'subscription_id' => 'nullable|exists:tbl_subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'remaining_amount' => 'nullable|numeric|min:0|max:' . $request->amount,
            'notes' => 'nullable|string',
        ]);

        try {

            if ($request->remaining_amount == 0) {
                $status = 'paid';
            } elseif ($request->remaining_amount > 0 && $request->remaining_amount < $request->amount) {
                $status = 'partial';
            } else {
                $status = 'unpaid';
            }

            $remainingAmount = $request->remaining_amount ?? 0;

            $invoiceData = [
                'invoice_number' => $request->invoice_number,
                'client_id' => $id,
                'subscription_id' => $request->invoice_type === 'subscription' ? $request->subscription_id : null,
                'amount' => $request->amount,
                'remaining_amount' => $remainingAmount,
                'enshaa_date' => now()->format('Y-m-d'),
                'invoice_type' => $request->invoice_type,
                'notes' => $request->notes,
                'paid_date' => now(),
                'created_by'=> auth()->user()->id,
                'status' => $status,
            ];

            // dd($invoiceData);

            $invoice = $this->InvoiceRepository->create($invoiceData);

            if ($request->remaining_amount < $request->amount) {
                $admin = auth('admin')->user();
                $collectedBy = $admin && $admin->is_employee ? $admin->emp_id : auth()->user()->id;

                Revenue::create([
                    'invoice_id' => $invoice->id,
                    'client_id' => $id,
                    'amount' => $request->amount - $request->remaining_amount,
                    'collected_by' => $collectedBy,
                    'received_at' => now(),
                ]);
            }

            return redirect()->back()->with('success', trans('clients.invoice_created_successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', trans('clients.failed_to_create_invoice.'));
        }
    }


}
