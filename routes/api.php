<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\AdminResetPassword;
use App\Http\Controllers\Auth\UserResetPassword;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/register', [AdminController::class, 'adminregister'])->name('user.index');
    Route::post('/login', [AdminController::class, 'adminlogin'])->name('user.index');
    Route::post('/logout', [AdminController::class, 'logout'])->name('user.index');
    Route::post('/me', [AdminController::class, 'me'])->name('user.index');
});
Route::prefix('admin')->controller(UserController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('refresh', 'refresh');
    Route::post('changepassword', 'changepassword');
    Route::post('register', 'register');
    Route::delete('delete/{id}', 'delete');
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'me');

    });
});
Route::prefix('admin')->group(function () {
    Route::post('send-reset-password-email', [UserResetPassword::class, 'send_reset_password_email']);
    Route::post('reset-password/{token}', [UserResetPassword::class, 'reset']);
});
