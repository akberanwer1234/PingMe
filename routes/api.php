<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UpdateUserApiController;
use App\Http\Controllers\PsngrRideController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidateTokenController;
// use App\Models\Payment;

// use App\Http\Controllers\NotificationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('notify', [NotificationController::class, 'notify']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    
});

Route::group(['middleware' => 'auth:api'], function (){
      // Route::post('login_user', [UserController::class, 'login_user']);
    
});



Route::post('register_user', [UserController::class, 'register_user']);

Route::post('login_user', [UserController::class, 'login_user']);

Route::post('logout_user', [UserController::class, 'logout_user']);

Route::post('nearby_locations', [UserController::class, 'nearby_locations']);

Route::post('add_safe_location', [UserController::class, 'add_safe_location']);

Route::post('fetch_safe_locations', [UserController::class, 'fetch_safe_locations']);

Route::post('check_excluded_area', [UserController::class, 'check_excluded_area']); // sb se pele run hogi

//details confirm hone k bad isko shertee

Route::post('vehicle_information', [UserController::class, 'vehicle_information']); //not solid

//add vehicle detail
//update profile
//update vehicle