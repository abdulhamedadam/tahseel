<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\roles\RolesRequest;
use App\Interfaces\BasicRepositoryInterface;
use App\Services\RoleService;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    use ImageProcessing;
    use ValidationMessage;

    protected $rolesRepository;
    protected $permissionsRepository;
    protected $roleService;

    public function __construct(BasicRepositoryInterface $basicRepository, RoleService $roleService)
    {
        $this->rolesRepository   = createRepository($basicRepository, new Role());
        $this->permissionsRepository   = createRepository($basicRepository, new Permission());
        $this->roleService   = $roleService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $allData = $this->rolesRepository->getAll();
            return DataTables::of($allData)
                ->editColumn('title', function ($row) {
                    return $row->getTranslation('title', app()->getLocale()) ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group btn-group-sm">
                            <a href="' . route('admin.roles.edit', $row->id) . '" class="btn btn-sm btn-primary" title="' . trans('users.edit') . '" style="font-size: 16px;">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a onclick="return confirm(\'Are You Sure To Delete?\')"  href="' . route('admin.delete_role', $row->id) . '"  class="btn btn-sm btn-danger" title="' . trans('users.delete') . '" style="font-size: 16px;" onclick="return confirm(\'' . trans('users.confirm_delete') . '\')">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </div>
                    ';
                })
                ->rawColumns(['title', 'action'])
                ->make(true);
        }
        return view('dashbord.roles.index');
    }

    /********************************************/
    public function create()
    {
        // $data['permissions']  = $this->permissionsRepository->getAll();
        $data['sections']  = $this->permissions();
        // dd($data);
        return view('dashbord.roles.form', $data);
    }

    /********************************************/
    public function store(RolesRequest $request)
    {
        try {
            // dd($request->all());
            $this->roleService->store($request);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.roles.index');
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
        $data['role']      = $this->rolesRepository->getById($id);
        $data['sections']  = $this->permissions();
        return view('dashbord.roles.edit', $data);
    }

    /********************************************/
    public function update(RolesRequest $request, string $id)
    {
        try {
            // dd($request->all());
            $this->roleService->update($request,$id);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.roles.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /********************************************/
    public function destroy(string $id)
    {
        try {
            $this->roleService->delete($id);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /********************************************/

    public function permissions()
    {
        return [
            'Dashboard' => Permission::whereIn('name',  ['view_dashboard'])->get(),
            'sarf_band' => Permission::whereIn('name', [
                'view_sarf_band',
                'add_sarf_band',
                'edit_sarf_band',
                'delete_sarf_band',
            ])->get(),
            'Employees' => Permission::whereIn('name', [
                'view_employees',
                'add_employee',
                'edit_employee',
                'delete_employee',
            ])->get(),
            'Roles' => Permission::whereIn('name', [
                'list_roles',
                'create_role',
                'update_role',
                'delete_role',
            ])->get(),
            'Clients' => Permission::whereIn('name', [
                'list_clients',
                'create_client',
                'update_client',
                'delete_client',
            ])->get(),
            'Masrofat' => Permission::whereIn('name', [
                'list_masrofat',
                'create_masrofat',
                'update_masrofat',
                'delete_masrofat',
            ])->get(),
            'Eradat' => Permission::whereIn('name', [
                'list_eradat',
                'create_eradat',
                'update_eradat',
                'delete_eradat',
            ])->get(),
            'Users' => Permission::whereIn('name', [
                'list_users',
                'create_user',
                'update_user',
                'delete_user',
                'change_user_status',
            ])->get(),
        ];
        // dd($data);
        // return view('dashbord.roles.form', $data);
    }

}
