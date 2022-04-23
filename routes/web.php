<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\DistrictsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\StatusKumuhController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\EvaluasiController;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

Route::prefix('/l-app/')
    ->middleware(['auth', 'role:super-admin'])
    ->group(function () {

        Route::get('dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('seeder', function () {
            Artisan::call('db:seed');
        })->name('seeder');

        // Users
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserController::class);
        Route::resource('kriteria', KriteriaController::class);
        Route::resource('statuskumuh', StatusKumuhController::class);
        Route::resource('subkriteria', SubKriteriaController::class);
        Route::resource('province', ProvinceController::class);
        Route::resource('city', CityController::class);
        Route::resource('district', DistrictsController::class);
        Route::resource('village', VillageController::class);
        Route::resource('evaluasi', EvaluasiController::class);

        Route::post('/live-search', [UserController::class, 'selectSearch']);
    });
