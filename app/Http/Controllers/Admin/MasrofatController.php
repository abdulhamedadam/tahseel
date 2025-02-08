<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\masrofat\SaveRequest;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Employee;
use App\Models\Admin\Masrofat;
use App\Models\Admin\SarfBand;
use App\Services\MasrofatService;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasrofatController extends Controller
{
    use ImageProcessing;
    use ValidationMessage;

    protected $bandsRepository;
    protected $masrofatService;
    protected $employeesRepository;
    protected $masrofatRepository;

    public function __construct(BasicRepositoryInterface $basicRepository,MasrofatService $masrofatService)
    {
        $this->bandsRepository = createRepository($basicRepository, new SarfBand());
        $this->employeesRepository   = createRepository($basicRepository, new Employee());
        $this->masrofatRepository   = createRepository($basicRepository, new Masrofat());
        $this->masrofatService   = $masrofatService;


    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $allData = Masrofat::with(['employee', 'sarf_band', 'user'])->get();
            return DataTables::of($allData)
                ->editColumn('emp_id', function ($row) {
                    return $row->employee ? $row->employee->first_name .' '. $row->employee->last_name : 'N/A';
                })
                ->editColumn('band_id', function ($row) {
                    return $row->sarf_band ? $row->sarf_band->title : 'N/A';
                })
                ->editColumn('value', function ($row) {
                    return $row->value;
                })
                ->editColumn('notes', function ($row) {
                    return $row->notes;
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group btn-group-sm">
                            <a href="' . route('admin.masrofat.edit', $row->id) . '" class="btn btn-sm btn-primary" title="' . trans('masrofat.edit') . '" style="font-size: 16px;">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a onclick="return confirm(\'Are You Sure To Delete?\')"  href="' . route('admin.delete_masrofat', $row->id) . '"  class="btn btn-sm btn-danger" title="' . trans('masrofat.delete') . '" style="font-size: 16px;" onclick="return confirm(\'' . trans('masrofat.confirm_delete') . '\')">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashbord.masrofat.index');
    }

    /********************************************/
    public function create()
    {
        $data['employees']      = $this->employeesRepository->getAll();
        $data['bands']      = $this->bandsRepository->getAll();
        return view('dashbord.masrofat.form', $data);
    }

    /********************************************/
    public function store(SaveRequest $request)
    {
        try {
            // dd($request->all());
            $this->masrofatService->store($request);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.masrofat.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /********************************************/
    public function show(string $id)
    {
        //
    }

    /********************************************/
    public function edit(string $id)
    {
        $data['all_data']     = $this->masrofatRepository->getById($id);
        $data['employees']      = $this->employeesRepository->getAll();
        $data['bands']      = $this->bandsRepository->getAll();
        return view('dashbord.masrofat.edit', $data);
    }

    /********************************************/
    public function update(SaveRequest $request, string $id)
    {
        try {
            $this->masrofatService->update($request,$id);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.masrofat.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /********************************************/
    public function destroy(string $id)
    {
        try {
            $this->masrofatRepository->delete($id);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.masrofat.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /********************************************/

}
