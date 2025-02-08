<?php

namespace App\Http\Controllers\Admin\subscriptions;

use Illuminate\Http\Request;
use App\Http\Requests\Subscriptions\Devices\StoreRequest;
use App\Http\Requests\Subscriptions\Devices\UpdateRequest;
use App\Models\subscriptions\Devices_M;
use App\Models\subscriptions\Exercises_M;
use App\Http\Controllers\Controller;
//use App\Http\Resources\subscriptions\DevicesResource;

use Yajra\DataTables\DataTables;

class Devices_C extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  protected $upload_folder = 'Site/Subscription/Devices';

  public function index(Request $request)
  {
      
    if ($request->ajax()) {
      $allData = Devices_M::select('*');
      return Datatables::of($allData)
          ->editColumn('exercise_type', function ($row) {    
             
                return $row->exercise_type_rel->exercise_type;
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
                       <a href="' . route('admin.subscriptions.devices.edit', $row->id) . '"
                         name="' . trans('forms.edite_btn') . '" class="menu-link px-3"
                         >' . trans('forms.edite_btn') . '</a>
                  </div>
               
                  <div class="menu-item px-3">
                          <a href="javascript:void(0)" data-kt-table-details="details_row" data-url="' . route('admin.subscriptions.devices.load_details', $row->id) . '"
                                     name="' . trans('forms.details') . '" class="menu-link px-3"
                                   data-bs-toggle="modal" data-bs-target="#kt_modal_1"  >' . trans('forms.details') . '</a>
                  </div>
                  <div class="menu-item px-3">
                          <a href="' . route('admin.subscriptions.devices.destroy', $row->id) . '" data-kt-table-delete="delete_row"
                                     name="' . trans('forms.delete_btn') . '" class="menu-link px-3"
                                     >' . trans('forms.delete_btn') . '</a>
                  </div>
            </div>



             </div>';
          })
          ->rawColumns(['action'])
          ->make(true);
  }

  return view('dashbord.admin.subscriptions.devices.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
   // $data['exercise'] = Exercises_M::with('exercise_type'); //select all from sub_settings where type=exercise_type
   $data['exercise']=Exercises_M::all();
   $data['one_data'] = new Devices_M(); 
    return view ('dashbord.admin.subscriptions.devices.create',$data);
  
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(StoreRequest $request)
  {
    try {

      $insert_data = $request->all();
      if ($request->hasFile('image')) {
          $file = $request->file('image');

          $dataX = $this->saveImage($file, $this->upload_folder);
          $insert_data['image'] = $dataX;
      }
      $inserted_data = Devices_M::create($insert_data);
      $insert_id = $inserted_data->id;
      toastr()->addSuccess(trans('forms.success'));
      return redirect()->route('admin.subscriptions.devices.index');
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
      $data['one_data'] = Devices_M::findOrFail($id);
     // $one_data = new DevicesResource($one_data);
      //$data['one_data'] = $this->prepare_data($one_data);
      return view('dashbord.admin.subscriptions.devices.load_details', $data);

    }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $data['one_data'] = Devices_M::findOrFail($id);
    $data['exercise']=Exercises_M::all();
    return view('dashbord.admin.subscriptions.devices.edit', $data);

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(UpdateRequest $request,$id)
  {
    try {
     // $data_id=$id;
      $data = Devices_M::find($request->id);
      $update_data = $request->all();
      if ($request->hasFile('image')) {
          $file = $request->file('image');
          $dataX = $this->saveImage($file, $this->upload_folder);
          $update_data['image'] = $dataX;
      }
      $data->update($update_data);
      toastr()->addSuccess(trans('forms.success'));
      return redirect()->route('admin.subscriptions.devices.index');
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

      $delete_data = Devices_M::find($id);
      Devices_M::destroy($id);
      $this->deleteImage($delete_data->image);
      toastr()->error(trans('forms.Delete'));
      return response()->json(['message' => 'Image deleted successfully.'], 200);
  } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);

  }
  }

}

?>
