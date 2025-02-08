<?php

namespace App\Http\Controllers\Admin\Hr;

use Illuminate\Http\Request;
use App\Http\Requests\Hr\operation\LoanRequest;
use App\Http\Controllers\Controller;
use App\Models\hr\operation\Loan;
use App\Models\hr\employee\Employee;
use Yajra\DataTables\Facades\DataTables;



class LoanController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $hr_loan = Loan::all();
    if ($request->ajax()) {
      $hr_loan = Loan::select('*');
      return Datatables::of($hr_loan)

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
                       <a href="' . route('admin.hr.hr_loan.edit', $row->id) . '"
                         title="' . trans('forms.edite_btn') . '" class="menu-link px-3"
                         >' . trans('forms.edite_btn') . '</a>
                  </div>
                  <div class="menu-item px-3">
                  <a href="javascript:void(0)" data-kt-table-details="details_row" data-url="' 
                  . route('admin.hr.hr_loan.load_details', $row->id) . '"
                             name="' . trans('forms.modal') . '" class="menu-link px-3"
                           data-bs-toggle="modal" data-bs-target="#kt_modal_1"  >' . trans('forms.modal') . '</a>
          </div>
                  <div class="menu-item px-3">
                  <a href= "'. route('admin.hr.hr_loan.show', $row->id) . '"
                             name="' . trans('forms.details') . '" class="menu-link px-3">' . trans('forms.details') . '</a>
          </div>
                  <div class="menu-item px-3">
                          <a href="' . route('admin.hr.hr_loan.destroy', $row->id) . '" data-kt-table-delete="delete_row"
                                     title="' . trans('forms.delete_btn') . '" class="menu-link px-3"
                                     >' . trans('forms.delete_btn') . '</a>
                  </div>
            </div>



             </div>';
          })
          ->rawColumns(['action'])
          ->make(true);
  }

  return view('dashbord.admin.hr.loan.index', compact('hr_loan'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    return view('dashbord.admin.hr.loan.create');

  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(LoanRequest $request)
  {
    try{
      $hr_loan=new Loan();
      $hr_loan->emp_id = $request->emp_id;
      $hr_loan->installments_num = $request->installments_num;
      $hr_loan->date_deductions = $request->date_deductions;   
      $hr_loan->date_deductions_int =strtotime($request->date_deductions);
      $hr_loan->date_loan = $request->date_loan; 
      $hr_loan->date_loan_int =strtotime($request->date_loan); 
      $hr_loan->loan_type=$request->loan_type;
      $hr_loan->value = $request->value;
      $hr_loan->reason = $request->reason;
      $hr_loan->year =  Date('Y', strtotime($request->date_loan));
      $hr_loan->month = Date('n', strtotime ($request->date_loan));
      $hr_loan->save();
      toastr()->addSuccess(trans('forms.success'));
      return redirect()->route('admin.hr.hr_loan.index');
   
   } catch (\Exception $e) {
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
    $hr_loan = loan::findOrFail($id);
    return view('dashbord.admin.hr.loan.details', compact('hr_loan'));
  }

  public function show_load($id)
  {
    $data['hr_loan'] = loan::findOrFail($id);
    return view('dashbord.admin.hr.loan.load_details', $data);

  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $hr_loan =Loan::find($id);
    //return view('dashbord.admin.hr.hr_loan.edit')->with('hr_loan', $hr_loan);
    return view('dashbord.admin.hr.loan.edit',compact('hr_loan'));

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(LoanRequest $request,$id)
  {
    try{ 

      $hr_loan_id=$id;
      $hr_loan = Loan::findorfail($hr_loan_id);
      $hr_loan->emp_id = $request->emp_id;
      $hr_loan->installments_num = $request->installments_num;
      $hr_loan->date_deductions = $request->date_deductions;   
      $hr_loan->date_deductions_int =strtotime($request->date_deductions);
      $hr_loan->date_loan = $request->date_loan;  
      $hr_loan->loan_type=$request->loan_type;
      $hr_loan->date_loan_int =strtotime($request->date_loan);
      $hr_loan->value = $request->value;
      $hr_loan->reason = $request->reason;
      $hr_loan->year =  Date('Y', strtotime($request->date_loan));
      $hr_loan->month = Date('n', strtotime ($request->date_loan));
      $hr_loan->update();
      toastr()->addSuccess(trans('forms.Update'));

      return redirect()->route('admin.hr.hr_loan.index');
   
   } catch (\Exception $e) {
      return redirect()->back()->withErrors(['error' => $e->getMessage()]);
  }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    try {
      $hr_loan=Loan::find($id);
      Loan::destroy($id);
      toastr()->addSuccess(trans('forms.Delete'));
      return response()->json(['message' => 'deleted successfully.'], 200);
      return redirect()->route('admin.hr.hr_loan.index');

  } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
  }
  }

} 


?>
