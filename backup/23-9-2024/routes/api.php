<?php


use App\Http\Controllers\Api\AppDataController;
use App\Http\Controllers\Api\Member\OprationController;
use App\Http\Controllers\Api\Member\UsersApiController;
use App\Http\Controllers\Api\Settings;
use App\Http\Controllers\Api\Trainers\ScheduleController;
use App\Http\Controllers\Api\Trainers\TrainersController;
use Illuminate\Support\Facades\Route;


/*----------------------app_data-------------------------*/

Route::post('/contact_message', [AppDataController::class, 'contact_message']);
Route::get('/data_app', [AppDataController::class, 'data_app']);
Route::get('/terms', [AppDataController::class, 'terms']);
Route::get('/programs', [AppDataController::class, 'programs']);
Route::get('/homePage', [AppDataController::class, 'homePage']);
Route::get('/get_Subscription', [Settings::class, 'get_Subscription']);

Route::group(['prefix' => 'setting'], function ($router) {
    Route::post('get_setting', [Settings::class, 'get_setting']);
    Route::get('/data_app', [AppDataController::class, 'data_app_local']);
    Route::get('/terms', [AppDataController::class, 'terms_local']);
    Route::get('list_category', [Settings::class, 'list_category']);
    Route::post('SubscriptionByCatogry', [Settings::class, 'get_SubscriptionByCatogry']);
});


Route::group(['prefix' => 'member'], function ($router) {
    Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {

        Route::post('/login', [UsersApiController::class, 'login_user'])->name('login');
        Route::post('/add_user', [UsersApiController::class, 'store']);
        Route::post('/checkExiteUser', [UsersApiController::class, 'checkExiteUser']);
        Route::post('/checkExiteUsername', [UsersApiController::class, 'checkExiteUsername']);
        Route::post('/checkExitePhone', [UsersApiController::class, 'checkExitePhone']);
        Route::post('/checkExiteEmail', [UsersApiController::class, 'checkExiteEmail']);
        Route::post('/checkExitePlateNumber', [UsersApiController::class, 'checkExitePlateNumber']);
        Route::get('/createNewToken', [UsersApiController::class, 'createNewToken']);
        Route::post('/logout', [UsersApiController::class, 'logout']);

        Route::post('/sendCodePhone', [UsersApiController::class, 'sendCodePhone']);
        Route::post('/checkExiteCode', [UsersApiController::class, 'checkExiteCode']);
        Route::post('/update_password', [UsersApiController::class, 'update_password']);


        Route::group(['middleware' => 'jwt'], function ($router) {
            Route::post('/update_user', [UsersApiController::class, 'update']);
            Route::post('/update_lang', [UsersApiController::class, 'update_lang']);
            Route::get('/get_user', [UsersApiController::class, 'show']);
            Route::post('/refreshToken', [UsersApiController::class, 'refreshToken']);
            Route::get('/user-profile', [UsersApiController::class, 'userProfile']);


        });

        Route::get('/list_transportation', [OprationController::class, 'list_transportation']);
        Route::get('/list_exercise', [OprationController::class, 'list_exercise']);
        Route::get('/one_exercise', [OprationController::class, 'one_exercise']);


        Route::get('/list_schedules', [OprationController::class, 'list_schedules']);
        Route::get('/list_schedules_day', [OprationController::class, 'list_schedules_day']);


    });

});



Route::group(['prefix' => 'trainer'], function ($router) {
    Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {

        Route::post('/login', [TrainersController::class, 'login_user'])->name('login');
        Route::get('/createNewToken', [TrainersController::class, 'createNewToken']);
        Route::post('/logout', [TrainersController::class, 'logout']);

        Route::post('/sendCodePhone', [TrainersController::class, 'sendCodePhone']);
        Route::post('/checkExiteCode', [TrainersController::class, 'checkExiteCode']);
        Route::post('/update_password', [TrainersController::class, 'update_password']);


        Route::group(['middleware' => 'jwt'], function ($router) {
            Route::post('/update_user', [TrainersController::class, 'update']);
            Route::post('/update_lang', [TrainersController::class, 'update_lang']);
            Route::get('/get_user', [TrainersController::class, 'show']);
            Route::post('/refreshToken', [TrainersController::class, 'refreshToken']);
            Route::get('/user-profile', [TrainersController::class, 'userProfile']);


        });

        Route::get('/list_schedules', [ScheduleController::class, 'list_schedules']);
        Route::get('/list_schedules_day', [ScheduleController::class, 'list_schedules_day']);
        Route::get('/one_schedules', [ScheduleController::class, 'one_schedules']);
        Route::post('update_schedules', [ScheduleController::class, 'update_schedules']);


    });


});
