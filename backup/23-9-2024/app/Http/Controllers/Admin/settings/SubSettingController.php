<?php

namespace App\Http\Controllers\Admin\settings;
use App\Http\Controllers\Controller;
use App\Models\setting\main_setting;
use Illuminate\Http\Request;
use DataTables;

class SubSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subeco=main_setting::all();
        if ($request->ajax()) {
            $subeco = main_setting::select('id', 'image','main', 'name', 'description');
            return Datatables::of($subeco)
                ->addColumn('image', function ($row) {
                    return '<a class="px-3" >
            <img class="w-50px h-50px rounded-1 ms-2" src="' . asset($row->image ? $row->image : 'assets/media/avatars/blank.png') . '" alt=""/>
        </a>';
                })
                 ->addColumn('action', function($row){
                    return'<a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"> '.trans('viewdata.Action').'
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

                       <div class="menu-item px-2">
                           <a href="#"
                           title="OverView" class="menu-link px-2">'.trans('viewdata.OverView').'</a>
                       </div>

                       <div class="menu-item px-2">
                           <a href="#"
                           title="Delete" class="menu-link px-3" data-kt-ecommerce-category-filter="delete_row">'.trans('viewdata.Delete').'</a>
                       </div>

                   </div>';
                 })

                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        return view('dashbord.setting.sub_setting.sub_view') ;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashbord.setting.sub_setting.sub_form') ;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $subeco=new sub_setting();
        $subeco['name'] = ['ar' => $request->name_ar, 'en' => $request->name_en];
        $subeco['description'] = ['ar' => $request->description_ar, 'en' => $request->description_en];
        $img=$request->file('image')->getClientOriginalName();
        $path=$request->file('image')->storeAs('imgs',$img,'public');
        $im->image=$path;
        $im->save();
        $subeco['main_category'] = $request->main_category;


    return redirect()->route('admin.subsetting.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(sub_setting $sub_setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sub_setting $sub_setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sub_setting $sub_setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sub_setting $sub_setting)
    {
        //
    }
}
