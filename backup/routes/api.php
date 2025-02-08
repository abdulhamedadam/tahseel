<?php


use App\Http\Controllers\Api\AppDataController;
use App\Http\Controllers\Api\UsersApiController;
use Illuminate\Support\Facades\Route;


/*----------------------app_data-------------------------*/

Route::post('/contact_message', [AppDataController::class, 'contact_message']);
Route::get('/data_app', [AppDataController::class, 'data_app']);
Route::get('/terms', [AppDataController::class, 'terms']);
Route::get('/programs', [AppDataController::class, 'programs']);
Route::get('/homePage', [AppDataController::class, 'homePage']);
Route::get('/get_Subscription', [Settings::class, 'get_Subscription']);

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
    });

});



Route::group(['prefix' => 'trainer'], function ($router) {
    Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {

    });

});
