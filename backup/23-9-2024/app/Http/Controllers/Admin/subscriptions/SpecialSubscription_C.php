<?php

namespace App\Http\Controllers\Admin\subscriptions;

use App\Models\subscriptions\SpecialSubscription_M;
use App\Models\subscriptions\SubscriptionSettings_M;
use App\Models\Trainers;
use App\Traits\ImageProcessing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class SpecialSubscription_C extends Controller
{
    use ImageProcessing;
    protected $view='dashbord.admin.subscriptions.special_subscription.';

  public function index(Request  $request)
  {
      if ($request->ajax()) {

          $data = SpecialSubscription_M::all();
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
//              ->editColumn('title_en', function ($row) {
//                  $title = $row->getTranslations('name');
//                  return optional($title)['en'];
//              })

              ->editColumn('price', function ($row) {
                  return $row->price;
              })
              ->editColumn('duration', function ($row) {
                  return $row->duration;
              })
              ->editColumn('max_discount', function ($row) {
                  return $row->max_discount;
              })

              ->addColumn('actions', function ($row) {
                  return '
        <div class="dropdown">
            <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                ' . trans('forms.action') . '
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                <li><a class="dropdown-item" href="' . route('admin.subscriptions.special_subscriptions.edit', $row->id) . '">' . trans('forms.edit_btn') . '</a></li>
                <li>
                    <a class="dropdown-item"  href="' . route('admin.subscriptions.special_subscriptions.destroy', $row->id) . '"  data-kt-table-delete="delete_row" title="' . trans('forms.delete_btn') . '">
                        ' . trans('forms.delete_btn') . '
                    </a>

                </li>
            </ul>
        </div>';
              })
              ->rawColumns(['actions'])
              ->make(true);
      }
      return view($this->view.'index');
  }

   /***********************************************************/
  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      $data['exercise_type']=SubscriptionSettings_M::where('ttype','exercise_type')->get();
      //dd($data);
      return view($this->view.'form',$data);
  }
    /***********************************************************/
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {


      try {
       //   dd($request);


          $insert_data['name']=['ar'=>$request->title_ar,'en'=>$request->title_en];
          $insert_data['exercise_type']=$request->exercise_type;
          $insert_data['price']=$request->price;
          $insert_data['duration']=$request->duration;
          $insert_data['max_discount']=$request->max_discount;


          //dd($insert_data);
          SpecialSubscription_M::create($insert_data);
          toastr()->addSuccess(trans('forms.success'));
          return redirect()->route('admin.subscriptions.special_subscriptions.index');
      } catch (\Exception $e) {
          // Rollback the transaction on error

          return redirect()->back()->withErrors(['error' => $e->getMessage()]);
      }
  }
    /***********************************************************/
  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
      return view($this->view.'show');
  }
    /***********************************************************/
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
//      $data['trainers']=Trainers::all();
      $data['exercise_type']=SubscriptionSettings_M::where('ttype','exercise_type')->get();
      $data['one_data']=SpecialSubscription_M::find($id);
      return view($this->view.'edit',$data);
  }
    /***********************************************************/
  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id)
  {
      try {
          //   dd($request);

          $special_subscription=SpecialSubscription_M::find($id);
          $insert_data['name']=['ar'=>$request->title_ar,'en'=>$request->title_en];
//          $insert_data['trainer_id']=$request->trainer_id;
          $insert_data['exercise_type']=$request->exercise_type;
          $insert_data['price']=$request->price;
          $insert_data['duration']=$request->duration;
          $insert_data['max_discount']=$request->max_discount;
          $special_subscription->update($insert_data);
          toastr()->addSuccess(trans('forms.success'));
          return redirect()->route('admin.subscriptions.special_subscriptions.index');
      } catch (\Exception $e) {
          // Rollback the transaction on error

          return redirect()->back()->withErrors(['error' => $e->getMessage()]);
      }
  }
    /***********************************************************/
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
      try {
      $special_subscription=SpecialSubscription_M::find($id);
          $special_subscription->delete();
        // toastr()->addSuccess(trans('sub.add_msg'));
          return response()->json(['message' => 'Image deleted successfully.'], 200);
  } catch (\Exception $e) {
      dd($e);
      return redirect()->back()->withErrors(['error' => $e->getMessage()]);
  }
  }

}

?>
