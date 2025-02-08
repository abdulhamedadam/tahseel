<?php

namespace App\Http\Controllers\Admin\subscriptions;

use App\Http\Requests\Admin\subscription\transportation\SaveTransportation_R;
use App\Models\subscriptions\MainSubscription_M;
use App\Models\subscriptions\SubscriptionSettings_M;
use App\Models\subscriptions\Transportation_M;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\Log;

class Transportation_C extends Controller
{
/*****************************************************/
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {

      return view('dashbord.admin.subscriptions.transportation.data_list');
  }
  /****************************************************************/
    public function get_ajax_transportation(Request $request)
    {
        if ($request->ajax()) {

            $data = Transportation_M::all();
            //dd($data);
            $counter = 0;
            return DataTables::of($data)
                ->addColumn('id', function () use (&$counter) {
                    $counter++;
                    return $counter;
                })
                ->editColumn('trip_day', function ($row) {

                    return $row->moving_day;
                })
                ->editColumn('trip_time', function ($row) {

                    return $row->trip_time;
                })

                ->editColumn('moving_time', function ($row) {
                    return $row->moving_time;
                })
               /* ->editColumn('car_type', function ($row) {

                    $carTypeSetting = json_decode($row->car_type_setting, true);
                    return $carTypeSetting['title'][app()->getLocale()] ;
                })*/



                ->editColumn('persons_number', function ($row) {
                    return $row->persons_number;
                })

                ->addColumn('actions', function ($row) {

                      return '
        <div class="dropdown">
            <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                ' . trans('forms.action') . '
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                <li><a class="dropdown-item" href="' . route('admin.subscriptions.transportation.edit', $row->id) . '">' . trans('forms.edit_btn') . '</a></li>
                <li>
                    <a class="dropdown-item"  href="' . route('admin.subscriptions.delete_transportation', $row->id) . '"  data-kt-table-delete="delete_row" title="' . trans('forms.delete_btn') . '">
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
  /*****************************************************/
  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      $data['car_type']=SubscriptionSettings_M::where('ttype','car_type')->get();
      return view('dashbord.admin.subscriptions.transportation.form',$data);
  }
  /*****************************************************/
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(SaveTransportation_R $request ,Transportation_M $transportation_M)
  {
      try {

          $transportation_M->save_data($request);
          toastr()->addSuccess(trans('sub.add_msg'));
          return redirect()->route('admin.subscriptions.transportation.index');
      } catch (\Exception $e) {
          dd($e);
          return redirect()->back()->withErrors(['error' => $e->getMessage()]);
      }
  }
    /*****************************************************/
  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {

  }
    /*****************************************************/
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
      $data['car_type'] = SubscriptionSettings_M::where('ttype','car_type')->get();
      $data['record'   ]= Transportation_M::find($id);
      return view('dashbord.admin.subscriptions.transportation.edit',$data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update_transportation(SaveTransportation_R $request,Transportation_M $transportation_M,$id)
  {
      try {

          $transportation_M->update_data($request,$id);
          toastr()->addSuccess(trans('sub.add_msg'));
          return redirect()->route('admin.subscriptions.transportation.index');
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
  public function delete_transportation($id)
  {
      try {
          $transportation_M=Transportation_M::find($id);
          $transportation_M->delete();
          return response()->json(['message' => 'Image deleted successfully.'], 200);
        //  toastr()->addSuccess(trans('sub.add_msg'));
         // return redirect()->route('admin.subscriptions.transportation.index');
      } catch (\Exception $e) {
          dd($e);
          return redirect()->back()->withErrors(['error' => $e->getMessage()]);
      }
  }

}

?>
