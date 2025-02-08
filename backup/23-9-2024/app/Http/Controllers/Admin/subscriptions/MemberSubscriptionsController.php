<?php

namespace App\Http\Controllers\Admin\subscriptions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\subscription\member_subscriptions\SaveMemberSubscriptions;
use App\Models\AdditionalMemberSubscriptions;
use App\Models\Members;
use App\Models\MembersSubscriptions;
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

    protected $view = 'dashbord.admin.subscriptions.member_subscriptions.';
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

                    return optional($row->member)->member_name;

                })->editColumn('process_num', function ($row) {
                    return $row->process_num;
                })->editColumn('process_date', function ($row) {
                    return $row->process_date;
                })
                ->addColumn('actions', function ($row) {
                    return '
        <div class="dropdown">
            <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                ' . trans('forms.action') . '
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->process_num . '">
                <li><a class="dropdown-item" href="' . route('admin.subscriptions.member_subscriptions.edit', $row->process_num) . '">' . trans('forms.edit_btn') . '</a></li>
                <li><a class="dropdown-item" href="' . route('admin.subscriptions.member_subscriptions.show', $row->process_num) . '">' . trans('forms.print') . '</a></li>
                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="show_details(' . $row->process_num . ')"> ' . trans('forms.details') . '</a></li>
                <li> <a class="dropdown-item"  href="' . route('admin.subscriptions.member-subscriptions.destroy', $row->process_num) . '"  data-kt-table-delete="delete_row" title="' . trans('forms.delete_btn') . '">
                        ' . trans('forms.delete_btn') . '
                    </a>
                </li>
            </ul>
        </div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('dashbord.admin.subscriptions.member_subscriptions.index');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $data['members'] = Members::all();
        $data['transportation'] = Transportation_M::all();
        $data['trainers'] = Trainers::all();
        $lastProcessNum = MembersSubscriptions::max('process_num');
        $data['process_num'] = $lastProcessNum ?? 0;
        //dd($data);
        return view('dashbord.admin.subscriptions.member_subscriptions.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    /****************************************************************/
    public function store(SaveMemberSubscriptions $request)
    {
        try {
            $sub_data_arr = $request->kt_docs_repeater_advanced;

            foreach ($sub_data_arr as $item) {
                $sub_data = $this->prepareSubscriptionData($request, $item);

                MembersSubscriptions::create($sub_data);
            }

            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.subscriptions.member_subscriptions.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /****************************************************************/

    /****************************************************************/
    public function show(string $id)
    {

        $data['one_data'] = MembersSubscriptions::with('member', 'special_subscriptions', 'main_subscriptions')->where('process_num', $id)->get();
//        dd($data);
        return view('dashbord.admin.subscriptions.member_subscriptions.details', $data);
    }

    /****************************************************************/
    public function edit(string $id)
    {
        $data['members'] = Members::all();
        $data['transportation'] = Transportation_M::all();
        $data['trainers'] = Trainers::all();
        // $data['one_data']=MembersSubscriptions::find($id);
        $data['one_data'] = $subscription = MembersSubscriptions::where('process_num', $id)->get();
        //dd($data['one_data']);
        return view('dashbord.admin.subscriptions.member_subscriptions.edit', $data);
    }

    /****************************************************************/
    public function update(SaveMemberSubscriptions $request, string $id)
    {
        //dd($request);
        DB::beginTransaction();

        try {

            $member_id = $request->member_id;
            $process_num = $request->process_num;

            MembersSubscriptions::where('process_num', $process_num)->delete();
            $df = $this->processSubscriptionData($request);


            $sub_data_arr = $request->kt_docs_repeater_advanced;

            if (!empty($sub_data_arr)) {
                foreach ($sub_data_arr as $item) {
                    $sub_data = $this->prepareSubscriptionData($request, $item);
                    MembersSubscriptions::create($sub_data);
                }
            }

            DB::commit();

            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.subscriptions.member_subscriptions.index');
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

        if (isset($item['transportation'])&&(!empty($item['transportation']))) {
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
            $subscription=MembersSubscriptions::where('process_num', $id)->first();
            $subscription = MembersSubscriptions::where('process_num', $id)->delete();
             AdditionalMemberSubscriptions::where('member_subscription_id', $subscription->id)->delete();

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
        $data['all_data'] = MembersSubscriptions::with('member', 'special_subscriptions', 'main_subscriptions')->where('process_num', $process_num)->get();
        return view('dashbord.admin.subscriptions.member_subscriptions.show', $data);
    }

    /**********************************************/
    public function delete_addtional_subscription(Request $request)
    {
        try {
            $id = $request->id;
            $subscription = AdditionalMemberSubscriptions::find($id);
            $subscription->delete();
            toastr()->addSuccess(trans('sub.add_msg'));
            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
