<?php

namespace App\Http\Controllers\Admin\subscriptions;

use App\Http\Controllers\Controller;
use App\Models\Members;
use App\Models\MembersSubscriptions;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function MembersSubscriptions(Request $request)
    {

        $data['members'] = Members::all();
        if ($request->ajax()) {
            $sql = MembersSubscriptions::select('*');
//            dd($request->all());
            if ($request->member_id) {
                $sql->where('member_id', $request->member_id);
            }
            if ($request->type) {
                $sql->where('type', $request->type);
            }
            if ($request->subscription_id) {
                $sql->where('subscription_id', $request->subscription_id);
            }
            return Datatables::of($sql)
                ->editColumn('member_name', function ($row) {

                    return $row->member->member_name;
                })->editColumn('member_email', function ($row) {

                    return $row->member->email;
                })->editColumn('member_phone', function ($row) {

                    return $row->member->phone;
                })
                ->editColumn('type', function ($row) {
                    $cat_arr = [
                        'main' => trans('members.main_subscription'),
                        'special' => trans('members.special_subscription')
                    ];

                    return $cat_arr[$row->type] ?? null;

                })
                ->editColumn('subscription', function ($row) {
                    $subscription = '';
                    if ($row->type == 'main') {
                        $subscription = $row->main_subscriptions->name;
                    } elseif ($row->type = 'special') {
                        $subscription = $row->special_subscriptions->name;
                    }
                    return $subscription;
                })->editColumn('price', function ($row) {
                    $subscription = '';
                    if ($row->type == 'main') {
                        $subscription = $row->main_subscriptions->price;
                    } elseif ($row->type = 'special') {
                        $subscription = $row->special_subscriptions->price;
                    }
                    return $subscription;

                })->editColumn('added_date', function ($row) {
                    return formatDateDayDisplay($row->added_date);
                })->editColumn('start_date', function ($row) {
                    return formatDateDayDisplay($row->start_date);
                })
                ->orderColumn('member_id', function ($query, $order) {
                    $query->orderBy('member_id', $order);
                })
                ->orderColumn('subscription_id', function ($query, $order) {
                    $query->orderBy('subscription_id', $order);
                })
                ->orderColumn('type', function ($query, $order) {
                    $query->orderBy('type', $order);
                })
                ->filterColumn('member_name', function ($query, $keyword) {
                    $query->whereHas('member', function ($q) use ($keyword) {
                        $q->where('member_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('member_email', function ($query, $keyword) {
                    $query->whereHas('member', function ($q) use ($keyword) {
                        $q->where('email', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('member_phone', function ($query, $keyword) {
                    $query->whereHas('member', function ($q) use ($keyword) {
                        $q->where('phone', 'like', "%{$keyword}%");
                    });
                })
                /*                ->addColumn('name', function ($row) {
                                    return '
                        <div class="d-flex align-items-center">
                        <!--begin:: Avatar -->
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                   <div class="symbol-label">
                                            <img src="' . $row->member->image_url . '" alt="" class="w-100">
                                   </div>
                            </div>
                            <!--end::Avatar-->
                            <!--begin::User details-->
                            <div class="d-flex flex-column">
                                <a href="' . route('admin.TrainingCenters.show', $row->id) . '"  class="text-gray-800 text-hover-primary mb-1">' . $row->member->member_name . '</a>
                                <span>' . $row->member->email . '</span>
                            </div>
                            <!--begin::User details-->
                            </div>';
                                })*/
                ->make(true);
        }

        return view('dashbord.admin.subscriptions.reportes.member_subscriptions', $data);
    }


}
