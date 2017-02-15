<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreDeviceRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Device;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($Devi_id)
    {
        /*$Device = Device::where('devi_id', $Devi_id)
                        ->where('devi_active', "true")
                        ->where('devi_record', "false")
                        ->get();*/

        /*$Device = Device::where('devi_id', $Devi_id)
                            ->where('devi_active', 1)
                            ->where('devi_record', 0)
                            ->get();*/

        $Device = Device::with('UserPicking')->where('devi_id', $Devi_id)
                            ->where('devi_active', 1)
                            ->where('devi_record', 0)
                            ->get();

        return Response()->json(array('Device' => $Device));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveDevice(Request $request)
    {
        $devices = collect($request->all());    
        $insert = $devices->where('row_mode', 1);
        $update = $devices->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->Insert($arr->toArray());
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

    private function Insert($devices)
    {
       
        try {
                $Device = new \App\Device();
                $Device = Device::insert($devices);
                if (!$Device) {
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

    private function Update($devices)
    {
        try {
            foreach  ($devices as $id_key => $device) {
                $Device =  Company::where(['devi_id' => $device['devi_id']])
                                  ->where(['cpny_id' => $device['cpny_id']])
                                  ->update(['devi_name' => $device['devi_name'],
                                            'devi_active' => $device['devi_active'],
                                            'devi_record' => $device['devi_record']]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
        
    }

}
