<?php

namespace App\Http\Controllers\Admin\subscriptions;

use App\Http\Requests\Admin\subscription\main_subscription\SaveMainSubsacription_R;
use App\Http\Requests\Admin\subscription\main_subscription\UpdateMainSubsacription_R;
use App\Http\Requests\Admin\subscription\main_subscription\UpdateOffer_R;

use App\Models\subscriptions\MainSubscription_M;
use App\Models\subscriptions\SubscriptionSettings_M;
use App\Traits\ImageProcessing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

class MainSubscription_C extends Controller
{
    use ImageProcessing;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('dashbord.admin.subscriptions.main_subscription.data_list');
    }
    /****************************************************************/
    public function get_ajax_main_subscription(Request $request)
    {
        if ($request->ajax()) {

            $data = MainSubscription_M::all();
            $counter = 0;

            return DataTables::of($data)
                ->addColumn('id', function () use (&$counter) {
                    $counter++;
                    return $counter;
                })
                ->editColumn('title', function ($row) {
                    $title = $row->getTranslations('name');
                    return $row->name;
                })
                ->editColumn('sub_type', function ($row) {
                    $sub_type_arr = [
                        'monthly' => trans('sub.monthly'),
                        'quarter' => trans('sub.quarter'),
                        'semi' => trans('sub.semi'),
                        'annual' => trans('sub.annual'),
                    ];
                    return $sub_type_arr[$row->category] ?? '';
                })
                ->editColumn('customize_to', function ($row) {
                    return $row->customize_to;
                })
                ->editColumn('price', function ($row) {
                    return $row->price;
                })
                ->editColumn('duration', function ($row) {
                    return $row->duration;
                })
                ->editColumn('max_discount', function ($row) {
                    return $row->max_discount;
                })
                /* Uncomment if needed
                ->editColumn('details_ar', function ($row) {
                    $details = $row->getTranslations('details');
                    return optional($details)['ar'];
                })
                ->editColumn('details_en', function ($row) {
                    $details = $row->getTranslations('details');
                    return optional($details)['en'];
                })
                */
                ->addColumn('actions', function ($row) {
                    return '
        <div class="dropdown">
            <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                ' . trans('forms.action') . '
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                <li><a class="dropdown-item" href="' . route('admin.subscriptions.main_subscriptions.edit', $row->id) . '">' . trans('forms.edit_btn') . '</a></li>
                <li>
                    <a class="dropdown-item" href="' . route('admin.subscriptions.main_subscriptions.destroy', $row->id) . '"  data-kt-table-delete="delete_row" title="' . trans('forms.delete_btn') . '">
                        ' . trans('forms.delete_btn') . '
                    </a>

                </li>
            </ul>
        </div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    /****************************************************************/
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        return view('dashbord.admin.subscriptions.main_subscription.form');
    }
    /****************************************************************/
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(SaveMainSubsacription_R $request, MainSubscription_M $mainSubscription_M)
    {
        try {
            if ($request->hasFile('contract')) {
                $file = $request->file('contract');
                $contract = $this->saveImage($file, 'main_subscriptions');
            } else {
                $contract = null;
            }
            $mainSubscription_M->save_data($request, $contract);
            toastr()->addSuccess(trans('sub.add_msg'));
            return redirect()->route('admin.subscriptions.main_subscriptions.index');
        } catch (\Exception $e) {
           // dd($request->all(),$e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /****************************************************************/
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {

    }
    /****************************************************************/
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data['record']=MainSubscription_M::find($id);
        return view('dashbord.admin.subscriptions.main_subscription.edit',$data);
    }
    /****************************************************************/
    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update_main_subscription(UpdateMainSubsacription_R $request, MainSubscription_M $mainSubscription_M, $id)
    {
        try {
            if ($request->hasFile('contract')) {
                $file = $request->file('contract');
                $contract = $this->saveFile($file, 'main_subscriptions');
            } else {
                $contract = null;
            }
            $mainSubscription_M->update_data($request, $contract,$id);
            toastr()->addSuccess(trans('sub.add_msg'));
            return redirect()->route('admin.subscriptions.main_subscriptions.index');
        } catch (\Exception $e) {
            dd($e);
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
            $MainSubscription_M=MainSubscription_M::find($id);
            $MainSubscription_M->delete();
           // toastr()->addSuccess(trans('sub.add_msg'));
            return response()->json(['message' => 'Image deleted successfully.'], 200);
           // return redirect()->route('admin.subscriptions.main_subscriptions.index');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}

?>
