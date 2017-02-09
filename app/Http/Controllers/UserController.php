<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserPickingRequest;
use App\Http\Requests\StorePickingRequest;
use App\http\Requests\StoreUserPickingCompanyRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\UserPicking;
use App\UserPickingCompany;
use App\Device;
use App\Picking;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Login($Pers_id, $Uspi_password, $Devi_id)
    {
        
        $UserPicking = UserPicking::with(array('Device' => function($query) use ($Devi_id)
                                {
                                    $query->where('Device.devi_id', $Devi_id);
                                    $query->where('Device.devi_active', 1);
                                    $query->where('Device.devi_record', 0);
                                }
                            ))
                            ->where('pers_id', $Pers_id)
                            ->where('uspi_password', $Uspi_password)
                            ->where('uspi_active', 1)
                            ->get();

        $Companys = UserPickingCompany::where('pers_id', $Pers_id)->get();

        /*$UserPicking = DB::table('UsersPicking')
                        ->join('Device', 'UsersPicking.pers_id', '=', 'Device.pers_id')
                        ->where('UsersPicking.pers_id', $Pers_id)
                                    ->where('UsersPicking.uspi_password', $uspi_password)
                                    ->where('UsersPicking.uspi_active', 1)
                                    ->where('Device.devi_id', $Devi_id)
                                    ->where('Device.devi_active', 1)
                                    ->where('Device.devi_record', 0)
                                    ->get();*/
        return response()->json([
                'msg' => "Login Success",
                'UserPicking' => $UserPicking,
                'Companys' => $Companys  
        ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
    }   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function SavePicking(Request $request)
    { 
        $request = $request->all();
        
        try
            {
                $Picking = new \App\Picking();
                $Picking = Picking::insert($request);
                
                if ($Picking) {
                    return response()->json([
                        'Codigo' => "2"
                    ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
                }
                else{
                    return response()->json([
                        'Codigo' => "1"
                    ]);
                }
            }
            catch(\Illuminate\Database\QueryException $e)
            {
                return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);

            }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function SaveUserPicking(StoreUserPickingRequest $request)
    {

        $request = $request->all();
   
        $UserPicking        = new \App\UserPicking();
        $UserPicking = UserPicking::insert($request);
        
        if ($UserPicking) {
            return response()->json([
                'msg' => "SaveUserPicking Success"
            ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
        }
        else{
            return response()->json([
                'Error' => "Problemas en metodo SaveUserPicking"
            ])-getStatusCode();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UpdateUserPicking(StoreUserPickingRequest $request, $pers_id)
    {
        $UserPicking = UserPicking::where('pers_id', $pers_id)->get();

        $Request = $request->all();
       
        if ($UserPicking->fill($Request)->save()) {
            return response()->json([
                'msg' => "UpdateUserPicking Success"
            ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
        }
        else{
            return response()->json([
                'Error' => "Problemas en metodo UpdateUserPicking"
            ])-getStatusCode();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function SaveUserPickingCompany(StoreUserPickingRequest $request)
    {

        $request = $request->all();
   
        $UserPickingCompany = new \App\UserPickingCompany();
        $UserPickingCompany = UserPickingCompany::create($request);
        
        if ($UserPickingCompany) {
            return response()->json([
                'msg' => "SaveUserPickingCompany Success"
            ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UpdateUserPickingCompany(StoreUserPickingCompanyRequest $request, $cpny_id, $pers_id)
    {
        $UserPickingCompany = UserPickingCompany::where('pers_id', $pers_id)
                                                ->where('cpny_id', $cpny_id)->get();

        $Request = $request->all();
       
        if ($UserPickingCompany->fill($Request)->save()) {
            return response()->json([
                'msg' => "UpdateUserPickingCompany Success"
            ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
