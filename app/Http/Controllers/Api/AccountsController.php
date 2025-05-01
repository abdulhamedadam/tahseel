<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Interfaces\BasicRepositoryInterface;
use App\Models\Admin\Account;
use App\Models\Admin\AccountSettings;
use App\Services\AccountService;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    use ResponseApi;
    protected $accountsRepository;

    public function __construct(BasicRepositoryInterface $basicRepository)
    {
        $this->accountsRepository = createRepository($basicRepository, new Account());
    }
    public function index()
    {
        try {
            $accounts = $this->accountsRepository->getAll();

            $accounts = $accounts->isNotEmpty() ? $accounts->toQuery()
                ->whereNull('parent_id')
                ->whereNull('deleted_at')
                ->with(['children' => function ($query) {
                    $query->withSum('financialTransactions', 'amount');
                }])
                ->withSum('financialTransactions', 'amount')
                ->get() : collect();

            return AccountResource::collection($accounts);
        } catch (\Exception $e) {
            return $this->responseApiError('حدث خطأ ما.');
        }
    }

    public function collectors1()
    {
        try {
            $settings = AccountSettings::first();

            if (!$settings || !$settings->employee_account_id) {
                return $this->responseApiError('لم يتم العثور على حساب الموظفين في الإعدادات.');
            }

            $employeeAccountId = $settings->employee_account_id;

            $accounts = $this->accountsRepository->getAll();

            $accounts = $accounts->isNotEmpty() ? $accounts->toQuery()
                ->where('parent_id', $employeeAccountId)
                ->whereNull('deleted_at')
                ->with(['children' => function ($query) {
                    $query->withSum('financialTransactions', 'amount');
                }])
                ->withSum('financialTransactions', 'amount')
                ->get() : collect();

            return AccountResource::collection($accounts);

        } catch (\Exception $e) {
            return $this->responseApiError('حدث خطأ ما.');
        }
    }

    public function collectors(Request $request)
    {
        try {
            $settings = AccountSettings::first();

            if (!$settings || !$settings->employee_account_id) {
                return $this->responseApiError('لم يتم العثور على حساب الموظفين في الإعدادات.');
            }

            $employeeAccountId = $settings->employee_account_id;

            $accounts = $this->accountsRepository->getAll();

            $query = $accounts->isNotEmpty() ? $accounts->toQuery()
                ->where('parent_id', $employeeAccountId)
                ->whereNull('deleted_at')
                ->with(['children' => function ($query) {
                    $query->withSum('financialTransactions', 'amount');
                }])
                ->withSum('financialTransactions', 'amount') : null;

            if (!$query) {
                return AccountResource::collection(collect());
            }

            if ($request->has('search') && $request->search != '') {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $accounts = $query->get();

            return AccountResource::collection($accounts);

        } catch (\Exception $e) {
            return $this->responseApiError('حدث خطأ ما.');
        }
    }
}
