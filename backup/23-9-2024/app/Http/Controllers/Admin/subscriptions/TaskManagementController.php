<?php

namespace App\Http\Controllers\Admin\subscriptions;

use App\Models\subscriptions\Task_Management;
use Illuminate\Http\Request;
use App\Http\Requests\Subscriptions\Task_management\StoreRequest;
use App\Http\Requests\Subscriptions\Task_management\UpdateRequest;
use App\Models\hr\employee\Employee;
use App\Http\Controllers\Controller;

class TaskManagementController extends Controller
{
    public function index(Request $request)
    {

    if ($request->ajax()) {
        $allData = Task_Management::select('*');
        return Datatables::of($allData)

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
                         <a href="' . route('admin.subscriptions.task_management.edit', $row->id) . '"
                           name="' . trans('forms.edite_btn') . '" class="menu-link px-3"
                           >' . trans('forms.edite_btn') . '</a>
                    </div>

                    <div class="menu-item px-3">
                            <a href="javascript:void(0)" data-kt-table-details="details_row" data-url="' . route('admin.subscriptions.task_management.load_details', $row->id) . '"
                                       name="' . trans('forms.details') . '" class="menu-link px-3"
                                     data-bs-toggle="modal" data-bs-target="#kt_modal_1"  >' . trans('forms.details') . '</a>
                    </div>
                    <div class="menu-item px-3">
                            <a href="' . route('admin.subscriptions.task_management.destroy', $row->id) . '" data-kt-table-delete="delete_row"
                                       name="' . trans('forms.delete_btn') . '" class="menu-link px-3"
                                       >' . trans('forms.delete_btn') . '</a>
                    </div>
              </div>
               </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    return view('dashbord.admin.subscriptions.task_management.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['one_data'] = new Task_Management();
        return view ('dashbord.admin.subscriptions.task_management.create',$data);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {

            $insert_data = $request->all();
            $insert_data['title']=['ar'=>$request->title_ar,'en'=>$request->title_en];
            $insert_data['details']=['ar'=>$request->details_ar,'en'=>$request->details_en];
            $inserted_data = Task_Management::create($insert_data);
            $insert_id = $inserted_data->id;
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.subscriptions.task_management.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task_Management $task_Management)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['one_data'] =Task_Management::findOrFail($id);

        return view ('dashbord.admin.subscriptions.task_management.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request,$id)
    {
        try{
              $data = Task_Management::find($request->id);
              $update_data = $request->all();
              $insert_data['title']=['ar'=>$request->title_ar,'en'=>$request->title_en];
              $insert_data['details']=['ar'=>$request->details_ar,'en'=>$request->details_en];
              $data->update($update_data);
              toastr()->addSuccess(trans('forms.success'));
              return redirect()->route('admin.subscriptions.task_management.index');
            } catch (\Exception $e) {
               return redirect()->back()->withErrors(['error' => $e->getMessage()]);
           }
    }


    public function destroy($id)
    {
        try {
            Task_Management::destroy($id);
            toastr()->addSuccess(trans('forms.Delete'));

            return response()->json(['message' => 'deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
