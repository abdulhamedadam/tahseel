<?php

namespace App\Http\Controllers\Admin\subscriptions;
use Illuminate\Http\Request;
use App\Http\Requests\Subscriptions\Devices_exercises\StoreRequest;
use App\Http\Requests\Subscriptions\Devices_exercises\UpdateRequest;
use App\Models\subscriptions\DeviceExercises_M;
use App\Models\subscriptions\Devices_M;
use App\Http\Controllers\Controller;
use App\Http\Resources\subscriptions;
use Yajra\DataTables\DataTables;
class DeviceExercises_C extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {

    if ($request->ajax()) {
      $allData = DeviceExercises_M::select('*');
      return Datatables::of($allData)
          ->editColumn('device_code', function ($row) {
          return optional($row->device_code_data)->code; // return $row->device_code_data->code;

        })
          ->addColumn('action', function ($row) {
              return '<a href="#" class="btn btn-sm btn-light btn-active-light-primary"
             data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"> ' . trans('forms.action') . '
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
                       <a href="' . route('admin.subscriptions.exercise_devices.edit', $row->id) . '"
                         name="' . trans('forms.edite_btn') . '" class="menu-link px-3"
                         >' . trans('forms.edite_btn') . '</a>
                  </div>

                  <div class="menu-item px-3">
                          <a href="javascript:void(0)" data-kt-table-details="details_row" data-url="' . route('admin.subscriptions.exercise_devices.load_details', $row->id) . '"
                                     name="' . trans('forms.details') . '" class="menu-link px-3"
                                   data-bs-toggle="modal" data-bs-target="#kt_modal_1"  >' . trans('forms.details') . '</a>
                  </div>
                  <div class="menu-item px-3">
                          <a href="' . route('admin.subscriptions.exercise_devices.destroy', $row->id) . '" data-kt-table-delete="delete_row"
                                     name="' . trans('forms.delete_btn') . '" class="menu-link px-3"
                                     >' . trans('forms.delete_btn') . '</a>
                  </div>
            </div>



             </div>';
          })
          ->rawColumns(['action'])
          ->make(true);
  }

  return view('dashbord.admin.subscriptions.devices_exercises.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {

    $data['codes'] = Devices_M::all();//select all from sub_settings where type=exercise_type
    $data['one_data'] = new DeviceExercises_M();
    //dd('data');
    return view ('dashbord.admin.subscriptions.devices_exercises.create',$data);

  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    try{
      $insert_data = $request->all();
      $insert_data['link_id']=extractVideoId($request->link);
      $inserted_data = DeviceExercises_M::create($insert_data);
      $insert_id = $inserted_data->id;
      toastr()->addSuccess(trans('forms.success'));
      return redirect()->route('admin.subscriptions.exercise_devices.index');
    } catch (\Exception $e) {
       return redirect()->back()->withErrors(['error' => $e->getMessage()]);
   }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {

  }

  public function show_load($id)
  {
    //  $one_data = Devices_M::findOrFail($id);
     // $one_data = new DevicesResource($one_data);
    //  $data['one_data'] = $this->prepare_data($one_data);
    $data['one_data'] = DeviceExercises_M::findOrFail($id);

      return view('dashbord.admin.subscriptions.devices_exercises.load_details', $data);
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $data['one_data'] = DeviceExercises_M::findOrFail($id);
    $data['codes'] = Devices_M::all();//select all from sub_settings where type=exercise_type
    return view('dashbord.admin.subscriptions.devices_exercises.edit',$data);

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request,$id)
  {
    try{
    //  $data_id=$id;
      $data = DeviceExercises_M::find($request->id);
      $update_data = $request->all();
      $data->update($update_data);
      toastr()->addSuccess(trans('forms.success'));
      return redirect()->route('admin.subscriptions.exercise_devices.index');
    } catch (\Exception $e) {
       return redirect()->back()->withErrors(['error' => $e->getMessage()]);
   }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    try {

      $delete_data = DeviceExercises_M::find($id);
      DeviceExercises_M::destroy($id);
      toastr()->error(trans('forms.Delete'));
      return response()->json(['message' => 'deleted successfully.'], 200);
  } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);

  }
  }

}

?>
