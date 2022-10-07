<?php

use App\Http\Controllers\api\v1\ArsipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\UserAuthController;
use App\Http\Controllers\api\v1\EvaluasiController;
use App\Http\Controllers\api\v1\LogController;

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
        Route::post('list', [EvaluasiController::class, 'index']);
        Route::post('', [EvaluasiController::class, 'store']);
        Route::get('create', [EvaluasiController::class, 'create']);
        Route::post('edit', [EvaluasiController::class, 'edit']);
        Route::post('update', [EvaluasiController::class, 'update']);
        Route::post('delete', [EvaluasiController::class, 'delete']);
        Route::post('show', [EvaluasiController::class, 'show']);
        Route::post('show/kriteria', [EvaluasiController::class, 'showKriteria']);
        Route::post('show/edit', [EvaluasiController::class, 'editDetailKriteria']);
        Route::post('show/update', [EvaluasiController::class, 'updateDetailKriteria']);
        Route::post('pembaruan/store', [EvaluasiController::class, 'storePembaruan']);
        Route::post('pembaruan/update', [EvaluasiController::class, 'updatePembaruan']);
        Route::post('pembaruan/create', [EvaluasiController::class, 'createPembaruan']);
        Route::post('search-village', [EvaluasiController::class, 'filterVillage']);
        Route::post('create/kriteria', [EvaluasiController::class, 'createKriteria']);
        Route::post('update/kriteria', [EvaluasiController::class, 'updateKriteria']);
        Route::post('update/status', [EvaluasiController::class, 'updateStatus']);
    });

    Route::group(['prefix' => 'arsip/'], function () {
        Route::post('', [ArsipController::class, 'index']);
        Route::post('show', [ArsipController::class, 'show']);
        Route::post('show/kriteria', [ArsipController::class, 'showKriteria']);
    });

    Route::group(['prefix' => 'log/'], function () {
        Route::post('', [LogController::class, 'index']);
    });
});

// Route::post('evaluasi/show', [EvaluasiController::class, 'show']);
