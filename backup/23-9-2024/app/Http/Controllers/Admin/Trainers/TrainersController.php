<?php

namespace App\Http\Controllers\Admin\Trainers;

use App\Http\Controllers\Controller;
use App\Models\hr\employee\Employee;
use App\Models\Members;
use App\Models\Trainers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class TrainersController extends Controller
{
   protected $view='dashbord.admin.trainers.';
    private $upload_folder = 'trainers';
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $allData = Trainers::select('*');
            return DataTables::of($allData)
                ->editColumn('trainer_name', function ($row) {
                    return $row->employee->name;
                }) ->editColumn('password', function ($row) {
                    return $row->real_password;
                })
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
                        <a href="' . route('admin.Trainers.edit', $row->id) . '"
                           title="' . trans('forms.edit_btn') . '" class="menu-link px-3"
                        >' . trans('forms.edit_btn') . '</a>
                    </div>

                    <div class="menu-item px-3">
                        <a href="javascript:void(0)" onclick="if(confirm(\'are you sure?\')){document.getElementById(\'delete-' . $row->id . '\').submit(); }else{ return false;}" title="' . trans('forms.delete_btn') . '" class="menu-link px-3">' . trans('forms.delete_btn') . '</a>
                        <form action="' . route('admin.Trainers.destroy', $row->id) . '" method="post" id="delete-' . $row->id . '" style="display:none">
                             ' . csrf_field() . '
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                        </form>
                    </div>
                </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view($this->view.'index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['employees']=Employee::all();
        return view($this->view.'create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $insert_data = $request->except('proengsoft_jsvalidation');
            $insert_data['password'] = bcrypt($request->password);
            $insert_data['real_password'] = ($request->password);

            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');

                $dataX = $this->saveImage($file, $this->upload_folder);
                $insert_data['user_image'] = $dataX;
            }

          //  dd($insert_data);
            $inserted_data = Trainers::create($insert_data);

            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.Trainers.index');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['employees']=Employee::all();
        $data['one_data']=Trainers::findOrFail($id);
        return view($this->view.'edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $trainer=Trainers::find($id);

            $insert_data = $request->except('proengsoft_jsvalidation');
            $insert_data['password'] = bcrypt($request->password);
            $insert_data['real_password'] = ($request->password);

            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');

                $dataX = $this->saveImage($file, $this->upload_folder);
                $insert_data['user_image'] = $dataX;
            }
            $trainer->update($insert_data);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.Trainers.index');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        try {
            $trainer=Trainers::find($id);

            $trainer->delete();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.Trainers.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /*****************************************************/
    public function get_trainer_details(Request $request)
    {
        $id=$request->id;
        //dd($id);
        $memmer_data=Employee::find($id);
        return response()->json([
            'record' => $memmer_data,
        ]);
    }
}
