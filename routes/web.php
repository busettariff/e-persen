<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KonfigurasiController;

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
    // Login Admin
    Route::get('panel', function(){return view('auth.loginAdmin');})->name('panel')->middleware(['notLoggedIn']);
    Route::post('panel','autenticate')->middleware(['notLoggedIn']);
    Route::get('panel/signout','logoutadmin');
    // Login User
    Route::get('login',function(){return view('auth.loginView');})->name('login')->middleware(['notLoggedIn']);
    Route::post('login','autenticate')->middleware(['notLoggedIn']);
    Route::get('logout','logout');

});


Route::group(['middleware' => ['auth']], function (){

    //Akses admin
    Route::group(['middleware' => ['cekUserLogin:admin']], function () {
        Route::get('panel/home', [DashboardController::class, 'indexadmin']);

        // pegawai
        Route::get('/pegawai',[PegawaiController::class, 'index']);
        Route::post('/pegawai/store',[PegawaiController::class, 'store']);
        Route::post('/pegawai/edit',[PegawaiController::class, 'edit']);
        Route::post('/pegawai/{username}/update',[PegawaiController::class, 'update']);
        Route::post('/pegawai/{username}/delete',[PegawaiController::class, 'delete']);

        //Divisi atau Mapel
        Route::get('/mapel',[MapelController::class, 'index']);
        Route::post('/mapel/store',[MapelController::class, 'store']);
        Route::post('/mapel/edit',[MapelController::class, 'edit']);
        Route::post('/mapel/{kode_mapel}/update',[MapelController::class, 'update']);
        Route::post('/mapel/{kode_mapel}/delete',[MapelController::class, 'delete']);

        //Monitoring
        Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
        Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
        Route::post('/showmaps', [PresensiController::class, 'showmaps']);
        Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
        Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);

        //Konfigurasi
        Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
        Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);

    });

    //Akses user
    Route::group(['middleware' => ['cekUserLogin:user']], function () {
        Route::get('dashboard', [DashboardController::class,'index']);
        //presensi
        Route::get('/presensi/create',[PresensiController::class, 'create']);
        Route::get('/presensi/location',[PresensiController::class, 'location']);
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
        Route::post('/presensi/storeizin',[PresensiController::class, 'storeizin']);

    });

});
