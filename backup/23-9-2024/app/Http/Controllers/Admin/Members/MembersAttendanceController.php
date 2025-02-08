<?php

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Members\SaveMemberAttendance;
use App\Models\AdditionalMemberSubscriptions;
use App\Models\Members;
use App\Models\MembersAttendance;
use App\Models\MembersInbody;
use App\Models\MembersSubscriptions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembersAttendanceController extends Controller
{
    /***************************************/
    public function index()
    {
        $data['members']=Members::all();
        return view('dashbord.admin.members.member_attendance.form',$data);
    }

    /***************************************/
    public function create()
    {

    }

    /***************************************/
    public function store(SaveMemberAttendance $request)
    {
        //dd($request);
        try {

            if ($request->subscriptions)
            {
                foreach ($request->subscriptions as $key=>$value)
                {
                    $data['member_id']=$request->member_id;
                    $data['additional_subscription_id']=$value;
                    $data['day']=date('Y-m-d');
                    MembersAttendance::create($data);
                }
            }

            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.subscriptions.MembersAttendance.index');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /***************************************/
    public function show(string $id)
    {
        //
    }

    /***************************************/
    public function edit(string $id)
    {
        //
    }

    /***************************************/
    public function update(Request $request, string $id)
    {
        //
    }

    /***************************************/
    public function delete($id)
    {

        try {
            $MembersAttendance=MembersAttendance::find($id);
          //  dd($MembersAttendance);
            $MembersAttendance->delete();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.subscriptions.MembersAttendance.index');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /****************************************/


    public function get_member_subscription(Request $request)
    {
        $member_id = $request->id;
        $today = Carbon::today();

        $member_id = $request->id;
        $today = date('Y-m-d');

        $main_subscriptions = MembersSubscriptions::with('additional_subscriptions')
            ->where('member_id', $member_id)
            ->where('end_date', '>', $today)
            ->first();
        $data['additional_subscriptions'] = AdditionalMemberSubscriptions::with(['special_subscriptions','member_attendance'])
            ->where('member_subscription_id', $main_subscriptions->id)
            ->whereDoesntHave('member_attendance', function ($query) {
                $query->where('day', date('Y-m-d'));
            })
            ->get();


        //dd($data['additional_subscriptions'][0]);

        $data['register_subscriptions']=MembersAttendance::with(['special_subscriptions','additional_subscription'])->where('member_id',$member_id)->where('day',$today)->get();
        //dd($data['register_subscriptions']);


       // dd($data['register_subscriptions'][0]->special_subscriptions);
        return view('dashbord.admin.members.member_attendance.member_subscriptions', $data);
    }

}
