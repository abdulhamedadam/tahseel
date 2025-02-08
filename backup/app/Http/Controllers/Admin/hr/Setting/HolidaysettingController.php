<?php

namespace App\Http\Controllers\Admin\hr\Setting;

use App\Models\hr\Setting\HolidaySetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HolidaysettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holiday_setting=HolidaySetting::get();
        return view('dashbord.admin.hr.setting.holidaysetting.create',compact('holiday_setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $holiday=new HolidaySetting();
            $holiday->title= ['ar' => $request->title_ar, 'en' => $request->title_en];
            $holiday->num_days=$request->num_days;
            $holiday->status=$request->status;
            $holiday->save();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.hr.holiday_type.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HolidaySetting $hr_holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HolidaySetting $hr_holiday)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            $holiday_id=$id;
            $holiday=HolidaySetting::findorfail($holiday_id);
            $holiday->title= ['ar' => $request->title_ar, 'en' => $request->title_en];
            $holiday->num_days=$request->num_days;
            $holiday->status=$request->status;
            $holiday->update();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.hr.holiday_type.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(HolidaySetting $hr_holiday)
    {
        try {
            HolidaySetting::destroy($id);
            toastr()->addSuccess(trans('forms.Delete'));

            return redirect()->route('admin.hr.holiday_type.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
