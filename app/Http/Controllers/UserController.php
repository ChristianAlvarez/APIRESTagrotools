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
        
        $Picking = Picking::with(array('Device' => function($query) use ($Devi_id)
                                {
                                    $query->where('Device.devi_id', $Devi_id);
                                    $query->where('Device.devi_active', 1);
                                }
                            ))
                            ->where('pers_id', $Pers_id)
                            ->where('uspi_password', $Uspi_password)
                            ->where('uspi_active', 1)
                            ->get();

        $Companys = Company::where('pers_id', $Pers_id)->get();

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
    public function savePicking(Request $request)
    { 
        $pickings = collect($request->all());    
        $insert = $pickings->where('row_mode', 1);
        $update = $pickings->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->Insert($arr->toArray());
                //$this->Insert($comp);
            });
        }

        //UPDATE
        if (count($update) > 0) {
            $this->Update($update->toArray());
        }

        return response()->json([
                        'Codigo' => "2"
                    ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
    }

    private function Insert($pickings)
    {
        try {
                $Picking = new \App\Picking();
                $Picking = Picking::insert($pickings);
                if (!$Picking) {
                    return response()->json([
                        'Codigo' => "1"
                    ]);
                }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
    }

    private function Update($pickings)
    {
        try {
            foreach  ($pickings as $id_key => $picking) {
                $Picking =  Picking::where(['pers_id' => $company['pers_id']])
                                   ->where(['cpny_id' => $company['cpny_id']])
                                   ->update(['pers_name' => $company['pers_name'],
                                             'pick_password' => $company['pick_password|'],
                                             'pick_active' => $company['pick_active'],
                                             'pick_record' => $company['pick_record']]);
                }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
    }
}
