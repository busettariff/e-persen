<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;

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

// Route::get('/',[LoginController::class,'index'])->name('login');

// Route::get('/',[LayoutController::class, 'index'])->('layout');
Route::redirect('/', 'login');

Route::controller(LoginController::class)->group(function(){
    Route::get('login','index')->name('login')->middleware(['notLoggedIn']);
    Route::post('login','autenticate')->middleware(['notLoggedIn']);
    Route::get('logout','logout');
});


Route::group(['middleware' => ['auth']], function (){

    //Akses admin
    Route::group(['middleware' => ['cekUserLogin:1']], function () {
        Route::resource('admin', AdminController::class);

    });

    //Akses user
    Route::group(['middleware' => ['cekUserLogin:2']], function () {
        Route::resource('dashboard', DashboardController::class);
        //presensi
        Route::get('/presensi/create',[PresensiController::class, 'create']);
        Route::post('/presensi/store',[PresensiController::class, 'store']);

        //Edit Profile
        Route::get('/editprofile',[PresensiController::class, 'editprofile']);
        Route::post('/presensi/{username}/updateprofile',[PresensiController::class, 'updateprofile']);

        //Histori
        Route::get('/presensi/histori',[PresensiController::class, 'histori']);
        Route::post('/gethistori',[PresensiController::class, 'gethistori']);

        //Izin
        Route::get('/presensi/izin',[PresensiController::class, 'izin']);
        Route::get('/presensi/formizin',[PresensiController::class, 'formizin']);

    });

});
