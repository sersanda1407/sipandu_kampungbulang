<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\DataRw;
use App\DataRt;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KkController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\RwController;
use App\Http\Controllers\RtController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\InboxController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/storePublic', [KkController::class, 'storePublic'])->name('kk.storePublic');
Auth::routes(['register' => false]);



/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard & Profil
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/edit-profile', [DashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/edit-lurah', [DashboardController::class, 'editLurah'])->name('edit.lurah');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /*
    |--------------------------------------------------------------------------
    | RW Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('rw')->group(function () {
        Route::get('/', [RwController::class, 'index'])->name('rw.index');
        Route::post('/store', [RwController::class, 'store'])->name('rw.store');
        Route::put('/update/{id}', [RwController::class, 'update'])->name('rw.update');
        Route::delete('/delete/{id}', [RwController::class, 'destroy'])->name('rw.delete');
        Route::post('/reset-password/{id}', [RwController::class, 'resetPassword'])->name('rw.resetPassword');
        Route::get('/{id}/showRW', [RwController::class, 'show'])->name('rw.show');

        // API Check
        Route::get('/api/check-nohp', function (Request $r) {
            return ['exists' => DataRw::where('no_hp', $r->no_hp)->exists()];
        })->name('api.check-nohp');

        Route::get('/api/check-rw', function (Request $r) {
            return ['exists' => DataRw::where('rw', $r->rw)->exists()];
        })->name('api.check-rw');
    });

    /*
    |--------------------------------------------------------------------------
    | RT Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('rt')->group(function () {
        Route::get('/', [RtController::class, 'index'])->name('rt.index');
        Route::post('/store', [RtController::class, 'store'])->name('rt.store');
        Route::put('/update/{id}', [RtController::class, 'update'])->name('rt.update');
        Route::delete('/delete/{id}', [RtController::class, 'destroy'])->name('rt.delete');
        Route::post('/reset-password/{id}', [RtController::class, 'resetPassword'])->name('rt.resetPassword');
        Route::get('/{id}/showRT', [RtController::class, 'show'])->name('rt.show');

        // API Check
        Route::get('/api/check-nohp', function (Request $r) {
            return ['exists' => DataRt::where('no_hp', $r->no_hp)->exists()];
        })->name('api.check-nohp');
    });

    /*
    |--------------------------------------------------------------------------
    | KK (Kartu Keluarga) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('kk')->group(function () {
        Route::get('/', [KkController::class, 'index'])->name('kk.index');
        Route::post('/store', [KkController::class, 'store'])->name('kk.store');
        Route::get('/{id}/showPenduduk', [KkController::class, 'show'])->name('kk.show');
        Route::put('/update/{id}', [KkController::class, 'update'])->name('kk.update');
        Route::delete('/delete/{id}', [KkController::class, 'destroy'])->name('kk.delete');
        Route::post('/{id}/reset-password', [KkController::class, 'resetPassword'])->name('kk.resetPassword');

        // Penduduk terkait KK
        Route::post('{id}/showPenduduk/store', [PendudukController::class, 'store'])->name('kk.storePdd');
        Route::delete('{id}/showPenduduk/delete', [PendudukController::class, 'destroy'])->name('kk.deletePdd');
        Route::put('{id}/showPenduduk/edit', [PendudukController::class, 'update'])->name('kk.editPdd');
    });

    /*
    |--------------------------------------------------------------------------
    | Penduduk Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('penduduk')->group(function () {
        Route::get('/', [PendudukController::class, 'index'])->name('penduduk.index');
        Route::get('/export/{id}', [PendudukController::class, 'export'])->name('penduduk.export');
        Route::get('/exportRt/{id}', [PendudukController::class, 'exportRt'])->name('penduduk.exportRt');
        Route::get('/exportRw/{id}', [PendudukController::class, 'exportRw'])->name('penduduk.exportRw');
        Route::get('/exportAll', [PendudukController::class, 'exportAll'])->name('penduduk.exportAll');
        Route::get('/filter', [PendudukController::class, 'filter'])->name('filter.data');
        Route::get('/penduduk/export-filtered', [PendudukController::class, 'exportFiltered'])->name('penduduk.exportFiltered');
    });

    Route::prefix('inbox')->group(function () {
    Route::get('/', [InboxController::class, 'index'])->name('inbox.index');
    Route::post('/verifikasi/{id}', [InboxController::class, 'verifikasi'])->name('inbox.verifikasi');
});

});
