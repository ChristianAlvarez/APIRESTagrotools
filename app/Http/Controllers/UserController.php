<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserPickingRequest;
use App\Http\Requests\StorePickingRequest;
use App\http\Requests\StoreUserPickingCompanyRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\DetailsReap;
use App\Company;
use App\Reap;
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
    public function Login(Request $request)
    {
        $Request = $request->all();  

        try 
        {
            $Login = DB::table('Device')
                        ->join('DetailsDevice', function ($join) {
                                $join->on('DetailsDevice.devi_id', '=', 'Device.devi_id');
                            })
                        ->join('Picking', function ($join) {
                                $join->on('DetailsDevice.pers_id', '=', 'Picking.pers_id');
                            })
                        ->join('Company', function ($join) {
                                $join->on('Device.cpny_id', '=', 'Company.cpny_id');
                            })
                        ->where('DetailsDevice.dtde_active', 1)
                        ->where('Device.devi_active', 1)
                        ->where('Picking.pick_active', 1)
                        ->where('Company.cpny_active', 1)
                        ->where('Picking.pick_password', $Request['pick_password'])
                        ->where('Picking.pers_id', $Request['pers_id'])
                        ->where('Device.devi_id', $Request['devi_id'])
                        ->get();                      

            if (count($Login) > 0) {
               
                $Picking = Picking::where('pers_id',  $Request['pers_id'])->get();

                $cpny_id = $Login->pluck('cpny_id');

                $Company = Company::whereIn('cpny_id', $cpny_id)->get();       

                $Reap = Reap::whereIn('cpny_id', $cpny_id)->get();

                $reap_id = $Reap->pluck('reap_id');

                $DetailsReap = DetailsReap::whereIn('reap_id', $reap_id)->get(); 

                $Data = [
                    'Company'       => $Company,
                    'Picking'       => $Picking,
                    'Reap'          => $Reap,
                    'DetailsReap'   => $DetailsReap
                ];

                return response()->json([
                    'msg' => "Login Success",
                    'Data' => $Data 
                ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
            else
            {
                return response()->json([
                    'Codigo' => "1",
                    'Mensaje' => "Usuarios y/o contraseña incorrecto"
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
        } 
        catch(\Illuminate\Database\QueryException $e) 
        {
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
