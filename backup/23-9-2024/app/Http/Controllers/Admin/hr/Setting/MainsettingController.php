<?php

namespace App\Http\Controllers\Admin\hr\Setting;

use App\Http\Requests\hr\setting\mainsettinRequest;
use App\Models\hr\setting\MainSetting;
use App\Models\hr\setting\TypeSetting;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class MainsettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $main = MainSetting::with('typedata')->get();
        // dd($main);
        $type = TypeSetting::get();

        return view('dashbord.admin.hr.setting.mainsetting.create', compact('main', 'type'));
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
    public function store(mainsettinRequest $request)
    {
        try {
            $main = new MainSetting();
            $main->title = ['ar' => $request->title_ar, 'en' => $request->title_en];
            $type_id_fk = TypeSetting::where('code', $request->type_code)->first()->id;
            $main->type_id_fk = $type_id_fk;
            $main->type_code = $request->type_code;
            $main->status = $request->status;
            $main->save();
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.hr.mainsetting.index');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Mainsetting $mainsetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mainsetting $mainsetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(mainsettinRequest $request, $id)
    {
        try {
            $main_id = $id;
            $main = MainSetting::findorfail($main_id);
            $main->title = ['ar' => $request->title_ar, 'en' => $request->title_en];
            $type_id_fk = TypeSetting::where('code', $request->type_code)->first()->id;
            $main->type_id_fk = $type_id_fk;
            $main->type_code = $request->type_code;
            $main->status = $request->status;
            $main->update();
            toastr()->addSuccess(trans('forms.Update'));
            return redirect()->route('admin.hr.mainsetting.index');
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
            MainSetting::destroy($id);
            toastr()->addSuccess(trans('forms.Delete'));

            return redirect()->route('admin.hr.mainsetting.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
