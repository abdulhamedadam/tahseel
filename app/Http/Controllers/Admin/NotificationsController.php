<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Invoice;
use App\Models\Clients;
use App\Notifications\InvoiceReminderNotification;
use App\Notifications\NewClientAddedNotification;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
// use DataTables;
class NotificationsController extends Controller
{

    use ImageProcessing;
    use ValidationMessage;

    /***********************************************************/

    protected $ClientsRepository;

    public function __construct(BasicRepositoryInterface $basicRepository)
    {
        $this->ClientsRepository = createRepository($basicRepository, new Clients());
    }
    /***********************************************************/

    public function new_clients()
    {
        return view('dashbord.notifications.new_clients');
    }

    /***********************************************************/
    public function get_ajax_notifications()
    {
        if (request()->ajax()) {
            try {
                $notifications = auth()->user()->notifications->where('type', NewClientAddedNotification::class);

                $counter = 0;

                return DataTables::of($notifications)
                    ->addColumn('id', function () use (&$counter) {
                        $counter++;
                        return $counter;
                    })
                    ->addColumn('message', function ($row) {
                        return $row->data['message'] ?? 'تم إضافة عميل جديد';
                    })
                    ->addColumn('client_name', function ($row) {
                        return '<a href="' . route('admin.client_unpaid_invoices', $row->data['client_id']) . '" class="text-primary" style="text-decoration: underline;">
                                    ' . trans('notifications.client_details') . '
                                </a>';
                    })
                    ->addColumn('created_at', function ($row) {
                        return $row->created_at->format('Y-m-d H:i:s');
                    })
                    ->addColumn('status', function ($row) {
                        return $row->read_at ? trans('notifications.read') : trans('notifications.unread');
                    })
                    ->addColumn('action', function ($row) {
                        if (!$row->read_at) {
                            return '<a href="' . route('admin.mark_notification_read', $row->id) . '" class="btn btn-sm btn-primary">
                                        ' . trans('notifications.mark_as_read') . '
                                    </a>';
                        }
                        return '<span class="badge bg-success">' . trans('notifications.read') . '</span>';
                    })
                    ->setRowClass(function ($row) {
                        return $row->read_at ? 'table-light' : 'table-warning';
                    })
                    ->rawColumns(['client_name', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                Log::error('Error in get_ajax_notifications: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()]);
            }
        }
    }

    /*****************************************************/
    public function mark_notification_read($id)
    {
        $notification = auth()->user()->notifications->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->back()->with('success', trans('notifications.notification_read'));
    }
    /***********************************************************/

    public function unpaid_invoices()
    {
        return view('dashbord.notifications.invoices');
    }
    /*****************************************************/
    public function get_ajax_invoice_notifications()
    {
        if (request()->ajax()) {
            try {
                $notifications = auth()->user()->notifications->where('type', InvoiceReminderNotification::class);

                $counter = 0;

                return DataTables::of($notifications)
                    ->addColumn('id', function () use (&$counter) {
                        $counter++;
                        return $counter;
                    })
                    ->addColumn('invoice_number', function ($row) {
                        $invoice = Invoice::find($row->data['invoice_id']);
                        if (!$invoice) return 'N/A';

                        $client = Clients::find($invoice->client_id);
                        $prefix = ($client && $client->client_type == 'satellite') ? 'SA-' : 'IN-';

                        return '<a href="javascript:void(0)" onclick="invoice_details(\''. route('admin.invoice_details', $invoice->id) .'\')"
                                class="text-primary fw-bold" title="'. trans('invoices.view_details') .'">
                                ' . $prefix . $invoice->invoice_number . '
                            </a>';
                    })
                    ->addColumn('message', function ($row) {
                        return $row->data['message'] ?? 'تنبيه بفاتورة مستحقة';
                    })
                    ->addColumn('amount', function ($row) {
                        return number_format($row->data['amount'], 2) . ' ' . trans('notifications.currency');
                    })
                    ->addColumn('paid_amount', function ($row) {
                        return number_format($row->data['paid_amount'], 2) . ' ' . trans('notifications.currency');
                    })
                    ->addColumn('remaining_amount', function ($row) {
                        return number_format($row->data['remaining_amount'], 2) . ' ' . trans('notifications.currency');
                    })
                    ->addColumn('due_date', function ($row) {
                        return $row->data['due_date'];
                    })
                    ->addColumn('client', function ($row) {
                        return '<a href="' . route('admin.client_unpaid_invoices', $row->data['client']) . '" class="text-primary" style="text-decoration: underline;">
                                    ' . trans('notifications.client_details') . '
                                </a>';
                    })
                    ->addColumn('status', function ($row) {
                        return $row->read_at ? trans('notifications.read') : trans('notifications.unread');
                    })
                    ->addColumn('month_year', function ($row) {
                        return $row->created_at ? Carbon::parse($row->created_at)->format('F Y') : 'N/A';
                    })
                    ->addColumn('action', function ($row) {
                        if (!$row->read_at) {
                            return '<a href="' . route('admin.mark_notification_read', $row->id) . '" class="btn btn-sm btn-primary">
                                        ' . trans('notifications.mark_as_read') . '
                                    </a>';
                        }
                        return '<span class="badge bg-success">' . trans('notifications.read') . '</span>';
                    })
                    ->setRowClass(function ($row) {
                        return $row->read_at ? 'table-light' : 'table-warning';
                    })
                    ->rawColumns(['invoice_number', 'client', 'action', 'month_year'])
                    ->make(true);
            } catch (\Exception $e) {
                Log::error('Error in get_ajax_invoice_notifications: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()]);
            }
        }
    }

    // /*****************************************************/
    // public function delete_subscription(Request $request,$id)
    // {
    //     try {
    //         $subscription = $this->SubscriptionsRepository->getById($id);
    //         $this->SubscriptionsRepository->delete($id);
    //         $request->session()->flash('toastMessage', trans('subscription_deleted_successfully'));
    //         return redirect()->route('admin.subscriptions');

    //     } catch (\Exception $e) {
    //         test($e->getMessage());
    //         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }

    // /****************************************************/
    // public function sarf_bands()
    // {
    //     return view('dashbord.admin.settings.sarf_band');
    // }

    // /***********************************************************/
    // public function get_ajax_sarf_bands()
    // {
    //     if (request()->ajax()) {
    //         try {
    //             $data = $this->SarfBandRepository->getAll();

    //             $counter = 0;

    //             return DataTables::of($data)
    //                 ->addColumn('id', function () use (&$counter) {
    //                     $counter++;
    //                     return $counter;
    //                 })
    //                 ->addColumn('title', function ($row) {
    //                     return $row->title;
    //                 })
    //                 ->addColumn('action', function ($row) {
    //                     return '<a data-bs-toggle="modal" data-bs-target="#modalSarfBands" onclick="edit_sarf_band(' . $row->id . ')" class="btn btn-sm btn-warning" title="">
    //                         <i class="bi bi-pencil"></i>
    //                     </a>
    //                     <a onclick="return confirm(\'Are You Sure To Delete?\')" href="' . route('admin.delete_sarf_band', $row->id) . '"  class="btn btn-sm btn-danger">
    //                         <i class="bi bi-trash"></i>
    //                     </a>';
    //                 })
    //                 ->rawColumns(['action'])
    //                 ->make(true);
    //         } catch (\Exception $e) {
    //             Log::error('Error in get_ajax_sarf_bands: ' . $e->getMessage());
    //             return response()->json(['error' => $e->getMessage()]);
    //         }
    //     }
    // }

    // /*****************************************************/
    // public function add_sarf_band(Request $request)
    // {
    //     try {
    //         // dd($request->all());
    //         $sarf_band_Model = new SarfBand();
    //         $data = $sarf_band_Model->add_sarf_band_data($request);
    //         if(empty($request->row_id))
    //         {
    //             $this->SarfBandRepository->create($data);

    //         }else{
    //             $this->SarfBandRepository->update($request->row_id, $data);
    //         }
    //         // notify()->success(trans('sarf_band_added_successfully'), '');
    //         $request->session()->flash('toastMessage', trans('sarf_band_added_successfully'));
    //         return redirect()->route('admin.sarf_bands');

    //     } catch (\Exception $e) {
    //         test($e->getMessage());
    //         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    //     }

    // }
    // /*****************************************************/
    // public function delete_sarf_band(Request $request,$id)
    // {
    //     try {
    //         $bsarf_band = $this->SarfBandRepository->getById($id);
    //         $this->SarfBandRepository->delete($id);
    //         // notify()->success(trans('sarf_band_deleted_successfully'), '');
    //         $request->session()->flash('toastMessage', trans('sarf_band_deleted_successfully'));
    //         return redirect()->route('admin.sarf_bands');

    //     } catch (\Exception $e) {
    //         test($e->getMessage());
    //         return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }

    // /****************************************************/
    // public function edit_sarf_band($id)
    // {
    //     $data['all_data']=$this->SarfBandRepository->getById($id);
    //     return response()->json($data);
    // }
}
