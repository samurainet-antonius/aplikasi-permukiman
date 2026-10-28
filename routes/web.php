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
use App\Http\Controllers\StatusKriteriaController;
use App\Http\Controllers\VillageController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeafletController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SiteSettingController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LeafletController::class, 'index'])->name('home');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/village', [LeafletController::class, 'village'])->name('village.home');
Route::post('/select-village', [LeafletController::class, 'formVillage'])->name('village.select');
Route::post('/search-village', [LeafletController::class, 'selectVillage'])->name('village.search');
Route::get('/detail/{id}', [LeafletController::class, 'detail'])->name('detail.public');

require __DIR__.'/auth.php';

Route::prefix('/l-app/')
    ->middleware('auth')
    ->group(function () {

        // Route::get('dashboard', function () {
        //     return view('dashboard');
        // })->name('dashboard');

        Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
        Route::get('dashboard/{district_code}/{village_code}/{month}/{years}',[DashboardController::class,'detail'])->name('dashboard.detail');

        Route::get('city/province',[CityController::class,'city'])->name('city-province');
        Route::get('district/city',[DistrictsController::class,'district'])->name('district-city');
        Route::get('village/district',[VillageController::class,'village'])->name('village-district');

        Route::get('profil',[UserController::class,'profil'])->name('profil');
        Route::get('setting/profil',[UserController::class,'profilChange'])->name('setting-profil');
        Route::post('setting/profil',[UserController::class,'updateProfile']);
        Route::get('setting/password',[UserController::class,'setting'])->name('setting');
        Route::post('setting/password',[UserController::class,'settingPassword']);

        // Users
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserController::class);
        Route::resource('kriteria', KriteriaController::class);
        Route::resource('statuskumuh', StatusKumuhController::class);
        Route::resource('statuskriteria', StatusKriteriaController::class);
        Route::resource('subkriteria', SubKriteriaController::class);
        Route::resource('province', ProvinceController::class);
        Route::resource('city', CityController::class);
        Route::resource('district', DistrictsController::class);
        Route::resource('village', VillageController::class);
        Route::resource('evaluasi', EvaluasiController::class);
        Route::resource('arsip', ArsipController::class);
        Route::resource('staff', EmployeeController::class);
        Route::resource('log', LogController::class);

        Route::get('/setting', [SiteSettingController::class, 'index'])->name('setting.index');
        Route::put('/setting', [SiteSettingController::class, 'update'])->name('setting.update');

        Route::get('evaluasi/delete/file/{id}',[EvaluasiController::class, 'destroyGambar'])->name('evaluasi.delete.file');

        Route::get('evaluasi/{evaluasi_id}/kriteria/{page}',[EvaluasiController::class, 'kriteriaCreate'])->name('evaluasi.create.kriteria');
        Route::post('evaluasi/{evaluasi_id}/kriteria/{page}',[EvaluasiController::class, 'kriteriaStore'])->name('evaluasi.store.kriteria');
        Route::get('evaluasi/{evaluasi_id}/kriteria/{page}/{id}',[EvaluasiController::class, 'destroyFotoEvaluasiCreate'])->name('evaluasi.delete.foto.create');

        Route::get('evaluasi/{evaluasi_id}/edit/kriteria/{page}',[EvaluasiController::class, 'kriteriaEdit'])->name('evaluasi.edit.kriteria');
        Route::post('evaluasi/{evaluasi_id}/edit/kriteria/{page}',[EvaluasiController::class, 'kriteriaUpdate'])->name('evaluasi.update.kriteria');
        Route::get('evaluasi/{evaluasi_id}/edit/kriteria/{page}/{id}', [EvaluasiController::class, 'destroyFotoEvaluasiEdit'])->name('evaluasi.delete.foto.edit');

        Route::post('evaluasi/{evaluasi_id}/edit',[EvaluasiController::class, 'kriteriaEvaluasi'])->name('evaluasi.store.kriteria.edit');
        Route::get('evaluasi/{evaluasi_id}/edit/{id}', [EvaluasiController::class, 'destroyFotoEvaluasi'])->name('evaluasi.delete.foto');



        Route::post('/live-search', [UserController::class, 'selectSearch']);
        Route::post('/evaluasi/status/{evaluasi_id}', [EvaluasiController::class, 'changeSatatus'])->name('change-status');

        // export
        Route::get('export/evaluasi/{evaluasi_id}',[ReportController::class,'index'])->name('export-evaluasi');
    });
