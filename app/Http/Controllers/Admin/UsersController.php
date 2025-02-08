<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin;
use App\Models\Admin\Employee;
use App\Services\AdminUserService;
use App\Traits\ImageProcessing;
use App\Traits\ValidationMessage;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    use ImageProcessing;
    use ValidationMessage;

    protected $AdminUsersRepository;
    protected $employeesRepository;
    protected $rolesRepository;
    protected $permissionsRepository;
    protected $adminUserService;

    public function __construct(BasicRepositoryInterface $basicRepository, AdminUserService $adminUserService)
    {
        $this->AdminUsersRepository     = createRepository($basicRepository, new Admin());
        $this->employeesRepository   = createRepository($basicRepository, new Employee());
        $this->rolesRepository   = createRepository($basicRepository, new Role());
        $this->adminUserService   = $adminUserService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $allData = Admin::with(['roles', 'employee'])->get();
            return DataTables::of($allData)
                ->editColumn('name', function ($row) {
                    return $row->name ?? 'N/A';
                })
                ->editColumn('email', function ($row) {
                    return $row->email ?? 'N/A';
                })
                ->editColumn('role', function ($row) {
                    return $row->roles->isNotEmpty() ? $row->roles->first()->getTranslation('title', app()->getLocale()) : 'N/A';
                })
                ->editColumn('position', function ($row) {
                    return $row->position ?? 'N/A';
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == '1') {
                        $title_approved = trans('users.active');
                        $class_approved = 'success';
                        $icon_approved = '<i class="bi bi-check-circle-fill"></i>';
                    } else {
                        $title_approved = trans('users.not_active');
                        $class_approved = 'danger';
                        $icon_approved = '<i class="bi bi-x-circle-fill"></i>';
                    }
                    return '<a href="'.route('admin.change_status', [$row->id, $row->status]).'" class="btn btn-'.$class_approved.' btn-sm" onclick="return confirm(\''.trans('users.change_type_msg').'\');">'.$icon_approved.' ' . $title_approved . '</a>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group btn-group-sm">
                            <a href="' . route('admin.users.edit', $row->id) . '" class="btn btn-sm btn-primary" title="' . trans('users.edit') . '" style="font-size: 16px;">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a onclick="return confirm(\'Are You Sure To Delete?\')"  href="' . route('admin.delete_user', $row->id) . '"  class="btn btn-sm btn-danger" title="' . trans('users.delete') . '" style="font-size: 16px;" onclick="return confirm(\'' . trans('users.confirm_delete') . '\')">
                                <i class="bi bi-trash3"></i>
                            </a>
                            </div>
                            ';
                            // <a href="' . route('admin.users.permissions', $row->id) . '" class="btn btn-sm btn-info" title="' . trans('users.set_permissions') . '" style="font-size: 16px;">
                            //     <i class="bi bi-lock"></i>
                            // </a>
                })
                ->rawColumns(['name', 'action', 'status', 'role'])
                ->make(true);
        }
        return view('dashbord.users.index');
    }

    /********************************************/
    public function create()
    {
        $data['roles']      = $this->rolesRepository->getAll();
        $data['employees']  = $this->employeesRepository->getAll();
        // dd($data);
        return view('dashbord.users.form', $data);
    }

    /********************************************/
    public function store(AdminStoreRequest $request)
    {
        try {
            // dd($request->all());
            $this->adminUserService->store($request);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.users.index');
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
        $data['admin']   = $this->AdminUsersRepository->getById($id);
        $data['roles']      = $this->rolesRepository->getAll();
        $data['employees']  = $this->employeesRepository->getAll();
        return view('dashbord.users.edit', $data);
    }

    /********************************************/
    public function update(AdminUpdateRequest $request, string $id)
    {
        try {
            // dd($request->all());
            $this->adminUserService->update($request,$id);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /********************************************/
    public function destroy(string $id)
    {
        try {
            $admin = $this->AdminUsersRepository->getById($id);
            if ($admin->image) {
                $oldImagePath = public_path('images/' . $admin->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $this->AdminUsersRepository->delete($id);
            toastr()->addSuccess(trans('forms.success'));
            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    /********************************************/

    public function change_status($id, $status)
    {
        try {
            $admin_user =$this->AdminUsersRepository->getById($id);
            if($admin_user)
            {
                if($status=='1')
                {
                    $data['status']='0';
                }elseif($status=='0'){
                    $data['status']='1';
                }
                $this->AdminUsersRepository->update($id,$data);
                toastr()->addSuccess(trans('users.status_changed_successfully'));
                return redirect()->route('admin.users.index');
            }
                return redirect()->route('admin.users.index');

        } catch (\Exception $e) {
            test($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function permissions($id)
    {
        // $data['permissions'] = $this->permissionsRepository->getAll();
        $data['admin'] = $this->AdminUsersRepository->getById($id);
        $data['sections'] = [
            'Dashboard' => Permission::whereIn('name',  ['view_dashboard'])->get(),
            'Branches' => Permission::whereIn('name', [
                'view_branches',
                'add_branch',
                'edit_branch',
                'delete_branch',
            ])->get(),
            'Governorates' => Permission::whereIn('name', [
                'view_governorates',
                'add_governorate',
                'edit_governorate',
                'delete_governorate',
            ])->get(),
            'Areas' => Permission::whereIn('name', [
                'view_areas',
                'add_area',
                'edit_area',
                'delete_area',
            ])->get(),
            'Site Data' => Permission::whereIn('name', [
                'view_site_data',
                'add_site_data',
                'edit_site_data',
            ])->get(),
            'Employees' => Permission::whereIn('name', [
                'view_employees',
                'add_employee',
                'edit_employee',
                'delete_employee',
            ])->get(),
            'Clients' => Permission::whereIn('name', [
                'list_clients',
                'create_client',
                'update_client',
                'delete_client',
            ])->get(),
            'Client Companies' => Permission::whereIn('name', [
                'view_client_companies',
                'add_client_company',
                'edit_client_company',
                'update_client_company',
                'delete_client_company',
            ])->get(),
            'Client Projects' => Permission::whereIn('name', [
                'view_client_projects',
                'add_client_project',
                'edit_client_project',
                'update_client_project',
                'delete_client_project',
            ])->get(),
            'Companies' => Permission::whereIn('name', [
                'list_companies',
                'create_company',
                'update_company',
                'delete_company',
            ])->get(),
            'Company Projects' => Permission::whereIn('name', [
                'view_company_projects',
                'add_company_project',
                'edit_company_project',
                'update_company_project',
                'delete_company_project',
            ])->get(),
            'Projects' => Permission::whereIn('name', [
                'list_projects',
                'create_project',
                'update_project',
                'delete_project',
            ])->get(),
            'Masrofat' => Permission::whereIn('name', [
                'list_masrofat',
                'create_masrofat',
                'update_masrofat',
                'delete_masrofat',
            ])->get(),
            'Tests' => Permission::whereIn('name', [
                'list_tests',
                'create_test',
                'update_test',
                'delete_test',
            ])->get(),
            'Users' => Permission::whereIn('name', [
                'list_users',
                'create_user',
                'update_user',
                'delete_user',
                'change_user_status',
                'manage_user_permissions',
            ])->get(),
        ];
        // dd($data);
        return view('dashbord.users.permissions.form', $data);
    }

    public function updatePermissions(Request $request, $id)
    {
        $validatedData = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        // dd($validatedData);
        $admin = $this->AdminUsersRepository->getById($id);
        $admin->syncPermissions($request->permissions ?? []);

        toastr()->addSuccess(trans('users.permissions_updated_successfully.'));
        return redirect()->route('admin.users.index');
    }
}
