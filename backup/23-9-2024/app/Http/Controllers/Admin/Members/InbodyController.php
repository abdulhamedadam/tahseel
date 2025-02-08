<?php

namespace App\Http\Controllers\Admin\Members;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Members\SaveInbodyRequest;
use App\Models\Members;
use App\Models\MembersInbody;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InbodyController extends Controller
{
    private $view_root='dashbord.admin.members.inbody.';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $allData = MembersInbody::with('member')->select('*');

            return DataTables::of($allData)
                ->editColumn('id', function () use (&$counter) {
                    $counter++;
                    return $counter;
                }) ->editColumn('member_name', function ($row){
                    return optional($row->member)->member_name;
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
                        <a href="' . route('admin.Members-Inbody.edit', $row->id) . '"
                           title="' . trans('forms.edit_btn') . '" class="menu-link px-3"
                        >' . trans('forms.edit_btn') . '</a>
                    </div>
                    <div class="menu-item px-3">
                        <a data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="show_details('.$row->id.')"
                           title="' . trans('forms.details') . '" class="menu-link px-3"
                        >' . trans('forms.details') . '</a>
                    </div>
                    <div class="menu-item px-3">
                        <a href="' . route('admin.Members-Inbody.destroy', $row->id) . '"  data-kt-table-delete="delete_row"  title="' . trans('forms.delete_btn') . '" class="menu-link px-3">' . trans('forms.delete_btn') . '</a>

                    </div>
                </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashbord.admin.members.inbody.index');
    }
    /***********************************************************/
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['members']=Members::all();
        return view('dashbord.admin.members.inbody.form',$data);
    }

    /****************************************************/
    public function store(SaveInbodyRequest $request)
    {


        try {
            $data['member_id']=$request->member_id;
            $data['height']=$request->height;
            $data['weight']=$request->weight;
            $data['fat_percentage']=$request->fat_percentage;
            $data['muscle_mass_percentage']=$request->muscle_mass_percentage;
            $data['body_status']=$request->body_status;
            $data['date']=$request->date;

            MembersInbody::create($data);

            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.Members-Inbody.index');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /****************************************************/
    public function show(Request $request)
    {
        $id=$request->id;
        $data['one_data'] = MembersInbody::find($id);

    //    dd($data['one_data']);
        return view('dashbord.admin.members.inbody.show',$data);
    }

    /****************************************************/
    public function edit(string $id)
    {
        $data['one_data'] = MembersInbody::find($id);
        $data['members']=Members::all();
        return view('dashbord.admin.members.inbody.edit',$data);
    }

    /****************************************************/
    public function update(SaveInbodyRequest $request, string $id)
    {

        try {

            $member_inbody=MembersInbody::find($id);
            $data['member_id']=$request->member_id;
            $data['height']=$request->height;
            $data['weight']=$request->weight;
            $data['fat_percentage']=$request->fat_percentage;
            $data['muscle_mass_percentage']=$request->muscle_mass_percentage;
            $data['body_status']=$request->body_status;
            $data['date']=$request->date;

            $member_inbody->update($data);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.Members-Inbody.index');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /****************************************************/

    public function destroy(string $id)
    {
        try {
            $member_inbody = MembersInbody::find($id);
            $member_inbody->delete();
            toastr()->addSuccess(trans('forms.success'));
            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}
