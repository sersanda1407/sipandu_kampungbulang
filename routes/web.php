<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\DataKk;
use App\DataRw;
use App\DataRt;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KkController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\RwController;
use App\Http\Controllers\RtController;
use App\Http\Controllers\VerificationController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\HistoryLogController;

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


Route::get('/api/check-phone', function (Request $request) {
    $phone = $request->query('no_telp');

    if (!$phone) {
        return response()->json(['exists' => false, 'message' => 'Nomor telepon tidak diberikan']);
    }

    // Cek di tabel DataKk (kolom 'no_telp')
    $existsInKk = DataKk::where('no_telp', $phone)->exists();

    // Cek di tabel Rt (kolom 'no_hp')
    $existsInRt = DataRt::where('no_hp', $phone)->exists();

    // Cek di tabel Rw (kolom 'no_hp')
    $existsInRw = DataRw::where('no_hp', $phone)->exists();

    // Jika nomor ada di SALAH SATU dari ketiga tabel, dianggap terdaftar
    $exists = $existsInKk || $existsInRt || $existsInRw;

    // Opsi: Beri tahu juga di tabel mana nomor itu ditemukan (lebih informatif)
    $foundIn = [];
    if ($existsInKk) $foundIn[] = 'DataKk';
    if ($existsInRt) $foundIn[] = 'Rt';
    if ($existsInRw) $foundIn[] = 'Rw';

    return response()->json([
        'exists' => $exists,
        'found_in_tables' => $foundIn // Opsional, bisa dihilangkan jika tidak perlu
    ]);
})->name('api.check-phone');


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
    // Route untuk validasi duplikasi no HP/telepon
Route::get('/api/check-nophone', [DashboardController::class, 'checkDuplicatenoPhone'])->name('api.check-nophone');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // History Log Routes
// History Log Routes
Route::get('/histori', [HistoryLogController::class, 'index'])
    ->name('histori.index')
    ->middleware('auth');


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

    /*
    |--------------------------------------------------------------------------
    | Verification Routes (WhatsApp Notifikasi)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->group(function () {
        Route::post('/kk/verify/{id}', [VerificationController::class, 'verify'])->name('kk.verify');
        Route::post('/kk/unverify/{id}', [VerificationController::class, 'unverify'])->name('kk.unverify');
        Route::post('/kk/reject/{id}', [VerificationController::class, 'reject'])->name('kk.reject');
        Route::post('/kk/reminder/{id}', [VerificationController::class, 'sendVerificationReminder'])->name('kk.reminder');
    });

    /*
    |--------------------------------------------------------------------------
    | Inbox Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->prefix('inbox')->name('inbox.')->group(function () {
        Route::get('/', [InboxController::class, 'index'])->name('index');
        Route::post('/verifikasi/{id}', [InboxController::class, 'verifikasi'])->name('verifikasi');
    });

});