<?php

namespace App\Http\Controllers\Admin\subscriptions;

use App\Http\Requests\Admin\subscription\offers\SaveOffer_R;
use App\Http\Requests\Admin\subscription\offers\UpdateOffer_R;
use App\Models\subscriptions\Offers_M;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
class Offers_C extends Controller
{
/**********************************************************/
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
      return view('dashbord.admin.subscriptions.offers.data_list');
  }
/**********************************************************/
    public function get_ajax_offers(Request $request)
    {
        if ($request->ajax()) {
            $data = Offers_M::all();
            //dd($data);
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
//                ->editColumn('title_en', function ($row) {
//                    $title = $row->getTranslations('name');
//                    return optional($title)['en'];
//                })

                ->editColumn('offer_type', function ($row) {
                    $sub_type_arr = [
                        'monthly' => trans('sub.monthly'),
                        'quarter' => trans('sub.quarter'),
                        'semi' => trans('sub.semi'),
                        'annual' => trans('sub.annual'),
                    ];
                    return $sub_type_arr[$row->offer_category];
                })
                ->editColumn('customize_to', function ($row) {
                    $sub_type_arr=['clients'=>trans('sub.clients'),'workers'=>trans('sub.workers')];
                    return $sub_type_arr[$row->customize_to];
                })
                ->editColumn('price', function ($row) {
                    return $row->price;
                })

                ->editColumn('duration', function ($row) {
                    return $row->duration;
                })
                 ->editColumn('status', function ($row) {
                     $status_arr=['active'=>trans('sub.active'),'notactive'=>trans('sub.notactive')];

                     return $status_arr[$row->status];
                })

//                ->editColumn('details_ar', function ($row) {
//                    $details = $row->getTranslations('details');
//                    return optional($details)['en'];
//                })
//
//                ->editColumn('details_en', function ($row) {
//                    $details = $row->getTranslations('details');
//                    return optional($details)['en'];
//                })

                ->addColumn('actions', function ($row) {
                      return '
        <div class="dropdown">
            <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                ' . trans('forms.action') . '
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                <li><a class="dropdown-item" href="'.route('admin.subscriptions.offers.edit',$row->id).'" >' . trans('forms.edit_btn') . '</a></li>
                <li>
                    <a class="dropdown-item"  href="' . route('admin.subscriptions.delete_offer', $row->id) . '"  data-kt-table-delete="delete_row" title="' . trans('forms.delete_btn') . '">
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
/**********************************************************/
  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      return view('dashbord.admin.subscriptions.offers.form');
  }
/**********************************************************/
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(SaveOffer_R $request,Offers_M $offers_M)
  {
      try {
          if ($request->hasFile('contract')) {
              $file = $request->file('contract');
              $contract = $this->saveFile($file, 'main_subscriptions');
          } else {
              $contract = null;
          }
          $offers_M->save_data($request, $contract);
          toastr()->addSuccess(trans('sub.add_msg'));
          return redirect()->route('admin.subscriptions.offers.index');
      } catch (\Exception $e) {
          dd($e);
          return redirect()->back()->withErrors(['error' => $e->getMessage()]);
      }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
      $data['record']=Offers_M::find($id);
      return view('dashbord.admin.subscriptions.offers.edit',$data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update_offer(UpdateOffer_R $request,Offers_M $offers_M,$id)
  {
      try {
          if ($request->hasFile('contract')) {
              $file = $request->file('contract');
              $contract = $this->saveFile($file, 'main_subscriptions');
          } else {
              $contract = null;
          }
          $offers_M->update_data($request, $contract,$id);
          toastr()->addSuccess(trans('sub.add_msg'));
          return redirect()->route('admin.subscriptions.offers.index');
      } catch (\Exception $e) {
          dd($e);
          return redirect()->back()->withErrors(['error' => $e->getMessage()]);
      }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function delete_offer($id)
  {
      try {
          $offer_m=Offers_M::find($id);
          $offer_m->delete();
          return response()->json(['message' => 'Image deleted successfully.'], 200);
         // toastr()->addSuccess(trans('sub.add_msg'));
          //return redirect()->route('admin.subscriptions.offers.index');
      } catch (\Exception $e) {
          dd($e);
          return redirect()->back()->withErrors(['error' => $e->getMessage()]);
      }
  }

}

?>
