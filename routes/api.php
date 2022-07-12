<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserAuthController;

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

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('login', [UserAuthController::class, 'login']);
    Route::get('auth/user', [UserAuthController::class, 'user']);
    Route::post('auth/refresh', [UserAuthController::class, 'refresh']);
    Route::post('auth/logout', [UserAuthController::class, 'logout']);
});
