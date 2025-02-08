<?php
namespace App\Http\Controllers\Admin\Hr;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Hr\operation\PermissionRequest;
use App\Models\hr\operation\Permission;
use App\Models\hr\Setting\MainSetting;
use App\Models\hr\employee\Employee;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $permission = Permission::all();
        if ($request->ajax()) {
            $permission = Permission::select('*');
            return Datatables::of($permission)
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
                         <a href="' . route('admin.hr.hr_permission.edit', $row->id) . '"
                           title="' . trans('forms.edite_btn') . '" class="menu-link px-3"
                           >' . trans('forms.edite_btn') . '</a>
                    </div>
                    <div class="menu-item px-3">
                    <a href="javascript:void(0)" data-kt-table-details="details_row" data-url="'
                        . route('admin.hr.hr_permission.load_details', $row->id) . '"
                               name="' . trans('forms.modal') . '" class="menu-link px-3"
                             data-bs-toggle="modal" data-bs-target="#kt_modal_1"  >' . trans('forms.modal') . '</a>
            </div>
                    <div class="menu-item px-3">
                    <a href= "' . route('admin.hr.hr_permission.show', $row->id) . '"
                               name="' . trans('forms.details') . '" class="menu-link px-3"
                              >' . trans('forms.details') . '</a>
            </div>
                    <div class="menu-item px-3">
                            <a href="' . route('admin.hr.hr_permission.destroy', $row->id) . '" data-kt-table-delete="delete_row"
                                       title="' . trans('forms.delete_btn') . '" class="menu-link px-3"
                                       >' . trans('forms.delete_btn') . '</a>
                    </div>
              </div>



               </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashbord.admin.hr.permission.index', compact('permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        return view('dashbord.admin.hr.permission.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PermissionRequest $request)
    {
        try {
            $permission = new Permission();
            $permission->emp_id = $request->emp_id;
            //$permission->end_permission = strtotime($request->end_permission);
            $permission->start_permission = $request->start_permission;
            $permission->end_permission = $request->end_permission;    //time
            $permission->date_permission = $request->date_permission;      //date
            $permission->date_permission_int = strtotime($request->date_permission);
            $permission->period = $request->period;
            $permission->reason = $request->reason;
            $permission->year = Date('Y', strtotime($request->date_permission));
            $permission->month = Date('n', strtotime($request->date_permission));
            $permission->save();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.hr.hr_permission.index');

<<<<<<< HEAD
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
=======
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(PermissionRequest $request)
  {
    try{
   $permission=new Permission();
   $permission->emp_id = $request->emp_id;
   $permission->start_permission = $request->start_permission;
    $permission->end_permission = $request->end_permission;    //time
   $permission->date_permission = $request->date_permission;      //date
   $permission->date_permission_int =strtotime($request->date_permission);
   $permission->period = $request->period;
   $permission->reason = $request->reason;
   $permission->year =  Date('Y', strtotime($request->date_permission));
   $permission->month = Date('n', strtotime ($request->date_permission));
   $permission->save();
   toastr()->addSuccess(trans('forms.success'));
   return redirect()->route('admin.hr.hr_permission.index');

} catch (\Exception $e) {
   return redirect()->back()->withErrors(['error' => $e->getMessage()]);
}
>>>>>>> b9d7dd9656453caa468658901b3eaff8e8c8ccd6


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        //$permission = Permission::get('emp_id','date_permission','start_permission','end_permission');
        //  $permission = Permission::select('*')->where('id',$id)->first();
        //$permission=$this->prepare_data($permission);
        // dd($permission);
        return view('dashbord.admin.hr.permission.details', compact('permission'));

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show_load($id)
    {
        $data['permission'] = Permission::findOrFail($id);
        // $permission = Permission::select('*');
        // $permission=$this->prepare_data($permission);
        return view('dashbord.admin.hr.permission.load_details', $data);

    }

<<<<<<< HEAD
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(Permission $permission, $id)
    {
        $permission = Permission::find($id);
        return view('dashbord.admin.hr.permission.edit')->with('permission', $permission);
=======
  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show_load($id)
  {
    $permission = Permission::findOrFail($id);
    // $permission = Permission::select('*');
    // $permission=$this->prepare_data($permission);
    return view('dashbord.admin.hr.permission.load_details',compact('permission'));
>>>>>>> b9d7dd9656453caa468658901b3eaff8e8c8ccd6

    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(PermissionRequest $request, $id)
    {
        try {

            $permission_id = $id;
            $permission = Permission::findorfail($permission_id);
            $permission->emp_id = $request->emp_id;
            $permission->start_permission = $request->start_permission;   //time
            $permission->end_permission = $request->end_permission;       //time
            $permission->date_permission = $request->date_permission;      //date
            $permission->date_permission_int = strtotime($request->date_permission);
            $permission->period = $request->period;
            $permission->reason = $request->reason;
            $permission->year = Date('Y', strtotime($request->date_permission));
            $permission->month = Date('n', strtotime($request->date_permission));
            $permission->update();
            toastr()->addSuccess(trans('forms.Update'));

            return redirect()->route('admin.hr.hr_permission.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::find($id);
            Permission::destroy($id);
            toastr()->addSuccess(trans('forms.Delete'));
            return response()->json(['message' => 'deleted successfully.'], 200);
            return redirect()->route('admin.hr.hr_permission.index');

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

?>
