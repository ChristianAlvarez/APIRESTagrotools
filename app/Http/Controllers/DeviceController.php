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
    public function SaveDevice(StoreDeviceRequest $request)
    {
        $request = $request->all();
        dd($request);
        $Device        = new \App\Device();
        $Device = Device::create($request);
        
        if ($Device) {
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UpdateDevice(StoreDeviceRequest $request, $devi_id)
    {
        $Device = Device::where('devi_id', $devi_id)->get();

        $Request = $request->all();
       
        if ($Device->fill($Request)->save()) {
            return response()->json([
                'msg' => "UpdateDevice Success"
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
