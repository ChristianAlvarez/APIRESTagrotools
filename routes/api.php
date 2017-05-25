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

Route::post('/saveuser', 'UserController@storeUser');

//MOBILE
Route::group(['prefix' => '/agroreap/mobile'], function (){
	//USER - AUTHENTICATE
	Route::post('/authenticate', 'MobileController@authenticate');
	Route::get('/indexuser', 'MobileController@indexUser');

	//MOVEMENTREAP
	Route::get('/indexmovementreap', 'MobileController@indexMovementReap');
	Route::post('/savemovementreap', 'MobileController@storeMovementReap');

	//SYNC
	Route::post('/syncup', 'SyncController@SyncUp');
	Route::post('/syncuppickings', 'SyncController@SynchronizedPickings');
	Route::post('/syncupcompanies', 'SyncController@SynchronizedCompanies');
	Route::post('/syncupreaps', 'SyncController@SynchronizedReaps');
	Route::post('/syncupdetailsreaps', 'SyncController@SynchronizedDetailsreap');

	Route::get('/synchronized/{pers_id}/{cpny_id}/{devi_id}', 'SyncController@Synchronized');
});

//DESKTOP
Route::group(['prefix' => '/agroreap/desktop'], function (){
	//USER - AUTHENTICATE
	Route::post('/authenticate', 'DesktopController@authenticate');
	
	//COMPANY
	Route::post('/savecompany', 'DesktopController@saveCompany');

	//PICKING
	Route::post('/savepicking', 'DesktopController@savePicking');

	//DEVICE
	Route::post('/savedevice', 'DesktopController@saveDevice');

	//DETAILSDEVICE
	Route::post('/savedetailsdevice', 'DesktopController@saveDetailsDevice');

	//REAP
	Route::get('/reap/{pers_id}/{cpny_id}', 'DesktopController@index');
	Route::post('/savereap', 'DesktopController@saveReap');

	//DETAILSREAP
	Route::post('/savedetailsreap', 'DesktopController@saveDetailsReap');

	//MOVEMENTREAP
	Route::get('/getmovementreap/{date}/{company}', 'DesktopController@getMovementReap');
});

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

//MOVEMENTREAP
Route::get('/getmovementreap/{date}/{company}', 'MovementreapController@getMovementReap');
Route::post('/postmovementreap', 'MovementreapController@postMovementReap');

//TEST PUSH NOTIFICATION
Route::get('/index2', 'RespaldoController@index');

