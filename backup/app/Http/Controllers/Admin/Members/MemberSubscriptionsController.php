<?php

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\subscription\member_subscriptions\SaveMemberSubscriptions;
use App\Models\AdditionalMemberSubscriptions;
use App\Models\Members;
use App\Models\MembersSubscriptions;
use App\Models\Site\SiteData;
use App\Models\subscriptions\MainSubscription_M;
use App\Models\subscriptions\SpecialSubscription_M;
use App\Models\subscriptions\Transportation_M;
use App\Models\Trainers;
use App\Traits\ImageProcessing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MemberSubscriptionsController extends Controller
{
    use ImageProcessing;

    protected $view = 'dashbord.admin.members.member_subscriptions.';
    private $upload_folder = 'Member_subscriptions';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subQuery = MembersSubscriptions::selectRaw('MIN(id) as id')
                ->groupBy('process_num')
                ->toSql();
            $data = MembersSubscriptions::whereIn('id', function ($query) use ($subQuery) {
                $query->select('id')
                    ->from(DB::raw("($subQuery) as sub"));
            })->get();

            $counter = 0;

            return DataTables::of($data)
                ->addColumn('id', function () use (&$counter) {
                    $counter++;
                    return $counter;
                })
                ->editColumn('member_name', function ($row) {

                    return $row->member->member_name;

                })->editColumn('process_num', function ($row) {
                    return $row->process_num;
                })->editColumn('process_date', function ($row) {
                    return $row->process_date;
                })->editColumn('end_date', function ($row) {
                    return $row->end_date;
                })
                ->editColumn('sub_status', function ($row) {

                    $today = date('Y-m-d');
                    $end_date = $row->end_date;

                    if ($today >= $end_date) {
                        $status = trans('members.closed');
                    } else {
                        $status = trans('members.opened');
                    }
                    return $status;
                })
                ->addColumn('actions', function ($row) {
                    $today = date('Y-m-d');
                    $end_date = $row->end_date;

                    if ($today >= $end_date) {
                        $additiona_sub = '';
                    } else {
                        $additiona_sub = ' <a href="' . route('admin.subscriptions.additional_subscription', $row->process_num) . '" class="btn btn-sm btn-light btn-active-light-primary ms-2" title="' . trans('forms.add_additional_subscription') . '">
                                         <i class="bi bi-plus fs-3"></i>
                                     </a>';
                    }

                    return '
    <div class="d-flex align-items-center">
        <a href="' . route('admin.subscriptions.member-subscriptions.edit', $row->process_num) . '" class="btn btn-sm btn-light btn-active-light-primary" title="' . trans('forms.edit_btn') . '">
            <i class="bi bi-pencil fs-3"></i>
        </a>
        ' . $additiona_sub . '

        <a href="javascript:print_subscription('.$row->process_num.')" class="btn btn-sm btn-light btn-active-light-primary ms-2" title="' . trans('forms.print') . '">
            <i class="bi bi-printer fs-3"></i>
        </a>
        <a href="#" class="btn btn-sm btn-light btn-active-light-primary ms-2" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="show_details(' . $row->process_num . ')" title="' . trans('forms.details') . '">
            <i class="bi bi-info-circle fs-3"></i>
        </a>
        <a href="' . route('admin.subscriptions.member-subscriptions.destroy', $row->process_num) . '" class="btn btn-sm btn-light btn-active-light-primary ms-2" data-kt-table-delete="delete_row" title="' . trans('forms.delete_btn') . '">
            <i class="bi bi-trash fs-3"></i>
        </a>
    </div>';


                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        // dd('ss');
        return view('dashbord.admin.members.member_subscriptions.index');
    }

    /****************************************************************/
    public function create()
    {
        $data['members'] = Members::all();
        $data['members_subscriptions'] = [];
        $data['main_subscriptions'] = MainSubscription_M::all();
        $data['special_subscriptions'] = SpecialSubscription_M::all();
        $data['transportation'] = Transportation_M::all();
        $data['trainers'] = Trainers::all();
        $lastProcessNum = MembersSubscriptions::max('process_num');
        $data['process_num'] = $lastProcessNum ?? 0;
        //dd($data);
        return view('dashbord.admin.members.member_subscriptions.form', $data);
    }

    /****************************************************************/
    public function store(SaveMemberSubscriptions $request)
    {
        try {

            //    dd($request);
            $data['type'] = 'main';
            $data['member_id'] = $request->member_id;
            $data['subscription_id'] = $request->main_subscription_id;
            $data['subscription_id'] = $request->main_subscription_id;
            $data['start_date'] = $request->main_start_date;
            $data['end_date'] = $request->end_date;
            $data['transport'] = $request->transportation;
            $data['discount'] = $request->main_discount;
            $data['pay_method'] = $request->pay_method;
            $data['transport_value'] = $request->transport_price;
            $data['process_num'] = $request->process_num;
            $data['process_date'] = date('Y-m-d');
            $data['process_type'] = 'new';
            $main_members_subscriptions = MembersSubscriptions::create($data);

            $sub_data_arr = $request->kt_docs_repeater_advanced;

            if (!empty($sub_data_arr) && is_array($sub_data_arr)) {
                foreach ($sub_data_arr as $item) {
                    $sub_data['member_subscription_id'] = $main_members_subscriptions->id;
                    $sub_data['type'] = $item['type'];
                    $sub_data['subscription_id'] = $item['subscription_id'];
                    $sub_data['start_date'] = $item['start_date'];
                    $sub_data['end_date'] = $request['end_date'];
                    $sub_data['added_date'] = date('Y-m-d');
                    $sub_data['trainer_id'] = $item['trainer_id'];
                    $sub_data['discount'] = $item['discount'];

                    AdditionalMemberSubscriptions::create($sub_data);
                }
            }


            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.subscriptions.member-subscriptions.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /****************************************************************/
    public function additional_subscriptions($process_num)
    {
        $data['members'] = Members::all();
        $data['members_subscriptions'] = MembersSubscriptions::where('process_num', $process_num)->first();
        $data['main_subscriptions'] = MainSubscription_M::all();
        $data['special_subscriptions'] = SpecialSubscription_M::all();
        $data['transportation'] = Transportation_M::all();
        $data['trainers'] = Trainers::all();
        $lastProcessNum = MembersSubscriptions::max('process_num');
        $data['process_num'] = $lastProcessNum ?? 0;
        //dd($data);
        return view('dashbord.admin.members.member_subscriptions.form', $data);
    }

    /****************************************************************/
    public function add_additional_subscriptions(Request $request, $id)
    {
        try {

            $sub_data_arr = $request->kt_docs_repeater_advanced;

            foreach ($sub_data_arr as $item) {
                $sub_data['member_subscription_id'] = $id;
                $sub_data['type'] = $item['type'];
                $sub_data['subscription_id'] = $item['subscription_id'];
                $sub_data['subscription_id'] = $item['subscription_id'];
                $sub_data['start_date'] = $item['start_date'];
                $sub_data['end_date'] = $request['end_date'];
                $sub_data['added_date'] = date('Y-m-d');
                $sub_data['trainer_id'] = $item['trainer_id'];
                $sub_data['discount'] = $item['discount'];
                AdditionalMemberSubscriptions::create($sub_data);
            }
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.subscriptions.member-subscriptions.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /****************************************************************/
    public function show(string $id)
    {
//        $data['one_data'] = MembersSubscriptions::with('member', 'special_subscriptions', 'main_subscriptions','additional_subscriptions')->where('process_num', $id)->get();
        $data['one_data'] = MembersSubscriptions::where('process_num', $id)->first();
        //        dd($data);
        return view('dashbord.admin.subscriptions.member_subscriptions.details', $data);
    }



    /****************************************************************/
    public function edit(string $id)
    {
        $data['members'] = Members::all();
        $data['transportation'] = Transportation_M::all();
        $data['main_subscriptions'] = MainSubscription_M::all();
        $data['special_subscriptions'] = SpecialSubscription_M::all();
        $data['transportation'] = Transportation_M::all();
        $data['trainers'] = Trainers::all();
        // $data['one_data']=MembersSubscriptions::find($id);

        $data['members_subscriptions'] = MembersSubscriptions::where('process_num', $id)->first();
        //dd($data['members_subscriptions']->additional_subscriptions);
        return view('dashbord.admin.members.member_subscriptions.edit', $data);

    }

    /****************************************************************/
    public function update(SaveMemberSubscriptions $request, string $id)
    {
        //dd($request);
        DB::beginTransaction();

        try {
            $main_members_subscriptions = MembersSubscriptions::find($id);
            $main_members_subscriptions->type = 'main';
            $main_members_subscriptions->member_id = $request->member_id;
            $main_members_subscriptions->subscription_id = $request->main_subscription_id;
            $main_members_subscriptions->start_date = $request->main_start_date;
            $main_members_subscriptions->end_date = $request->end_date;
            $main_members_subscriptions->transport = $request->transportation;
            $main_members_subscriptions->discount = $request->main_discount;
            $main_members_subscriptions->pay_method = $request->pay_method;
            $main_members_subscriptions->transport_value = $request->transport_price;
            $main_members_subscriptions->process_num = $request->process_num;
            $main_members_subscriptions->process_date = date('Y-m-d');
            $main_members_subscriptions->update();

            $main_members_subscriptions = AdditionalMemberSubscriptions::where('member_subscription_id', $id)->delete();
            if ($request->type) {
                $count = count($request->type);

                if ($count != 0) {
                    for ($i = 0; $i < $count; $i++) {
                        $sub_data = [
                            'member_subscription_id' => $id,
                            'type' => $request->type[$i],
                            'subscription_id' => $request->subscription_id[$i],
                            'start_date' => $request->start_date[$i],
                            'end_date' => $request->end_date,
                            'added_date' => date('Y-m-d'),
                            'trainer_id' => $request->trainer_id[$i],
                            'discount' => $request->discount[$i],
                        ];
                        /* $main_members_subscriptions = AdditionalMemberSubscriptions::where('member_subscription_id', $id)->delete();

                         if ($request->type){
                             $count = count($request->type);*/

                        AdditionalMemberSubscriptions::create($sub_data);
                    }

                }
            }
//        }

            $sub_data_arr = $request->kt_docs_repeater_advanced;

            if (!empty($sub_data_arr) && is_array($sub_data_arr)) {
                foreach ($sub_data_arr as $item) {
                    $sub_data['member_subscription_id'] = $main_members_subscriptions->id;
                    $sub_data['type'] = $item['type'];
                    $sub_data['subscription_id'] = $item['subscription_id'];
                    $sub_data['start_date'] = $item['start_date'];
                    $sub_data['end_date'] = $request['end_date'];
                    $sub_data['added_date'] = date('Y-m-d');
                    $sub_data['trainer_id'] = $item['trainer_id'];
                    $sub_data['discount'] = $item['discount'];

                    AdditionalMemberSubscriptions::create($sub_data);
                }
            }

            DB::commit();

            toastr()->addSuccess(trans('forms.success'));

            return redirect()->route('admin.subscriptions.member-subscriptions.index');
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**********************************************************/
    private function processSubscriptionData($request)
    {
        // dd($request);
        $count = count($request->type);

        if ($count != 0) {

            if ($request->hasFile('transfer_image')) {
                $file = $request->file('transfer_image');
                $transfer_image = $this->saveImage($file, $this->upload_folder);
            }
            for ($i = 0; $i < $count; $i++) {
                $sub_data = [
                    'member_id' => $request->member_id,
                    'process_num' => $request->process_num,
                    'process_date' => date('Y-m-d'),
                    'type' => $request->type[$i],
                    'subscription_id' => $request->subscription_id[$i],
                    'start_date' => $request->start_date[$i],
                    'added_date' => date('Y-m-d'),
                    'discount' => $request->discount[$i],
                    'pay_method' => $request->pay_method,
                    'transfer_image' => $transfer_image
                ];

                if ($request->transportation) {

                    $sub_data['transport'] = 'yes';
                    $sub_data['transport_value'] = ($request->duration[$i] * getMainData()->transport_value);
                } else {
                    // dd('dd');
                    $sub_data['transport'] = 'no';
                }


                if ($sub_data['type'] == 'main') {
                    $sub_data['end_date'] = $request->end_date[$i];
                } elseif ($sub_data['type'] == 'special') {
                    $sub_data['trainer_id'] = $request->trainer_id[$i];
                }

                MembersSubscriptions::create($sub_data);
            }
        }
    }

    /**********************************************************/
    private function prepareSubscriptionData($request, $item)
    {

        $sub_data = [
            'process_num' => $request->process_num,
            'process_date' => date('Y-m-d'),
            'member_id' => $request->member_id,
            'type' => $item['type'],
            'subscription_id' => $item['subscription_id'],
            'start_date' => $item['start_date'],
            'added_date' => date('Y-m-d'),

            'discount' => $item['discount'],
            'pay_method' => $request->pay_method,
        ];

        if (isset($item['transportation']) && (!empty($item['transportation']))) {
            $sub_data['transport'] = 'yes';
            $sub_data['transport_value'] = ($item['duration'] * getMainData()->transport_value);
        } else {
            $sub_data['transport'] = 'no';
        }

        if ($request->hasFile('transfer_image')) {
            $file = $request->file('transfer_image');
            $sub_data['transfer_image'] = $this->saveImage($file, $this->upload_folder);
        }

        if ($sub_data['type'] == 'main') {
            $sub_data['end_date'] = $item['end_date'];
        } elseif ($sub_data['type'] == 'special') {
            $sub_data['trainer_id'] = $item['trainer_id'];
        }

        return $sub_data;
    }

    /**********************************************************/


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $subscription = MembersSubscriptions::where('process_num', $id)->delete();

            toastr()->addSuccess(trans('sub.add_msg'));
            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /******************************************************/
    public function delete_subscription(Request $request)
    {
        try {
            $id = $request->id;
            $subscription = MembersSubscriptions::find($id);
            $process_num = $subscription->process_num;
            $subscription->delete();
            toastr()->addSuccess(trans('sub.add_msg'));
            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /************************************************/
    public function get_member_subscription_details(Request $request)
    {
        $process_num = $request->process_num;
        $data['all_data'] = MembersSubscriptions::where('process_num', $process_num)->first();
        return view('dashbord.admin.members.member_subscriptions.show', $data);
    }



    public function print(Request $request)
    {

        $process_num = $request->subscription_num;
        $data['main_data']=SiteData::first();
        $data['one_data'] = MembersSubscriptions::where('process_num', $process_num)->first();
        $url = route('printQr',$process_num);
        $data['qrCode'] = \QrCode::size(150)->generate($url);

//                dd($data);
        return view('dashbord.admin.subscriptions.member_subscriptions.print',$data);
    }
}
