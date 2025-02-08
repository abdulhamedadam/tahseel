<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;


use App\Http\Requests\Site\About\StoreRequest;
use App\Http\Requests\Site\About\UpdateRequest;
use App\Http\Resources\Site\AboutResource;
use App\Models\Site\SiteAbout;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AboutController extends Controller
{
    use ResponseApi;

    protected $upload_folder = 'Site/About';


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $allData = SiteAbout::select('*');
            return Datatables::of($allData)
                ->editColumn('address', function ($row) {
                    return $row->address;
                })->editColumn('sub_address', function ($row) {
                    return $row->sub_address;
                })
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
                             <a href="' . route('admin.about.edit', $row->id) . '"
                               address="' . trans('forms.edite_btn') . '" class="menu-link px-3"
                               >' . trans('forms.edite_btn') . '</a>
                        </div>
                   		<div class="menu-item px-3">
                                <a href="' . route('admin.about.show', $row->id) . '"
                                           address="' . trans('forms.details') . '" class="menu-link px-3"
                                           >' . trans('forms.details') . '</a>
                        </div>
                        <div class="menu-item px-3">
                                <a href="javascript:void(0)" data-kt-table-details="details_row" data-url="' . route('admin.about.load_details', $row->id) . '"
                                           address="' . trans('forms.details') . '" class="menu-link px-3"
                                         data-bs-toggle="modal" data-bs-target="#kt_modal_1"  >' . trans('forms.details') . '</a>
                        </div>
                        <div class="menu-item px-3">
                                <a href="' . route('admin.about.destroy', $row->id) . '" data-kt-table-delete="delete_row"
                                           address="' . trans('forms.delete_btn') . '" class="menu-link px-3"
                                           >' . trans('forms.delete_btn') . '</a>
                        </div>
                  </div>



                   </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashbord.admin.Site.About.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashbord.admin.Site.About.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
//        dd($request->all());
        try {

            $insert_data = $request->all();
            $insert_data['address'] = ['en' => $request->address_en, 'ar' => $request->address_ar];
            $insert_data['sub_address'] = ['en' => $request->sub_address_en, 'ar' => $request->sub_address_ar];
            $insert_data['details'] = ['en' => $request->details_en, 'ar' => $request->details_ar];
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                $dataX = $this->saveImage($file, $this->upload_folder);
                $insert_data['image'] = $dataX;

            }
            $inserted_data = SiteAbout::create($insert_data);
            $insert_id = $inserted_data->id;

//            dd($insert_data);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.about.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $one_data = SiteAbout::findOrFail($id);
        $one_data = new AboutResource($one_data);
        $data['one_data'] = $this->prepare_data($one_data);
//        dd($data);
        return view('dashbord.admin.Site.About.details', $data);
    }    public function show_load($id)
    {
        $one_data = SiteAbout::findOrFail($id);
        $one_data = new AboutResource($one_data);
        $data['one_data'] = $this->prepare_data($one_data);
//        dd($data);
        return view('dashbord.admin.Site.About.load_details', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $one_data = SiteAbout::findOrFail($id);
        $one_data = new AboutResource($one_data);
        $data['one_data'] = $this->prepare_data($one_data->edite_data($one_data));
//        dd($data);
        return view('dashbord.admin.Site.About.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        //        dd($request->all());
        try {
            $data = SiteAbout::find($request->id);

            $update_data = $request->all();
            $update_data['address'] = ['en' => $request->address_en, 'ar' => $request->address_ar];
            $update_data['sub_address'] = ['en' => $request->sub_address_en, 'ar' => $request->sub_address_ar];
            $update_data['details'] = ['en' => $request->details_en, 'ar' => $request->details_ar];
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                $dataX = $this->saveImage($file, $this->upload_folder);
                $update_data['image'] = $dataX;

            }
            $data->update($update_data);

//            dd($insert_data);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.about.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            $delete_data = SiteAbout::find($id);
            $this->deleteImage($delete_data->image);

            toastr()->error(trans('forms.Delete'));

//            return redirect()->route('admin.Unite.index');
            /*            return redirect()->back();*/
            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } catch (\Exception $e) {
            /*            return redirect()->back()->withErrors(['error' => $e->getMessage()]);*/
            return response()->json(['error' => $e->getMessage()], 500);

        }
    }

}
