<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\SaveSiteDataRequest;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\AreaSetting;
use App\Models\Admin\SarfBand;
use App\Models\Admin\Subscription;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
// use DataTables;
class GeneralSettingsController extends Controller
{

    use ImageProcessing;
    use ValidationMessage;

    /***********************************************************/

    protected $SubscriptionsRepository;
    protected $SarfBandRepository;

    public function __construct(BasicRepositoryInterface $basicRepository)
    {
        $this->SubscriptionsRepository = createRepository($basicRepository, new Subscription());
        $this->SarfBandRepository = createRepository($basicRepository, new SarfBand());
    }
    /***********************************************************/

    public function subscriptions()
    {
        return view('dashbord.admin.settings.subscriptions');
    }

    /***********************************************************/
    public function get_ajax_subscriptions()
    {
        if (request()->ajax()) {
            try {
                $data = $this->SubscriptionsRepository->getAll();

                $counter = 0;

                return DataTables::of($data)
                    ->addColumn('id', function () use (&$counter) {
                        $counter++;
                        return $counter;
                    })
                    ->addColumn('name', function ($row) {
                        return $row->name;
                    })
                    ->addColumn('description', function ($row) {
                        return $row->description;
                    })
                    ->addColumn('action', function ($row) {
                        return '<a data-bs-toggle="modal" data-bs-target="#modalsubscriptions" onclick="edit_subscription(' . $row->id . ')" class="btn btn-sm btn-warning" title="">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a onclick="return confirm(\'Are You Sure To Delete?\')" href="' . route('admin.delete_subscription', $row->id) . '"  class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </a>
                        ';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                Log::error('Error in get_ajax_subscriptions: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()]);
            }
        }
    }

    /*****************************************************/
    public function add_subscription(Request $request)
    {
        try {
            // dd($request->all());
            $subscription_Model = new Subscription();
            $data = $subscription_Model->add_subscription_data($request);
            if(empty($request->row_id))
            {
                $this->SubscriptionsRepository->create($data);
            }else{
                $this->SubscriptionsRepository->update($request->row_id, $data);
            }
            // notify()->success(trans('subscriptions_added_successfully'), '');
            $request->session()->flash('toastMessage', trans('subscriptions_added_successfully'));
            return redirect()->route('admin.subscriptions');

        } catch (\Exception $e) {
            test($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }
    /*****************************************************/
    public function delete_subscription(Request $request,$id)
    {
        try {
            $subscription = $this->SubscriptionsRepository->getById($id);
            $this->SubscriptionsRepository->delete($id);
            $request->session()->flash('toastMessage', trans('subscription_deleted_successfully'));
            return redirect()->route('admin.subscriptions');

        } catch (\Exception $e) {
            test($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /****************************************************/
    public function edit_subscription($id)
    {
        $data['all_data']=$this->SubscriptionsRepository->getById($id);
        return response()->json($data);
    }

    /****************************************************/
    public function sarf_bands()
    {
        return view('dashbord.admin.settings.sarf_band');
    }

    /***********************************************************/
    public function get_ajax_sarf_bands()
    {
        if (request()->ajax()) {
            try {
                $data = $this->SarfBandRepository->getAll();

                $counter = 0;

                return DataTables::of($data)
                    ->addColumn('id', function () use (&$counter) {
                        $counter++;
                        return $counter;
                    })
                    ->addColumn('title', function ($row) {
                        return $row->title;
                    })
                    ->addColumn('action', function ($row) {
                        return '<a data-bs-toggle="modal" data-bs-target="#modalSarfBands" onclick="edit_sarf_band(' . $row->id . ')" class="btn btn-sm btn-warning" title="">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a onclick="return confirm(\'Are You Sure To Delete?\')" href="' . route('admin.delete_sarf_band', $row->id) . '"  class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                Log::error('Error in get_ajax_sarf_bands: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()]);
            }
        }
    }

    /*****************************************************/
    public function add_sarf_band(Request $request)
    {
        try {
            // dd($request->all());
            $sarf_band_Model = new SarfBand();
            $data = $sarf_band_Model->add_sarf_band_data($request);
            if(empty($request->row_id))
            {
                $this->SarfBandRepository->create($data);

            }else{
                $this->SarfBandRepository->update($request->row_id, $data);
            }
            // notify()->success(trans('sarf_band_added_successfully'), '');
            $request->session()->flash('toastMessage', trans('sarf_band_added_successfully'));
            return redirect()->route('admin.sarf_bands');

        } catch (\Exception $e) {
            test($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }
    /*****************************************************/
    public function delete_sarf_band(Request $request,$id)
    {
        try {
            $bsarf_band = $this->SarfBandRepository->getById($id);
            $this->SarfBandRepository->delete($id);
            // notify()->success(trans('sarf_band_deleted_successfully'), '');
            $request->session()->flash('toastMessage', trans('sarf_band_deleted_successfully'));
            return redirect()->route('admin.sarf_bands');

        } catch (\Exception $e) {
            test($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /****************************************************/
    public function edit_sarf_band($id)
    {
        $data['all_data']=$this->SarfBandRepository->getById($id);
        return response()->json($data);
    }
}
