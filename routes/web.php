<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubadminController;

   


Route::get('/clear-cache', function() {

    Artisan::call('cache:clear');

    return "Cache is cleared";

});


Auth::routes();


Route::get('/', function () {
    return view('welcome');
});


Route::get('forgot_password', [ForgotController::class,'index']);
Route::post('send/forgot_password', [ForgotController::class,'send_forgot_password']);


Route::group(['middleware' => ['web','auth']], function () {
    Route::get('home', [HomeController::class,'index']);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('subadmin', SubadminController::class);
    Route::post('subadmin/{id}/destroy', [SubadminController::class,'destroy']);
    Route::resource('settings', SettingsController::class);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
