<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/**
 *  LOGIN CUSTOMER, DRIVER, PEGAWAI
 */ 
Route::post('login', 'Api\AuthController@login');

/**
 * RESGITER FOR CUSTOMER
 */
Route::post('register/customer', 'Api\UserController@register');

/**
 * AUTH FOR CUSTOMER AND PEGAWAI
 */
Route::group(['middleware' => ['auth:api', 'auth:pegawai']], function() {
});
//Pembayaran(role by CS & Customer)
Route::get('show/pembayaran/{id}','Api\PembayaranController@show');
Route::put('update/pembayaran/{id}','Api\PembayaranController@update');
//--------------------------------------------------------------------------------------
//Transaksi(role by CS & Customer)
Route::get('show/transaksi/{id}','Api\TransaksiController@show');
Route::get('showInProgress/transaksi/{idC}','Api\TransaksiController@showTransaksiInProgress');
Route::put('update/transaksi/{id}','Api\TransaksiController@update');
//--------------------------------------------------------------------------------------
//Everything about showing data for transaction (mobil,promo)
Route::get('mobil','Api\MobilController@index');
Route::get('getAvailableMobil','Api\MobilController@getAvailableMobil');
Route::get('promo','Api\PromoController@index');
//--------------------------------------------------------------------------------------

/**
 * AUTH FOR DRIVER AND PEGAWAI
 */
Route::group(['middleware' => ['auth:driver', 'auth:pegawai']], function() {
});
Route::get('show/driver/{id}','Api\DriverController@show');

/**
 * AUTH FOR CUSTOMER
 */
Route::group(['middleware' => 'auth:api'], function() {
});
//Customer
Route::post('update/customer/{id}', 'Api\UserController@updateProfile');
Route::put('updateEmail/customer/{id}', 'Api\UserController@updateEmail');
Route::put('deleteSIM/customer/{id}', 'Api\UserController@deleteSIM');
Route::put('deleteKP/customer/{id}', 'Api\UserController@deleteKP');
Route::put('updatePassword/customer/{id}', 'Api\UserController@updatePassword');
Route::get('show/customer/{id}', 'Api\UserController@show');
//--------------------------------------------------------------------------------------
//Pembayaran Create
Route::post('create/pembayaran','Api\PembayaranController@create');
Route::post('hitungBiaya/pembayaran','Api\PembayaranController@hitungBiaya');
Route::get('showAllCustomer/pembayaran/{idC}','Api\PembayaranController@showAllByCustomer');
//--------------------------------------------------------------------------------------
//Transaksi 
Route::post('create/transaksi','Api\TransaksiController@create');
Route::post('cekTanggalSewa/transaksi','Api\TransaksiController@cekTanggalSewa');
Route::get('showAllCustomer/transaksi/{idC}','Api\TransaksiController@showAllByCustomer');
Route::put('updateRate/transaksi/{id}','Api\TransaksiController@updateRate');
//--------------------------------------------------------------------------------------
Route::get('getAvailableDriver','Api\DriverController@getAvailableDriver');

/**
 * AUTH FOR DRIVER
 */
Route::group(['middleware' => 'auth:driver'], function() {
});
//Driver
Route::put('updateProfile/driver/{id}','Api\DriverController@updateProfile');
Route::put('updateStatus/driver/{id}','Api\DriverController@updateStatus');
Route::put('updateEmail/driver/{id}','Api\DriverController@updateEmail');
Route::put('updatePassword/driver/{id}','Api\DriverController@updatePassword');
//--------------------------------------------------------------------------------------

/**
 * AUTH FOR PEGAWAI
 */
Route::group(['middleware' => 'auth:pegawai'], function() {
});
//Driver(role by Admin)
Route::post('create/driver','Api\DriverController@create');
Route::get('driver','Api\DriverController@index');
Route::post('updateByAdmin/driver/{id}','Api\DriverController@updateByAdmin');
Route::put('updateBerkas/driver/{id}','Api\DriverController@updateBerkas');
Route::put('updateStatusKetersediaan/driver/{id}','Api\DriverController@updateStatusKetersediaan');
Route::put('updateStatusAktif/driver/{id}','Api\DriverController@updateStatusAktif');
Route::delete('destroy/driver/{id}','Api\DriverController@destroy');
//--------------------------------------------------------------------------------------
//Mitra(role by Admin)
Route::post('create/mitra', 'Api\MitraController@create');
Route::get('mitra','Api\MitraController@index');
Route::get('showAll/mitra','Api\MitraController@showMitraByStatus');
Route::get('show/mitra/{id}','Api\MitraController@show');
Route::put('update/mitra/{id}','Api\MitraController@update');
Route::put('updateStatus/mitra/{id}','Api\MitraController@updateStatus');
Route::delete('delete/mitra/{id}','Api\MitraController@destroy');
//--------------------------------------------------------------------------------------
//Promo(role by Manager)
Route::post('create/promo','Api\PromoController@create');
Route::get('show/promo/{id}','Api\PromoController@show');
Route::put('update/promo/{id}','Api\PromoController@update');
Route::delete('delete/promo/{id}','Api\PromoController@destroy');
//--------------------------------------------------------------------------------------
//Role(role by Manager)
Route::post('create/role','Api\RoleController@create');
Route::get('role','Api\RoleController@index');
Route::get('show/role/{id}','Api\RoleController@show');
Route::put('update/role/{id}','Api\RoleController@update');
Route::delete('delete/role/{id}','Api\RoleController@destroy');
//--------------------------------------------------------------------------------------
//Jadwal(role by Manager)
Route::post('create/jadwal', 'Api\JadwalController@create');
Route::get('jadwal','Api\JadwalController@index');
Route::get('getTime/jadwal','Api\JadwalController@getTime');
Route::get('show/jadwal/{id}','Api\JadwalController@show');
Route::get('search/jadwal/{hari}/{wm}/{ws}','Api\JadwalController@cekInputJadwal');
Route::put('update/jadwal/{id}','Api\JadwalController@update');
Route::delete('delete/jadwal/{id}','Api\JadwalController@destroy');
//--------------------------------------------------------------------------------------
//Pegawai(role by Admin)
Route::post('create/pegawai', 'Api\PegawaiController@create');
Route::get('pegawai', 'Api\PegawaiController@index');
Route::get('getAktif/pegawai', 'Api\PegawaiController@getPegawaiAktif');
Route::get('show/pegawai/{id}', 'Api\PegawaiController@show');
Route::post('update/pegawai/{id}', 'Api\PegawaiController@update');
Route::put('updateStatus/pegawai/{id}', 'Api\PegawaiController@updateStatus');
Route::put('updateEmail/pegawai/{id}', 'Api\PegawaiController@updateEmail');
Route::put('updatePassword/pegawai/{id}', 'Api\PegawaiController@updatePassword');
Route::delete('delete/pegawai/{id}', 'Api\PegawaiController@destroy');
//--------------------------------------------------------------------------------------
//DetailJadwal(role by Manager)
Route::post('create/dj','Api\DetailJadwalController@create');
Route::get('dj','Api\DetailJadwalController@index');
Route::get('show/dj/{hari}','Api\DetailJadwalController@show');
Route::get('cekShift/dj/{id}','Api\DetailJadwalController@cekShift');
Route::get('cekIsShift/dj/{idJ}/{idP}','Api\DetailJadwalController@cekIsShift');
Route::put('update/dj/{id}','Api\DetailJadwalController@update');
Route::delete('delete/dj/{id}','Api\DetailJadwalController@destroy');
//--------------------------------------------------------------------------------------
//Mobil(role by Admin)
Route::post('create/mobil','Api\MobilController@create');
Route::get('show/contract/mobil','Api\MobilController@showMobilOnContract');
Route::get('show/mobil/{id}','Api\MobilController@show');
Route::post('update/mobil/{id}','Api\MobilController@update');
Route::put('updateStatus/mobil/{id}','Api\MobilController@updateStatus');
Route::delete('delete/mobil/{id}','Api\MobilController@destroy');
//--------------------------------------------------------------------------------------
//Pembayaran
Route::get('pembayaran','Api\PembayaranController@index');
//--------------------------------------------------------------------------------------
//Transaksi(role by CS & Manager)
Route::get('transaksi','Api\TransaksiController@index');
Route::get('countCustomer/transaksi','Api\TransaksiController@countCustomer');
Route::get('countDriver/transaksi','Api\TransaksiController@countDriver'); 
Route::put('updateStatus/transaksi/{id}','Api\TransaksiController@updateStatus');
Route::put('updateStatusBerkas/customer/{id}','Api\UserController@updateStatus');
Route::get('showAll/customer','Api\UserController@index');
//--------------------------------------------------------------------------------------