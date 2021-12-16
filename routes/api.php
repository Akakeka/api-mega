<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/registrar', 'Auth\RegisterController@create')->name('create');
Route::get('/roles', 'Configuracion\ConfiguracionController@roles')->name('roles');
Route::post('/asignarPerfil', 'Configuracion\ConfiguracionController@asignarPerfil')->name('asignar');
Route::post('/storeCita', 'Configuracion\ConfiguracionController@storeCita')->name('storeCita');
Route::post('/suscribirse', 'Configuracion\ConfiguracionController@suscribirse')->name('suscribirse');
Route::post('/AsociarCupoCita', 'Configuracion\ConfiguracionController@AsociarCupoCita')->name('AsociarCupoCita');
Route::post('/storeRol', 'Configuracion\ConfiguracionController@storeRol');