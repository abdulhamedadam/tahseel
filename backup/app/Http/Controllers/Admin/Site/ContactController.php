<?php

namespace App\Http\Controllers\Admin\Site;
use App\Http\Controllers\Controller;
use App\Models\Site\SiteContact;
use DataTables;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
//        $contact=SiteContact::all();
         if ($request->ajax()) {
            $contact = SiteContact::select('id','name','email','phone','subject');
            return Datatables::of($contact)
                ->editColumn('subject', function ($row){
                    return '<button type="button" class="btn btn-bg-secondary"
                    data-bs-toggle="modal" data-bs-target="#kt_modal_1"
                     onclick="getDetailes('.$row->id.')">
                   Subject
                </button>';
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
                         <a href="' . route('admin.contact.delete', $row->id) . '"
                       title="Delete" class="menu-link px-3"
                       data-kt-ecommerce-category-filter="delete_row">'.trans('contactus.Delete').'</a>
                   </div>


                   </div>';
                 })
                ->rawColumns(['action','subject'])
                ->make(true);
        }

        return view('dashbord.admin.Site.contact.overview');

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
        $contact=new SiteContact();
        $contact->name=$request->name;
        $contact->email=$request->email;
        $contact->phone=$request->phone;
        $contact->subject=$request->subject;
        $contact->save();

    return redirect()->route('admin.contact.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

        $one_data=SiteContact::find($request->id);
        return response()->json(array('one_data'=>$one_data),'200');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteContact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SiteContact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(SiteContact $contact,$id)
    {


        try {
            SiteContact::destroy($id);
            toastr()->addSuccess(trans('forms.Delete'));

            return redirect()->route('admin.contact.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    }

