<?php

namespace App\Http\Controllers\Admin\hr;

use App\Http\Controllers\Controller;
use App\Models\hr\employee\Employee;
use App\Models\hr\operation\Holidays;
use App\Models\hr\setting\HolidaySetting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HolidaysController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /* $hr_holiday=Holidays::all();
         return view('dashbord.admin.hr.requestholiday.create',compact('hr_holiday')); */
        $hr_holiday = Holidays::all();
        $type = HolidaySetting::get();

        if ($request->ajax()) {
            $hr_holiday = Holidays::select('*');
            return Datatables::of($hr_holiday)
                ->editColumn('title', function ($row) {
                    return $row->title;
                }) ->addColumn('typeholiday', function ($row) {
                    return $row->typeholiday->title;
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
                             <a href="' . route('admin.hr.reqholiday.edit', $row->id) . '"
                               title="' . trans('forms.edite_btn') . '" class="menu-link px-3"
                               >' . trans('forms.edite_btn') . '</a>
                        </div>
                   		<div class="menu-item px-3">
                                <a href="' . route('admin.hr.reqholiday.show', $row->id) . '"
                                           title="' . trans('forms.details') . '" class="menu-link px-3"
                                           >' . trans('forms.details') . '</a>
                        </div>
                        <div class="menu-item px-3">
                                <a href="' . route('admin.hr.reqholiday.destroy', $row->id) . '" data-kt-table-delete="delete_row"
                                           title="' . trans('forms.delete_btn') . '" class="menu-link px-3"
                                           >' . trans('forms.delete_btn') . '</a>
                        </div>
                  </div>



                   </div>';
                })
                ->rawColumns(['action','typeholiday'])
                ->make(true);
        }

        return view('dashbord.admin.hr.requestholiday.index', compact('hr_holiday', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['type_holiday'] = HolidaySetting::where('status', 'active')->get();
        return view('dashbord.admin.hr.requestholiday.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*emp_id','type_holiday','date_start'
        ,'date_start_int','date_end','date_end_int'
        ,'month','reason','year','status'  **/

        try {

            $hr_holiday = new Holidays();
            $hr_holiday->emp_id = $request->emp_id;
            $hr_holiday->type_holiday_fk = $request->type_holiday_fk;
            $hr_holiday->date_start = $request->date_start;
            $hr_holiday->date_end = $request->date_end;
            $hr_holiday->num_days = $request->num_days;
            $hr_holiday->date_end_int = strtotime($request->date_end);
            $hr_holiday->date_start_int = strtotime($request->date_start);
            $hr_holiday->month = Date('n', strtotime($request->date_start));
            $hr_holiday->year = Date('Y', strtotime($request->date_start));
            $hr_holiday->reason = $request->reason;
            $hr_holiday->save();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.hr.reqholiday.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holidays $hr_re_holiday, $id)
    {
        $data['type_holiday'] = HolidaySetting::where('status', 'active')->get();

        $data['hr_holiday'] = Holidays::find($id);
        //return view('dashbord.admin.hr.requestholiday.edit',compact('hr_holiday'));
        return view('dashbord.admin.hr.requestholiday.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $hr_holiday_id = $id;
            $hr_holiday = Holidays::findorfail($hr_holiday_id);
            $hr_holiday->emp_id = $request->emp_id;
            $hr_holiday->type_holiday_fk = $request->type_holiday_fk;
            $hr_holiday->date_start = $request->date_start;
            $hr_holiday->date_end = $request->date_end;
            $hr_holiday->num_days = $request->num_days;
            $hr_holiday->month = Date('n', strtotime($request->date_start));
            $hr_holiday->year = Date('Y', strtotime($request->date_start));
            $hr_holiday->reason = $request->reason;
            $hr_holiday->update();


            toastr()->addSuccess(trans('forms.Update'));
            return redirect()->route('admin.hr.reqholiday.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Holidays::destroy($id);
            toastr()->addSuccess(trans('forms.Delete'));

            /* return redirect()->route('admin.hr.reqholiday.index');
         } catch (\Exception $e) {
             return redirect()->back()->withErrors(['error' => $e->getMessage()]);
         } */

            return response()->json(['message' => 'deleted successfully.'], 200);
        } catch (\Exception $e) {
            /*            return redirect()->back()->withErrors(['error' => $e->getMessage()]);*/
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

?>
