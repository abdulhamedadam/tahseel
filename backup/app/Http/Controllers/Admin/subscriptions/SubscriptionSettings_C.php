<?php

namespace App\Http\Controllers\Admin\subscriptions;

use App\Http\Controllers\Controller;;


use App\Models\subscriptions\SubscriptionSettings_M;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Route;

class SubscriptionSettings_C extends Controller
{

    public function index($type)
    {
        return view('dashbord.admin.subscriptions.settings.settings_data');
    }
    /*******************************************************/
    public function settings_data($type)
    {
        //dd( Route::current()->parameters['type']);
        $data['type'] = $type;
            //dd($data['type'] );
        return view('dashbord.admin.subscriptions.settings.settings_data', $data);
    }
    /*******************************************************/
    public function get_ajax_settings(Request $request,$type)
    {

        if ($request->ajax()) {
            $status = $request->input('status');
            $data = SubscriptionSettings_M::where('ttype',$type)->get();
           // dd($data);
            $counter = 0;

            return DataTables::of($data)
                ->addColumn('id', function () use (&$counter) {
                    $counter++;
                    return $counter;
                })
                ->editColumn('title_ar', function ($row) {
                    $title = $row->getTranslations('title');
                    return optional($title)['ar'];
                })
                ->editColumn('title_en', function ($row) {
                    $title = $row->getTranslations('title');
                    return optional($title)['en'];
                })

                ->addColumn('actions', function ($row) {

                    return '
        <div class="dropdown">
            <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                ' . trans('forms.action') . '
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                <li><a class="dropdown-item" data-bs-toggle="modal"  data-bs-target="#exampleModal" onclick="edit_setting('.$row->id.')" >' . trans('forms.edit_btn') . '</a></li>
                <li>
                    <a class="dropdown-item"  href="' . route('admin.subscriptions.delete_setting', $row->id) . '"  data-kt-table-delete="delete_row" title="' . trans('forms.delete_btn') . '">
                        ' . trans('forms.delete_btn') . '
                    </a>

                </li>
            </ul>
        </div>';







                })->rawColumns(['actions'])
                ->make(true);

            return $dataTable->toJson();
        }
    }
    /*******************************************************/
    public function create()
    {

    }
    /*******************************************************/
    public function store(Request $request,SubscriptionSettings_M $subscriptionSettings_M)
    {
        try{
            if(!empty($request->row_id))
            {
                $subscriptionSettings_M->update_settings($request,$request->row_id);
            }else{

            $subscriptionSettings_M->save_settings($request);
            }
            toastr()->addSuccess(trans('sub.add_msg'));
            return redirect()->route('admin.subscriptions.settings_data',$request->type);
        }catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /*********************************************************/
    public function show($id)
    {

    }
    /*********************************************************/
    public function edit($id)
    {
        $data['all_data']=SubscriptionSettings_M::find($id);
        return response()->json($data);
    }
    /*********************************************************/
    public function update($id)
    {

    }
    /*********************************************************/
    public function delete_setting($id)
    {
        try{
            $setting = SubscriptionSettings_M::find($id);
            $type   = $setting->ttype;
            $setting->delete();
            toastr()->addSuccess(trans('sub.add_msg'));
            return response()->json(['message' => 'Image deleted successfully.'], 200);
           // return redirect()->route('admin.subscriptions.settings_data',$type);
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /*********************************************************/
}

?>
