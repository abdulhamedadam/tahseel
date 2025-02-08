<?php

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Admin\subscriptions\SubscriptionSettings_C;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Members\SaveInbodyRequest;
use App\Http\Requests\Admin\Members\StoreRequest;
use App\Http\Requests\Admin\Members\UpdateRequest;
use App\Http\Resources\Admin\MembersResource;
use App\Models\Members;
use App\Models\MembersGoals;
use App\Models\MembersInbody;
use App\Models\MembersSubscriptions;
use App\Models\Site\SiteEvent;
use App\Models\subscriptions\MainSubscription_M;
use App\Models\subscriptions\SpecialSubscription_M;
use App\Models\subscriptions\SubscriptionSettings_M;
use App\Models\subscriptions\Transportation_M;
use App\Models\Trainers;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \DateTime;

class MembersController extends Controller
{
    private $view_root = 'dashbord.admin.members.';
    private $upload_folder = 'Members';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $allData = Members::select('*');
            return Datatables::of($allData)
                ->addColumn('action', function ($row) {
                    return '<a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">' . trans('forms.action') . '
                    <span class="svg-icon svg-icon-5 m-0">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579
                            8.13583 6.16421 8.13584 5.75 8.55005C5.33579
                            8.96426 5.33579 9.63583 5.75 10.05L11.2929
                            15.5929C11.6834 15.9835 12.3166 15.9835
                            12.7071 15.5929L18.25 10.05C18.6642 9.63584
                            18.6642 8.96426 18.25 8.55005C17.8358 8.13584
                            17.1642 8.13584 16.75 8.55005L12.5657
                            12.7344C12.2533 13.0468 11.7467 13.0468
                            11.4343 12.7344Z" fill="currentColor" />
                        </svg>
                    </span>
                </a>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <a href="' . route('admin.Members.edit', $row->id) . '"
                           title="' . trans('forms.edit_btn') . '" class="menu-link px-3"
                        >' . trans('forms.edit_btn') . '</a>
                    </div>
                    <div class="menu-item px-3">
                        <a href="' . route('admin.Members.show', $row->id) . '"
                           title="' . trans('forms.details') . '" class="menu-link px-3"
                        >' . trans('forms.details') . '</a>
                    </div>
                    <div class="menu-item px-3">
                        <a href="' . route('admin.Members.delete', $row->id) . '"  data-kt-table-delete="delete_row2"  title="' . trans('forms.delete_btn') . '" class="menu-link px-3">' . trans('forms.delete_btn') . '</a>

                    </div>
                </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashbord.admin.members.data_list');
    }
    /***********************************************************/
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['goals'] = SubscriptionSettings_M::where('ttype', 'goals')->get();
        $lastProcessNum = MembersSubscriptions::max('process_num');
        $data['process_num'] = $lastProcessNum ?? 0;
        //dd($data['process_num']);
        $data['transportation'] = Transportation_M::all();
        $data['trainers'] = Trainers::all();
        $data['health_status'] = SubscriptionSettings_M::where('ttype', 'health_status')->get();
        return view('dashbord.admin.members.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(StoreRequest $request)
    {
//        dd($request->all());
        DB::beginTransaction();

        try {
            // Get all the request data

            //  dd($request->kt_docs_repeater_advanced[0]['duration']*getMainData()->transport_value);
            $insert_data['publisher_id'] = auth()->user()->id;
            $insert_data['publisher_name'] = auth()->user()->name;
            $insert_data['member_name'] = $request->member_name;
            $insert_data['birth_date'] = $request->birth_date;
            $insert_data['email'] = $request->email;
            $insert_data['phone'] = $request->phone;
            $insert_data['country_code'] = $request->country_code;
            $insert_data['phone_full'] = $request->phone_full;
            $insert_data['health_status_id'] = $request->health_status_id;

            // Handle the image file if it exists
            if ($request->hasFile('member_image')) {
                $file = $request->file('member_image');
                $dataX = $this->saveImage($file, $this->upload_folder);
                $insert_data['member_image'] = $dataX;
            }

            // Create the member record
            $inserted_data = Members::create($insert_data);
            $insert_id = $inserted_data->id;


            // Create the member goals
            $goals = $request->goal_id;
            foreach ($goals as $goal) {
                $data['goal_id'] = $goal;
                $data['member_id'] = $insert_id;
                MembersGoals::create($data);
            }
            // Commit the transaction
            DB::commit();

            // Show success message and redirect
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.Members.index');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $one_data = Members::with('health_status', 'goals.subsetting_goals', 'inbody', 'members_subscriptions.additional_subscriptions.special_subscriptions', 'members_subscriptions.additional_subscriptions.trainer.employee')->findOrFail($id);
//        dd($one_data);
        return view('dashbord.admin.members.details-new', compact('one_data'));

    }

    public function show_old(string $id, $type = null)
    {

        $data['goals'] = SubscriptionSettings_M::where('ttype', 'goals')->get();
        $data['health_status'] = SubscriptionSettings_M::where('ttype', 'health_status')->get();
        $one_data = Members::with('goals')->findOrFail($id);
        //dd($one_data->goals);
        $one_data = new MembersResource($one_data);
        $data['one_data'] = $this->prepare_data($one_data->edite_data($one_data));
        $data['inbody_data'] = MembersInbody::where('member_id', $id)->get();
        // Fetch the subscriptions for the member
        $data['subscriptions'] = MembersSubscriptions::where('member_id', $id)->get();

        $data['transportation'] = Transportation_M::all();


        $type_view = $type ? $type : 'inbody';

        return view($this->view_root . $type_view, $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['goals'] = SubscriptionSettings_M::where('ttype', 'goals')->get();
        $data['health_status'] = SubscriptionSettings_M::where('ttype', 'health_status')->get();
        $one_data = Members::with('goals')->findOrFail($id);
        $data['transportation'] = Transportation_M::all();
        $data['trainers'] = Trainers::all();
        $one_data = new MembersResource($one_data);
        $data['one_data'] = $this->prepare_data($one_data->edite_data($one_data));

        return view('dashbord.admin.members.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {

            //dd($request);
            $member = Members::whereId($id)->first();
            $insert_data = $request->except('proengsoft_jsvalidation', 'goal_id');
            $insert_data['publisher_id'] = auth()->user()->id;
            $insert_data['publisher_name'] = auth()->user()->name;
            if ($request->hasFile('member_image')) {
                $file = $request->file('member_image');

                $dataX = $this->saveImage($file, $this->upload_folder);
                $insert_data['member_image'] = $dataX;
            }
            MembersGoals::where('member_id', $id)->delete();
            $member->update($insert_data);
            $goals = $request->goal_id;
            foreach ($goals as $goal) {
                $data['goal_id'] = $goal;
                $data['member_id'] = $id;
                MembersGoals::create($data);
            }
            DB::commit();

            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.Members.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
//            DB::enableQueryLog();  /* before sql **/


            $delete_data = Members::with('goals', 'members_subscriptions')->find($id);
            $this->deleteImage($delete_data->member_image);
            MembersGoals::where('member_id',$id)->delete();
            MembersInbody::where('member_id',$id)->delete();
            MembersSubscriptions::where('member_id',$id)->delete();
//            $delete_data->goals()->delete();
//            $delete_data->inbody()->delete();
//            $delete_data->members_subscriptions()->delete();
            $delete_data->delete();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.Members.index');

//            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } catch (\Exception $e) {
//            return response()->json(['message' => $e->getMessage()], 500);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);

        }
    }

    /*********************************************/
    public function inbody($id)
    {

        return $this->show($id, 'inbody');
    }

    /*********************************************/
    public function add_inbody(SaveInbodyRequest $request)
    {
        try {
            $data['member_id'] = $request->member_id;
            $data['height'] = $request->height;
            $data['weight'] = $request->weight;
            $data['fat_percentage'] = $request->fat_percentage;
            $data['muscle_mass_percentage'] = $request->muscle_mass_percentage;
            $data['body_status'] = $request->body_status;
            $data['date'] = $request->date;

            MembersInbody::create($data);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.inbody', $request->member_id);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**************************************************/
    public function update_inbody(SaveInbodyRequest $request, $id)
    {
        try {
            $member_inbody = MembersInbody::find($id);
            $data['member_id'] = $request->member_id;
            $data['height'] = $request->height;
            $data['weight'] = $request->weight;
            $data['fat_percentage'] = $request->fat_percentage;
            $data['muscle_mass_percentage'] = $request->muscle_mass_percentage;
            $data['body_status'] = $request->body_status;
            $data['date'] = $request->date;

            $member_inbody->update($data);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.inbody', $request->member_id);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /************************************************************/

    public function delete_inbody(Request $request, $id)
    {
        try {
            $member_inbody = MembersInbody::find($id);
            $member_id = $member_inbody->member_id;

            $member_inbody->delete();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.inbody', $member_id);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**********************************************/
    public function subscriptions($id)
    {
        return $this->show($id, 'main_subscription');
    }

    /**********************************************/
    public function get_subscription(Request $request)
    {
        $type = $request->type;
        $subscription = [];
        if ($type == 'main') {
            $subscription = MainSubscription_M::all();
        } elseif ($type = 'special') {

            $subscription = SpecialSubscription_M::all();
        }

        // dd($subscription);
        return response()->json($subscription);
    }

    /************************************************/
    public function add_subscriptions(Request $request)
    {
        try {
            $data['member_id'] = $request->member_id;
            $data['type'] = $request->type;
            $data['subscription_id'] = $request->subscription_id;
            $supscription = [];
            if ($data['type'] == "main") {
                $supscription = MainSubscription_M::where('id', $data['subscription_id'])->first();
            } elseif ($data['type'] == "special") {
                $supscription = SpecialSubscription_M::where('id', $data['subscription_id'])->first();
            }

            $duration = $supscription->duration;

            $startDate = new DateTime($request->start_date);
            $endDate = clone $startDate;
            $endDate->modify("+{$duration} months");
            $addedDate = new DateTime();
            $data['start_date'] = $startDate->format('Y-m-d');
            $data['end_date'] = $endDate->format('Y-m-d');
            $data['added_date'] = $addedDate->format('Y-m-d');
            $data['transport_id'] = $request->transportation_id;

            MembersSubscriptions::create($data);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.member_subscriptions', $request->member_id);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /***************************************************/
    public function update_subscriptions(Request $request, $id)
    {
        try {
            $member_subscription = MembersSubscriptions::find($id);
            $data['member_id'] = $request->member_id;
            $data['type'] = $request->type;
            $data['subscription_id'] = $request->subscription_id;
            $supscription = [];
            if ($data['type'] == "main") {
                $supscription = MainSubscription_M::where('id', $data['subscription_id'])->first();
            } elseif ($data['type'] == "special") {
                $supscription = SpecialSubscription_M::where('id', $data['subscription_id'])->first();
            }

            $duration = $supscription->duration;


            $startDate = new DateTime($request->start_date);
            $endDate = clone $startDate;
            $endDate->modify("+{$duration} months");
            $addedDate = new DateTime();
            $data['start_date'] = $startDate->format('Y-m-d');
            $data['end_date'] = $endDate->format('Y-m-d');
            $data['added_date'] = $addedDate->format('Y-m-d');
            $data['transport_id'] = $request->transportation_id;

            $member_subscription->update($data);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.member_subscriptions', $request->member_id);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /***************************************************/
    public function delete_subscriptions(Request $request, $id)
    {
        try {
            $member_subscription = MembersSubscriptions::find($id);
            $member_id = $member_subscription->member_id;

            $member_subscription->delete();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.member_subscriptions', $member_id);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /***************************************************/
    public function print_subscriptions($id)
    {
        $data['all_data'] = MembersSubscriptions::find($id);
        // dd($data['all_data']->member);
        return view('dashbord.admin.members.print_subscription', $data);

    }

    /**************************************************/
    public function get_subscription_details(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $start_date = $request->start_date;
        $subscription = [];
        //dd($id);
        if ($type == 'main') {
            $subscription = MainSubscription_M::find($id);
        } elseif ($type == 'special') {
            $subscription = SpecialSubscription_M::find($id);
        }
        $duration = $subscription->duration;


        $startDate = new DateTime($start_date);
        $endDate = clone $startDate;
        $endDate->modify("+{$duration} months");
        $endDate->modify("-1 day"); // Subtract 1 day

        $end_date = $endDate->format('Y-m-d');

        return response()->json([
            'subscription' => $subscription,
            'end_date' => $end_date,
            'transport_price' => ($subscription->duration * getMainData()->transport_value),
        ]);


    }


}
