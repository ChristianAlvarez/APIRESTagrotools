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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//PICKING
Route::post('/savepicking', 'UserController@SavePicking');

//USER
Route::get('/login/{pers_id}/{uspi_password}/{devi_id}', 'UserController@Login');
Route::post('/saveuserpicking', 'UserController@SaveUserPicking');
Route::put('/updateuserpicking/{pers_id}', 'UserController@UpdateUserPicking');
Route::post('/saveuserpickingcompany', 'UserController@SaveUserPickingCompany');
Route::put('/updateuserpickingcompany/{cpny_id}/{pers_id}', 'UserController@UpdateUserPickingCompany');

//DEVICE
Route::post('/savedevice', 'DeviceController@SaveDevice');
Route::put('/updatedevice/{devi_id}', 'DeviceController@UpdateDevice');

//SYNC
Route::get('/syncUp/{pers_id}/{cpny_id}/{devi_id}', 'SyncController@SyncUp');
Route::get('/Synchronized/{pers_id}/{cpny_id}/{devi_id}', 'SyncController@Synchronized');

//REAP
Route::get('/reap/{pers_id}/{cpny_id}', 'ReapController@index');

//MOVEMENTREAP
Route::get('/movementreap', 'MovementreapController@index');
