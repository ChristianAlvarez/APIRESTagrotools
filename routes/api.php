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

Route::get('test', function() {
	return 'Test';
});

//MOBILE
Route::group(['prefix' => '/agroreap/mobile'], function (){
	//USER - AUTHENTICATE
	Route::post('/authenticate', 'MobileController@authenticate');
	Route::get('/indexuser', 'MobileController@indexUser');

	//MOVEMENTREAP
	Route::get('/indexmovementreap', 'MobileController@indexMovementReap');
	Route::post('/savemovementreap', 'MobileController@storeMovementReap');

	//DETAILREAP
	Route::post('/savedetailreapmanual', 'MobileController@storeDetailsReapManual');
	Route::post('/updatedetailsreapmanual', 'MobileController@updateDetailsReapManual');

	//SYNC
	Route::post('/sync', 'MobileController@sync');
	Route::post('/syncup', 'SyncController@SyncUp');
	Route::post('/syncuppickings', 'SyncController@SynchronizedPickings');
	Route::post('/syncupcompanies', 'SyncController@SynchronizedCompanies');
	Route::post('/syncupreaps', 'SyncController@SynchronizedReaps');
	Route::post('/syncupdetailsreaps', 'SyncController@SynchronizedDetailsreap');

	Route::get('/synchronized/{pers_id}/{cpny_id}/{devi_id}', 'SyncController@Synchronized');

	//TOKEN GCM
	Route::post('/posttoken', 'MobileController@posttoken');

	//Verify Picking Active
	Route::post('/useractive', 'MobileController@userActive');

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
	Route::get('/getdetailsreap/{company}', 'DesktopController@getDetailsReap');
	Route::get('/getdetailsreapupdate/{company}', 'DesktopController@getDetailsReapUpdate');
	Route::post('/savedetailsreap', 'DesktopController@saveDetailsReap');

	Route::post('/updatedetailsreap', 'DesktopController@updateDetailManual');
	Route::post('/updatedetailsreapupdate', 'DesktopController@updateDetailManualUpdate');

	//MOVEMENTREAP
	Route::get('/getmovementreap/{date}/{company}', 'DesktopController@getMovementReap');
	Route::post('/updatemovementreap', 'DesktopController@postMovementReap');

	//SYNCHRONIZATIONS
	Route::get('/getsynchronizations/{company}', 'DesktopController@getSynchronizations');
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


//TEST PUSH NOTIFICATION
Route::get('/index2', 'RespaldoController@index');

