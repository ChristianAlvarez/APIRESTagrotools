<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreDeviceRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\DetailsDevice;

class DetailsDeviceController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveDetailsDevice(Request $request)
    {
        $detailsdevices = collect($request->all());    
        $insert = $detailsdevices->where('row_mode', 1);
        $update = $detailsdevices->where('row_mode', 0);
       
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
    
     private function Insert($detailsdevices)
    {
       
        try {
                $DetailsDevice = new \App\DetailsDevice();
                $DetailsDevice = DetailsDevice::insert($detailsdevices);
                if (!$DetailsDevice) {
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

    private function Update($detailsdevices)
    {
        try {
            foreach  ($detailsdevices as $id_key => $detailsdevice) {
                $DetailsDevice =  Company::where(['devi_id' => $detailsdevice['devi_id']])
                                         ->where(['cpny_id' => $detailsdevice['cpny_id']])
                                         ->where(['pers_id' => $detailsdevice['pers_id']])
                                         ->update(['dtde_active' => $detailsdevice['dtde_active'],
                                                   'dtde_record' => $detailsdevice['dtde_record']]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
        
    }
}
