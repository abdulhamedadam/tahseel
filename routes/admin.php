<?php

use App\Http\Controllers\Admin\AccountTransferController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\app_setting\DiscountController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ConfigAppController;
use App\Http\Controllers\Admin\EmployeesController;

use App\Http\Controllers\Admin\FinancialTransactionsController;
use App\Http\Controllers\Admin\GeneralSettingsController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\MasrofatController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\TestsController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Define routes for the "languages" prefix outside the group
Route::prefix('languages')->group(function () {
    // Your routes for the "languages" prefix
});
Route::get('/pre_home', function () {
    return view('welcome');
});
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth:admin']
    ],
    function () {


        Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
            Route::get('/dashboard', function () {
                return view('dashbord.home');
            })->name('dashboard');

            Route::get('/test', function () {
                return ' test admin ';
            });


            /******************************************abdulhamed zaghloul*********************************************/

            Route::get('/Employees', [EmployeesController::class, 'index'])->name('employee_data');
            Route::get('/get_ajax_employee', [EmployeesController::class, 'get_ajax_employee'])->name('get_ajax_employee');
            Route::get('/add_employee', [EmployeesController::class, 'add_employee'])->name('add_employee');
            Route::get('/edit_employee/{id}', [EmployeesController::class, 'edit_employee'])->name('edit_employee');
            Route::post('/update_employee/{id}', [EmployeesController::class, 'update_employee'])->name('update_employee');
            Route::post('/save_employee', [EmployeesController::class, 'save_employee'])->name('save_employee');
            Route::get('/employee_files/{id}', [EmployeesController::class, 'employee_files'])->name('employee_files');
            Route::get('/employee_details/{id}', [EmployeesController::class, 'employee_details'])->name('employee_details');
            Route::get('/employee_masrofat/{id}', [EmployeesController::class, 'employee_masrofat'])->name('employee_masrofat');
            Route::post('/employee_add_masrofat/{id}', [EmployeesController::class, 'employee_add_masrofat'])->name('employee_add_masrofat');
            Route::get('/employee_delete_masrofat/{id}', [EmployeesController::class, 'employee_delete_masrofat'])->name('employee_delete_masrofat');
            Route::get('/employee_revenues/{id}', [EmployeesController::class, 'employee_revenues'])->name('employee_revenues');

            Route::post('/employee_add_files/{id}', [EmployeesController::class, 'employee_add_files'])->name('employee_add_files');
            Route::get('/employee_read_file/{id}', [EmployeesController::class, 'read_file'])->name('employee_read_file');
            Route::get('/employee_download_file/{id}/{file?}', [EmployeesController::class, 'download_file'])->name('employee_download_file');
            Route::get('/employee_delete_file/{id}', [EmployeesController::class, 'delete_file'])->name('employee_delete_file');

            Route::get('/sarf_bands', [GeneralSettingsController::class, 'sarf_bands'])->name('sarf_bands');
            Route::post('/sarf_band/create', [GeneralSettingsController::class, 'add_sarf_band'])->name('add_sarf_band');
            Route::get('/sarf_band/edit/{id}', [GeneralSettingsController::class, 'edit_sarf_band'])->name('edit_sarf_band');
            Route::get('/sarf_band/delete/{id}', [GeneralSettingsController::class, 'delete_sarf_band'])->name('delete_sarf_band');
            Route::get('/get_ajax_sarf_bands', [GeneralSettingsController::class, 'get_ajax_sarf_bands'])->name('get_ajax_sarf_bands');

            Route::get('/subscriptions', [GeneralSettingsController::class, 'subscriptions'])->name('subscriptions');
            Route::post('/subscription/create', [GeneralSettingsController::class, 'add_subscription'])->name('add_subscription');
            Route::get('/subscription/edit/{id}', [GeneralSettingsController::class, 'edit_subscription'])->name('edit_subscription');
            Route::get('/subscription/delete/{id}', [GeneralSettingsController::class, 'delete_subscription'])->name('delete_subscription');
            Route::get('/get_ajax_subscriptions', [GeneralSettingsController::class, 'get_ajax_subscriptions'])->name('get_ajax_subscriptions');

            Route::resource('clients', ClientController::class);
            Route::get('client/delete/{id}', [ClientController::class, 'destroy'])->name('delete_client');
            Route::get('/get_price/{id}', [ClientController::class, 'get_price'])->name('get_price');
            Route::get('/client_unpaid_invoices/{id}', [ClientController::class, 'client_unpaid_invoices'])->name('client_unpaid_invoices');
            Route::get('/client_paid_invoices/{id}', [ClientController::class, 'client_paid_invoices'])->name('client_paid_invoices');
            Route::get('/client_invoices/{id}', [ClientController::class, 'client_invoices'])->name('client_invoices');
            Route::post('/client_add_invoice/{id}', [ClientController::class, 'client_add_invoice'])->name('client_add_invoice');

            Route::resource('roles', RolesController::class);
            Route::get('role/delete/{id}', [RolesController::class, 'destroy'])->name('delete_role');

            Route::resource('users', UsersController::class);
            Route::get('user/delete/{id}', [UsersController::class, 'destroy'])->name('delete_user');
            Route::get('users/change_status/{id}/{status}', [UsersController::class, 'change_status'])->name('change_status');

            Route::resource('masrofat', MasrofatController::class);
            Route::get('masrofat/delete/{id}', [MasrofatController::class, 'destroy'])->name('delete_masrofat');

            Route::resource('invoices', InvoiceController::class)->except(['store', 'create', 'show', 'edit', 'update']);
            Route::get('invoice/delete/{id}', [InvoiceController::class, 'destroy'])->name('delete_invoice');
            Route::post('/invoice/{id}/pay', [InvoiceController::class, 'pay_invoice'])->name('pay_invoice');
            Route::get('/invoice/{id}/details', [InvoiceController::class, 'show_details'])->name('invoice_details');
            Route::get('/invoice/{id}/print', [InvoiceController::class, 'print_invoice'])->name('print_invoice');
            Route::get('/invoice/{id}/redo', [InvoiceController::class, 'redo_invoice'])->name('redo_invoice');
            Route::get('/invoices/due-monthly', [InvoiceController::class, 'dueMonthlyInvoices'])->name('due_monthly_invoices');
            Route::get('/invoices/new', [InvoiceController::class, 'newlyPaidInvoices'])->name('new_paid_invoices');
            Route::post('/invoices/generate', [InvoiceController::class, 'generate'])->name('invoices_generate');

            Route::get('invoices/reports', [ReportController::class, 'reports'])->name('reports.reports');
            Route::post('reports', [ReportController::class, 'index'])->name('reports.index');

            Route::resource('revenues', RevenueController::class);

            Route::get('admin/users/{user}/permissions', [UsersController::class, 'permissions'])->name('users.permissions');
            Route::post('admin/users/{user}/permissions', [UsersController::class, 'updatePermissions'])->name('users.update_permissions');

            Route::get('/notifications/new_clients', [NotificationsController::class, 'new_clients'])->name('new_clients_notifications');
            Route::get('/get_ajax_notifications/new_clients', [NotificationsController::class, 'get_ajax_notifications'])->name('get_ajax_notifications');
            Route::get('/notifications/unpaid_invoices', [NotificationsController::class, 'unpaid_invoices'])->name('unpaid_invoices_notifications');
            Route::get('/get_ajax_invoice_notifications', [NotificationsController::class, 'get_ajax_invoice_notifications'])->name('get_ajax_invoice_notifications');
            Route::get('/notifications/invoices', [NotificationsController::class, 'invoices'])->name('invoices_process_notifications');
            Route::get('/get_ajax_invoices_notifications', [NotificationsController::class, 'get_ajax_invoices_notifications'])->name('get_ajax_invoices_notifications');
            Route::get('/notifications/transfers', [NotificationsController::class, 'transfers'])->name('transfers_notifications');
            Route::get('/get_ajax_transfers_notifications', [NotificationsController::class, 'get_ajax_transfers_notifications'])->name('get_ajax_transfers_notifications');
            Route::get('/notifications/read/{id}', [NotificationsController::class, 'mark_notification_read'])->name('mark_notification_read');


            Route::get('/accounts', [AccountController::class, 'accounts'])->name('accounts');
            Route::post('/account/create', [AccountController::class, 'add_account'])->name('add_account');
            Route::get('/edit/account/{id}', [AccountController::class, 'edit_account'])->name('edit_account');
            Route::get('delete/account/{id}', [AccountController::class, 'destroy'])->name('delete_account');
            Route::get('/get_ajax_accounts', [AccountController::class, 'get_ajax_accounts'])->name('get_ajax_accounts');
            Route::get('/accounts/{id}/transactions', [AccountController::class, 'get_transactions'])->name('accounts_transactions');

            Route::get('account-settings', [AccountController::class, 'account_setting'])->name('account_settings');
            Route::post('save-account-settings', [AccountController::class, 'save_account_setting'])->name('save_account_setting');

            Route::get('financial-transactions', [FinancialTransactionsController::class, 'index'])->name('financial_transactions.index');
            Route::get('/account-balance/{id}', [FinancialTransactionsController::class, 'getAccountBalance'])->name('get_account_balance');


            Route::get('/account_transfers', [AccountTransferController::class, 'account_transfers'])->name('account_transfers');
            Route::post('/account_transfers/create', [AccountTransferController::class, 'add_account_transfer'])->name('add_account_transfer');
            Route::get('/edit/account_transfers/{id}', [AccountTransferController::class, 'edit_account_transfer'])->name('edit_account_transfer');
            Route::get('delete/account_transfers/{id}', [AccountTransferController::class, 'destroy'])->name('delete_account_transfer');
            Route::get('/get_ajax_account_transfers', [AccountTransferController::class, 'get_ajax_account_transfers'])->name('get_ajax_account_transfers');
            Route::post('redo_account_transfer/{id}', [AccountTransferController::class, 'redo_account_transfer'])->name('redo_account_transfer');


            /*************************************************************************************************/
            Route::get('setting/app_config', [ConfigAppController::class, 'index'])->name('app_config');
            Route::post('setting/app_config/save', [ConfigAppController::class, 'store'])->name('save_app_config');

        });
    }
);


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

        Route::get('/invoice/{id}/print', [InvoiceController::class, 'print_invoice'])->name('print_invoice');


        require __DIR__ . '/adminauth.php';
    }
);

Route::get('/run-migrate', function () {
    try {
        Artisan::call('migrate', [
            '--force' => true
        ]);

        return response()->json([
            'message' => 'Migration completed successfully.',
            'output' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Migration failed: ' . $e->getMessage()
        ], 500);
    }
})->middleware('auth');

Route::get('/run-specific-migrate/{file}', function ($file) {
    try {
        $path = "database/migrations/{$file}";

        if (!file_exists(base_path($path))) {
            return response()->json([
                'message' => 'الملف غير موجود.',
                'path' => $path
            ], 404);
        }

        Artisan::call('migrate', [
            '--path' => $path,
            '--force' => true
        ]);

        return response()->json([
            'message' => 'تم التنفيذ بنجاح.',
            'output' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'حدث خطأ ما: ' . $e->getMessage()
        ], 500);
    }
})->middleware('auth');
