<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserAuthController;
use App\Http\Controllers\api\v1\EvaluasiController;

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

Route::post('login', [UserAuthController::class, 'login']);

Route::group(['middleware' => 'auth.api'], function ($router) {
    Route::get('auth/user', [UserAuthController::class, 'user']);
    Route::post('auth/refresh', [UserAuthController::class, 'refresh']);
    Route::post('auth/logout', [UserAuthController::class, 'logout']);
    Route::post('upload', [UserAuthController::class, 'cekUpload']);

    Route::group(['prefix' => 'evaluasi/'], function () {
        Route::get('', [EvaluasiController::class, 'index']);
        Route::get('create', [EvaluasiController::class, 'create']);
        Route::post('search-village', [EvaluasiController::class, 'filterVillage']);
        Route::post('create/kriteria', [EvaluasiController::class, 'createKriteria']);
    });
});
