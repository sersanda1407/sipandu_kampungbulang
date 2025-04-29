<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\DataRt;
use App\DataPenduduk;
use Barryvdh\DomPDF\Facade\Pdf;




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
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// });


Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::group(['prefix' => 'rw'], function () {
        Route::get('/', 'RwController@index')->name('rw.index');
        Route::post('/store', 'RwController@store')->name('rw.store');
        Route::put('/update/{id}', 'RwController@update')->name('rw.update');
        Route::delete('/delete/{id}', 'RwController@destroy')->name('rw.delete');
        Route::post('/reset-password/{id}', 'RwController@resetPassword')->name('rw.resetPassword');
        Route::get('/api/check-nohp', function (Illuminate\Http\Request $r) {
            return ['exists' => \App\DataRw::where('no_hp', $r->no_hp)->exists()];
        })->name('api.check-nohp');
        Route::get('/api/check-rw', function (Illuminate\Http\Request $r) {
            return ['exists' => \App\DataRw::where('rw', $r->rw)->exists()];
        })->name('api.check-rw');

    });
    Route::group(['prefix' => 'rt'], function () {
        Route::get('/', 'RtController@index')->name('rt.index');
        Route::post('/store', 'RtController@store')->name('rt.store');
        Route::put('/update/{id}', 'RtController@update')->name('rt.update');
        Route::delete('/delete/{id}', 'RtController@destroy')->name('rt.delete');
        Route::post('/reset-password/{id}', 'RtController@resetPassword')->name('rt.resetPassword');
        Route::get('/api/check-nohp', function (Illuminate\Http\Request $r) {
            return ['exists' => \App\DataRt::where('no_hp', $r->no_hp)->exists()];
        })->name('api.check-nohp');

    });
    Route::group(['prefix' => 'kk'], function () {
        Route::get('/', 'KkController@index')->name('kk.index');
        Route::post('/store', 'KkController@store')->name('kk.store');
        Route::get('/{id}/showPenduduk', 'KkController@show')->name('kk.show');
        Route::put('/update/{id}', 'KkController@update')->name('kk.update');
        Route::delete('/delete/{id}', 'KkController@destroy')->name('kk.delete');
        Route::post('{id}/showPenduduk/store', 'PendudukController@store')->name('kk.storePdd');
        Route::delete('{id}/showPenduduk/delete', 'PendudukController@destroy')->name('kk.deletePdd');
        Route::put('{id}/showPenduduk/edit', 'PendudukController@update')->name('kk.editPdd');
        Route::post('/{id}/reset-password', 'KkController@resetPassword')->name('kk.resetPassword');

    });
    Route::group(['prefix' => 'penduduk'], function () {
        Route::get('/', 'PendudukController@index')->name('penduduk.index');
        Route::get('/export/{id}', 'PendudukController@export')->name('penduduk.export');
        Route::get('/exportRt/{id}', 'PendudukController@exportRt')->name('penduduk.exportRt');
        Route::get('/exportRw/{id}', 'PendudukController@exportRw')->name('penduduk.exportRw');
        Route::get('/exportAll', 'PendudukController@exportAll')->name('penduduk.exportAll');
        Route::get('/filter', 'PendudukController@filter')->name('filter.data');
        Route::get('/penduduk/export-filtered', 'PendudukController@exportFiltered')->name('penduduk.exportFiltered');

    });

    Route::post('/edit-profile', 'DashboardController@editProfile')->name('profile.edit');
    Route::put('/edit-lurah', 'DashboardController@editLurah')->name('edit.lurah');
    Route::get('/home', 'HomeController@index')->name('home');
});

Auth::routes();
