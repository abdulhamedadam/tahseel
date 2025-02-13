<?php

use App\Http\Controllers\Admin\app_setting\DiscountController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\EmployeesController;

use App\Http\Controllers\Admin\GeneralSettingsController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\MasrofatController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\TestsController;
use App\Http\Controllers\Admin\UsersController;
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
    ], function () {


    Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::get('/dashboard', function () {
            return view('dashbord.home');
        })->name('dashboard')->middleware('can:view_dashboard');

        Route::get('/test', function () {
            return ' test admin ';
        });


        /********************************************************************************************************************************/
        // Route::get('clients/{id}/companies',[ClientController::class,'companies'])->name('client_companies');
        // Route::post('clients/{id}/companies/save',[ClientController::class,'store_company'])->name('client_store_company');
        // Route::get('clients/companies/edit/{id}',[ClientController::class,'edit_company'])->name('client_edit_company');
        // Route::post('clients/companies/update/{id}',[ClientController::class,'update_company'])->name('client_update_company');
        // Route::get('clients/companies/delete/{id}',[ClientController::class,'delete_company'])->name('client_delete_company');
        /********************************************************************************************************************************/
        // Route::get('clients/{id}/projects',[ClientController::class,'projects'])->name('client_projects');
        // Route::post('clients/{id}/projects/save',[ClientController::class,'store_project'])->name('client_store_project');
        // Route::get('clients/projects/edit/{id}',[ClientController::class,'edit_project'])->name('client_edit_project');
        // Route::post('clients/projects/update/{id}',[ClientController::class,'update_project'])->name('client_update_project');
        // Route::get('clients/projects/delete/{id}',[ClientController::class,'delete_project'])->name('client_delete_project');
        // /********************************************************************************************************************************/
        // Route::resource('company',CompanyController::class);
        // Route::get('company/delete/{id}',[CompanyController::class,'destroy'])->name('delete_company');
        // /********************************************************************************************************************************/
        // Route::get('company/{id}/projects',[CompanyController::class,'projects'])->name('company_projects');
        // Route::post('company/{id}/projects/save',[CompanyController::class,'store_project'])->name('company_store_project');
        // Route::get('company/projects/edit/{id}',[CompanyController::class,'edit_project'])->name('company_edit_project');
        // Route::post('company/projects/update/{id}',[CompanyController::class,'update_project'])->name('company_update_project');
        // Route::get('company/projects/delete/{id}',[CompanyController::class,'delete_project'])->name('company_delete_project');
        // /********************************************************************************************************************************/
        // Route::resource('project',ProjectController::class);
        // Route::get('project/delete/{id}',[ProjectController::class,'destroy'])->name('delete_project');
        // Route::get('get_company/{id}',[ProjectController::class,'get_company'])->name('get_company');
        /********************************************************************************************************************************/
        /************************** MAINDATA *****************************/
        // Route::resource('mdata', MaindataController::class);
        /************************** About *****************************/
        // Route::resource('about', AboutController::class);
        // Route::get('about/show_load/{id}', [AboutController::class, 'show_load'])->name('about.load_details');

        /******************************************************************************************************** */
        // Route::group(['prefix' => 'app_setting', 'as' => 'app_setting.'], function () {
        // Route::resource('Notification', NotificationController::class);
        // Route::get('Notification/show_load/{id}', [NotificationController::class, 'show_load'])->name('Notification.load_details');
        // Route::resource('Discount', DiscountController::class);

        // });
        //************************************** Complaints ********************************************** */





        /******************************************abdulhamed zaghloul*********************************************/

        // Route::get('/branches', [GeneralSettingsController::class, 'branches'])->name('branches');
        // Route::post('/branch/create', [GeneralSettingsController::class, 'add_branch'])->name('add_branch');
        // Route::get('/branch/edit/{id}', [GeneralSettingsController::class, 'edit_branch'])->name('edit_branch');
        // Route::get('/branch/delete/{id}', [GeneralSettingsController::class, 'delete_branch'])->name('delete_branch');
        // Route::get('/get_ajax_branches', [GeneralSettingsController::class, 'get_ajax_branches'])->name('get_ajax_branches');

        // Route::get('/governorates', [GeneralSettingsController::class, 'governorates'])->name('governorates');
        // Route::post('/governorate/create', [GeneralSettingsController::class, 'add_governorate'])->name('add_governorate');
        // Route::get('/governorate/edit/{id}', [GeneralSettingsController::class, 'edit_governorate'])->name('edit_governorate');
        // Route::get('/governorate/delete/{id}', [GeneralSettingsController::class, 'delete_governorate'])->name('delete_governorate');
        // Route::get('/get_ajax_governorates', [GeneralSettingsController::class, 'get_ajax_governorates'])->name('get_ajax_governorates');
        // Route::get('/get_ajax_branches', [GeneralSettingsController::class, 'get_ajax_branches'])->name('get_ajax_branches');

        // Route::get('/areas', [GeneralSettingsController::class, 'areas'])->name('areas');
        // Route::post('/area/create', [GeneralSettingsController::class, 'add_area'])->name('add_area');
        // Route::get('/area/edit/{id}', [GeneralSettingsController::class, 'edit_area'])->name('edit_area');
        // Route::get('/area/delete/{id}', [GeneralSettingsController::class, 'delete_area'])->name('delete_area');
        // Route::get('/get_ajax_areas', [GeneralSettingsController::class, 'get_ajax_areas'])->name('get_ajax_areas');

        // Route::get('/site_data', [GeneralSettingsController::class, 'siteData'])->name('siteData');
        // Route::get('/site_data/edit/{id}', [GeneralSettingsController::class, 'edit_siteData'])->name('edit_siteData');
        // Route::post('/site_data/create', [GeneralSettingsController::class, 'save_siteData'])->name('save_siteData');
        // Route::get('/get_ajax_siteData', [GeneralSettingsController::class, 'get_ajax_siteData'])->name('get_ajax_siteData');

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
        Route::get('/employee_download_file/{id}/{file?}', [EmployeesController::class,'download_file'])->name('employee_download_file');
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

        Route::resource('clients',ClientController::class);
        Route::get('client/delete/{id}',[ClientController::class,'destroy'])->name('delete_client');
        Route::get('/get_price/{id}', [ClientController::class, 'get_price'])->name('get_price');
        Route::get('/client_unpaid_invoices/{id}', [ClientController::class, 'client_unpaid_invoices'])->name('client_unpaid_invoices');
        Route::get('/client_paid_invoices/{id}', [ClientController::class, 'client_paid_invoices'])->name('client_paid_invoices');
        Route::get('/client_invoices/{id}', [ClientController::class, 'client_invoices'])->name('client_invoices');
        Route::post('/client_add_invoice/{id}', [ClientController::class, 'client_add_invoice'])->name('client_add_invoice');

        Route::resource('roles',RolesController::class);
        Route::get('role/delete/{id}',[RolesController::class,'destroy'])->name('delete_role');

        Route::resource('users',UsersController::class);
        Route::get('user/delete/{id}',[UsersController::class,'destroy'])->name('delete_user');
        Route::get('users/change_status/{id}/{status}', [UsersController::class, 'change_status'])->name('change_status');

        Route::resource('masrofat',MasrofatController::class);
        Route::get('masrofat/delete/{id}',[MasrofatController::class,'destroy'])->name('delete_masrofat');

        Route::resource('invoices',InvoiceController::class)->except(['store', 'create', 'show', 'edit', 'update']);
        Route::get('invoice/delete/{id}',[InvoiceController::class,'destroy'])->name('delete_invoice');
        Route::post('/invoice/{id}/pay', [InvoiceController::class, 'pay_invoice'])->name('pay_invoice');
        Route::get('/invoice/{id}/details', [InvoiceController::class, 'show_details'])->name('invoice_details');
        Route::get('/invoice/{id}/print', [InvoiceController::class, 'print_invoice'])->name('print_invoice');
        Route::get('/invoice/{id}/redo', [InvoiceController::class, 'redo_invoice'])->name('redo_invoice');
        Route::get('/invoices/due-monthly', [InvoiceController::class, 'dueMonthlyInvoices'])->name('due_monthly_invoices');
        Route::get('/invoices/new', [InvoiceController::class, 'newlyPaidInvoices'])->name('new_paid_invoices');

        Route::resource('revenues',RevenueController::class);

        Route::get('admin/users/{user}/permissions', [UsersController::class, 'permissions'])->name('users.permissions');
        Route::post('admin/users/{user}/permissions', [UsersController::class, 'updatePermissions'])->name('users.update_permissions');

        Route::get('/notifications/new_clients', [NotificationsController::class, 'new_clients'])->name('new_clients_notifications');
        Route::get('/get_ajax_notifications/new_clients', [NotificationsController::class, 'get_ajax_notifications'])->name('get_ajax_notifications');
        Route::get('/notifications/unpaid_invoices', [NotificationsController::class, 'unpaid_invoices'])->name('unpaid_invoices_notifications');
        Route::get('/get_ajax_invoice_notifications', [NotificationsController::class, 'get_ajax_invoice_notifications'])->name('get_ajax_invoice_notifications');
        Route::get('/notifications/read/{id}', [NotificationsController::class, 'mark_notification_read'])->name('mark_notification_read');
    });


});


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {



    require __DIR__ . '/adminauth.php';
});
