<?php

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Models\AdditionalMemberSubscriptions;
use App\Models\Members;
use App\Models\MembersSubscriptions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MembersReportsController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $today = date('Y-m-d');
            $data = AdditionalMemberSubscriptions::with(['member_subscription','special_subscriptions','member_attendance'])->where('end_date','>',$today)->get();

            //dd($data);


        return DataTables::of($data)
            ->addColumn('id', function () use (&$counter) {
                $counter++;
                return $counter;
            })
            ->editColumn('member_name', function ($row) {
                return $row->member_subscription ? $row->member_subscription->member->member_name : 'N/A';


            })->editColumn('subscription', function ($row) {
                return $row->special_subscriptions->name;
            })->editColumn('session_num', function ($row) {
                return $row->special_subscriptions->duration;
            })->editColumn('session_attendance', function ($row) {
                return get_session_attendance($row->member_subscription->member_id,$row->id);
            })
            ->editColumn('remain_session', function ($row) {

                return ($row->special_subscriptions->duration - get_session_attendance($row->member_subscription->member_id,$row->id));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
        return view('dashbord.admin.members.member_attendance.attendance_reports');
    }

    /****************************************************/

    public function expire_mainSubscription()
    {
        $today = Carbon::now();
        $startDate = $today->copy()->addDays(1)->format('Y-m-d');
        $endDate = $today->copy()->addDays(7)->format('Y-m-d');

        $data['all_data'] = MembersSubscriptions::whereBetween('end_date', [$startDate, $endDate])->get();
      //  dd($data);

        return view('dashbord.admin.members.member_attendance.expire_main_subscription_reports',$data);

    }
    /****************************************************/

    public function expire_specialSubscription()
    {
        $today = Carbon::now();
        $startDate = $today->copy()->addDays(1)->format('Y-m-d');
        $endDate = $today->copy()->addDays(7)->format('Y-m-d');

        $data['all_data'] = AdditionalMemberSubscriptions::with(['member_subscription', 'special_subscriptions', 'member_attendance'])
            ->whereBetween('end_date', [$startDate, $endDate])
            ->get();

         // dd($data['all_data']);
        $data['all_data2'] = AdditionalMemberSubscriptions::with(['member_subscription', 'special_subscriptions', 'member_attendance'])
            ->where('end_date', '>',$endDate)
            ->get();
        $filteredData = $data['all_data2']->filter(function ($row) {
            $memberId = $row->member_subscription->member_id;
            $subscriptionId = $row->id;

            $duration = $row->special_subscriptions->duration;
            $attendance = get_session_attendance($memberId, $subscriptionId);
            return ($duration - $attendance) <= 5;
        });
        $data['filtered_data'] = $filteredData->values();

        return view('dashbord.admin.members.member_attendance.expire_special_subscription_reports', $data);
    }



    /****************************************************/
    public function create()
    {
        //
    }

    /****************************************************/
    public function store(Request $request)
    {
        //
    }

    /****************************************************/
    public function show(string $id)
    {
        //
    }

    /****************************************************/
    public function edit(string $id)
    {
        //
    }

    /****************************************************/
    public function update(Request $request, string $id)
    {
        //
    }

    /****************************************************/
    public function destroy(string $id)
    {

    }
}
