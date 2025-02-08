<?php

namespace App\Http\Controllers\Admin\Site;

use App\Http\Controllers\Controller;


use App\Http\Requests\Site\Videos\StoreRequest;
use App\Http\Requests\Site\Videos\UpdateRequest;
use App\Http\Resources\Site\VideoResource;
use App\Models\Site\SiteVideo;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VideosController extends Controller
{
    use ResponseApi;
    protected $upload_folder = 'Site/Videos';


    private function extractVideoId($videoLink)
    {
        // Extract video ID from the YouTube link
        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $videoLink, $matches);

        // Check if the regex matched and return the video ID or null
        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $allData = SiteVideo::select('*');
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
                             <a href="' . route('admin.videos.edit', $row->id) . '"
                               title="' . trans('forms.edite_btn') . '" class="menu-link px-3"
                               >' . trans('forms.edite_btn') . '</a>
                        </div>
                   		<div class="menu-item px-3">
                                <a href="' . route('admin.videos.show', $row->id) . '"
                                           title="' . trans('forms.details') . '" class="menu-link px-3"
                                           >' . trans('forms.details') . '</a>
                        </div>
<div class="menu-item px-3">
                                <a href="javascript:void(0)" data-kt-table-details="details_row" data-url="' . route('admin.videos.load_details', $row->id) . '"
                                           name="' . trans('forms.details') . '" class="menu-link px-3"
                                         data-bs-toggle="modal" data-bs-target="#kt_modal_1"  >' . trans('forms.details') . '</a>
                        </div>
                        <div class="menu-item px-3">
                                <a href="' . route('admin.videos.destroy', $row->id) . '" data-kt-table-delete="delete_row"
                                           title="' . trans('forms.delete_btn') . '" class="menu-link px-3"
                                           >' . trans('forms.delete_btn') . '</a>
                        </div>
                  </div>



                   </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashbord.admin.Site.videos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashbord.admin.Site.videos.create');

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
            $insert_data['link_id'] = $this->extractVideoId($insert_data['full_link']);
            $inserted_data = SiteVideo::create($insert_data);

            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.videos.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $one_data = SiteVideo::findOrFail($id);
        $one_data = new VideoResource($one_data);
//        $data['one_data'] = $this->prepare_data($one_data->edite_data($one_data));
        $data['one_data'] = $this->prepare_data($one_data);
        return view('dashbord.admin.Site.videos.details', $data);
    }

    public function show_load($id)
    {
        $one_data = SiteVideo::findOrFail($id);
        $one_data = new VideoResource($one_data);
        $data['one_data'] = $this->prepare_data($one_data);
//        dd($data);
        return view('dashbord.admin.Site.videos.load_details', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $one_data = SiteVideo::findOrFail($id);
        $one_data = new VideoResource($one_data);
        $data['one_data'] = $this->prepare_data($one_data->edite_data($one_data));
        return view('dashbord.admin.Site.videos.edit', $data);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $data = SiteVideo::find($request->id);

            $update_data = $request->all();
            $update_data['title'] = ['en' => $request->title_en, 'ar' => $request->title_ar];
            if ($request->hasFile('main_image')) {
                $file = $request->file('main_image');

                $dataX = $this->saveImage($file, $this->upload_folder);
                $update_data['main_image'] = $dataX;

            }
            $update_data['link_id'] = $this->extractVideoId($update_data['full_link']);

            $data->update($update_data);

            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.videos.index');
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

            $delete_data = SiteVideo::find($id);
            $this->deleteImage($delete_data->main_image);
            $delete_data->delete();
            toastr()->error(trans('forms.Delete'));

            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);

        }
    }


}
