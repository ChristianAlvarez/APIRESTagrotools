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

//COMPANY
Route::post('/savecompany', 'CompanyController@saveCompany');

//PICKING
Route::post('/savepicking', 'UserController@savePicking');

//DEVICE
Route::post('/savedevice', 'DeviceController@saveDevice');

//DETAILSDEVICE
Route::post('/savedetailsdevice', 'DetailsDeviceController@saveDetailsDevice');

//REAP
Route::get('/reap/{pers_id}/{cpny_id}', 'ReapController@index');
Route::post('/savereap', 'ReapController@saveReap');

//DETAILSREAP
Route::post('/savedetailsreap', 'DetailsReapController@saveDetailsReap');

//USER
Route::post('/login', 'UserController@Login');
Route::post('/saveuserpicking', 'UserController@SaveUserPicking');
Route::put('/updateuserpicking/{pers_id}', 'UserController@UpdateUserPicking');
Route::post('/saveuserpickingcompany', 'UserController@SaveUserPickingCompany');
Route::put('/updateuserpickingcompany/{cpny_id}/{pers_id}', 'UserController@UpdateUserPickingCompany');

//SYNC
Route::get('/syncUp/{pers_id}/{cpny_id}/{devi_id}/{updated_at}', 'SyncController@SyncUp');
Route::get('/Synchronized/{pers_id}/{cpny_id}/{devi_id}', 'SyncController@Synchronized');

//MOVEMENTREAP
Route::get('/getmovementreap/{date}/{company}', 'MovementreapController@getMovementReap');
Route::post('/postmovementreap', 'MovementreapController@postMovementReap');

Route::get('/get', 'MovementreapController@get');
