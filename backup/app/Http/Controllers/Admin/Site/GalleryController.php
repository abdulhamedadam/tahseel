<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;

use App\Http\Requests\Site\Gallery\StoreRequest;
use App\Http\Requests\Site\Gallery\UpdateRequest;
use App\Http\Resources\Site\PhotoResource;
use App\Models\Site\SitePhoto;
use App\Models\Site\SitePhotoImage;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    use ResponseApi;

    protected $upload_folder = 'Site/Gallery';


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $allData = SitePhoto::select('*');
            return Datatables::of($allData)
                ->editColumn('title', function ($row) {
                    return $row->title;
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
                             <a href="' . route('admin.gallery.edit', $row->id) . '"
                               title="' . trans('forms.edite_btn') . '" class="menu-link px-3"
                               >' . trans('forms.edite_btn') . '</a>
                        </div>
                   		<div class="menu-item px-3">
                                <a href="' . route('admin.gallery.show', $row->id) . '"
                                           title="' . trans('forms.details') . '" class="menu-link px-3"
                                           >' . trans('forms.details') . '</a>
                        </div>

                        <div class="menu-item px-3">
                                <a href="' . route('admin.gallery.destroy', $row->id) . '" data-kt-table-delete="delete_row"
                                           title="' . trans('forms.delete_btn') . '" class="menu-link px-3"
                                           >' . trans('forms.delete_btn') . '</a>
                        </div>
                  </div>



                   </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashbord.admin.Site.gallery.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashbord.admin.Site.gallery.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {

            $insert_data = $request->all();
            $insert_data['title'] = ['en' => $request->title_en, 'ar' => $request->title_ar];

            if ($request->hasFile('main_image')) {
                $file = $request->file('main_image');

                $dataX = $this->saveImage($file, $this->upload_folder);
                $insert_data['main_image'] = $dataX;

            }
            $inserted_data = SitePhoto::create($insert_data);
            $insert_id = $inserted_data->id;
            if ($request->hasFile('images')) {
                $blog_folder = $this->upload_folder . '/' . $insert_id;
                $blog_images = [];
                foreach ($request->file('images') as $image) {

                    $dataX = $this->saveImageAndThumbnail($image, $blog_folder, true);


                    $blog_images[] = [
                        'photo_id' => $insert_id,
                        'image' => $dataX['image'],
                        'thumbnailsm' => $dataX['thumbnailsm'],
                        'thumbnailmd' => $dataX['thumbnailmd'],

                    ];


                }
                SitePhotoImage::insert($blog_images);
            }
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.gallery.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $one_data = SitePhoto::with('images')->findOrFail($id);
        $one_data = new PhotoResource($one_data);
//        $data['one_data'] = $this->prepare_data($one_data->edite_data($one_data));
        $data['one_data'] = $this->prepare_data($one_data);
        return view('dashbord.admin.Site.gallery.details', $data);
    }
    public function show_load($id)
    {
        $one_data = SitePhoto::with('images')->findOrFail($id);
        $one_data = new PhotoResource($one_data);
        $data['one_data'] = $this->prepare_data($one_data);
//        dd($data);
        return view('dashbord.admin.Site.gallery.load_details', $data);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $one_data = SitePhoto::with('images')->findOrFail($id);
        $one_data = new PhotoResource($one_data);
        $data['one_data'] = $this->prepare_data($one_data->edite_data($one_data));
        return view('dashbord.admin.Site.gallery.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $data = SitePhoto::find($request->id);

            $update_data = $request->all();
            $update_data['title'] = ['en' => $request->title_en, 'ar' => $request->title_ar];
            if ($request->hasFile('main_image')) {
                $file = $request->file('main_image');

                $dataX = $this->saveImage($file, $this->upload_folder);
                $update_data['main_image'] = $dataX;

            }
            $data->update($update_data);
            $insert_id = $request->id;
            if ($request->hasFile('images')) {
                $blog_folder = $this->upload_folder . '/' . $insert_id;
                $blog_images = [];
                foreach ($request->file('images') as $image) {

                    $dataX = $this->saveImageAndThumbnail($image, $blog_folder, true);


                    $blog_images[] = [
                        'photo_id' => $insert_id,
                        'image' => $dataX['image'],
                        'thumbnailsm' => $dataX['thumbnailsm'],
                        'thumbnailmd' => $dataX['thumbnailmd'],

                    ];


                }
                SitePhotoImage::insert($blog_images);
            }
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.gallery.index');
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

            $delete_data = SitePhoto::with('images')->find($id);
            $this->deleteImage($delete_data->main_image);

            $delete_data->images()->delete();
            $delete_data->delete();
            $blog_folder = $this->upload_folder . '/' . $id;
            $this->deleteFolder($blog_folder);
            toastr()->error(trans('forms.Delete'));

            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);

        }
    }

    public function destroy_image($id)
    {
        try {
            $delete_data = SitePhotoImage::find($id);
            $this->deleteImage($delete_data->image);
            $this->deleteImage($delete_data->thumbnailmd);
            $this->deleteImage($delete_data->thumbnailsm);

            $delete_data->delete();
            toastr()->error(trans('forms.Delete'));

            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);

        }
    }
}
