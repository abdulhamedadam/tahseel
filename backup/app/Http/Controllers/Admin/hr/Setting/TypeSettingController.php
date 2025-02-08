<?php

namespace App\Http\Controllers\Admin\hr\Setting;
//use DataTables;
use App\Models\hr\Setting\TypeSetting;
use App\Http\Requests\hr\setting\type_settingRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obj=TypeSetting::all();
        return view('dashbord.admin.hr.setting.typesetting.create',compact('obj'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(type_settingRequest $request)
    {
         try {
            // $request->validated();
            $obj=new TypeSetting();
            $obj->title= ['ar' => $request->name_ar, 'en' => $request->name_en];
            $obj->code=$request->code;
            $obj->save();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.hr.typesetting.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Hr_Mainsetting $Hr_Mainsetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hr_Mainsetting $Hr_Mainsetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $obj_id =$id;
            $obj=TypeSetting::findorfail($obj_id);
            $obj->title = ['ar' => $request->name_ar, 'en' => $request->name_en];
            $obj->code=$request->code;
            $obj->update();
            toastr()->addSuccess(trans('forms.Update'));
            return redirect()->route('admin.hr.typesetting.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
         try {
            // TypeSetting::destroy($id);

            $delete_data = TypeSetting::with('maindata')->find($id);
            $delete_data->maindata()->delete();
            $delete_data->delete();
            toastr()->addSuccess(trans('forms.Delete'));

            return redirect()->route('admin.hr.typesetting.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
