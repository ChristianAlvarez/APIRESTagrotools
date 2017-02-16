<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\UserPicking;
use App\Device;
use App\Company;
use App\Reap;

class SyncController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SyncUp($Pers_id, $Cpny_id, $Devi_id, $Updated_at)
    {
        
        $data = [
            'Pers_id' => $Pers_id, 
            'Cpny_id' => $Cpny_id,
            'Devi_id' => $Devi_id
        ]; 

        $rules = [
            'Pers_id' => 'required|max:12|exists:userspicking,pers_id',
            'Cpny_id' => 'required|max:20',
            'Devi_id' => 'required|max:50|exists:device,devi_id',
        ];

        $messages = [
            'Pers_id.required' => 'pers_id - Identificador del usuario es requerido',
            'Pers_id.max'      => 'Pers_id - Id maximo de caracteres permitidos 20',
            'Pers_id.exists'   => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',
            'Cpny_id.required' => 'cpny_id - Compañia es requerido',
            'Cpny_id.max'      => 'cpny_id - Compañia maximo de caracteres permitidos 12',
            'Devi_id.required' => 'devi_id - Identificador del dispositivo es requerido',
            'Devi_id.max'      => 'devi_id - Dispositivo maximo de caracteres permitidos 50',
            'Devi_id.exists'   => 'devi_id - Identificador del dispositivo debe existir en tabla Device',
        ];       

        $validator = Validator::make($data, $rules, $messages);
       
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }else {

            $Company = Company::where('pers_id', $Pers_id)
                              ->where('cpny_id', $Cpny_id)
                              ->where('cpny_active', 1)
                              ->where('updated_at', '>', $Updated_at)
                              ->orderBy('updated_at', 'desc')
                              ->get();

            $Picking = Picking::where('pers_id', $Pers_id)
                              ->where('uspi_active', 1)
                              ->where('updated_at', '>', $Updated_at)
                              ->orderBy('updated_at', 'desc')
                              ->get();

            $Device = Device::where('devi_id', $Devi_id)
                             ->where('devi_active', 1)
                             ->where('updated_at', '>', $Updated_at)
                             ->orderBy('updated_at', 'desc')
                             ->get();

           

            $Reaps = Reap::with('DetailsReap')
                                ->where('cpny_id', $Cpny_id)
                                ->where('pers_id', $Pers_id)
                                ->where('updated_at', '>', $Updated_at)
                                ->orderBy('updated_at', 'desc')
                                ->get();

            $Data = [
                'UserPicking' => $UserPicking,
                'Device' => $Device,
                'UserPickingCompanys' => $UserPickingCompanys,
                'Reaps' => $Reaps
            ];

            return response()->json([
                'msg' => "Success",
                'Data' => $Data
            ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Synchronized($Pers_id, 
                                 $Cpny_id, 
                                 $Devi_id,
                                 $created_userpicking, 
                                 $created_device, 
                                 $created_userpickingcompany, 
                                 $created_reap, 
                                 $created_detailsreap)
    {
        $data = [

            'Pers_id' => $Pers_id, 
            'Cpny_id' => $Cpny_id,
            'Devi_id' => $Devi_id

            'created_userpicking'        => $created_userpicking, 
            'created_device'             => $created_device,
            'created_userpickingcompany' => $created_userpickingcompany,
            'created_reap'               => $created_reap,
            'created_detailsreap'        => $created_detailsreap,
        ]; 

        $rules = [

            'Pers_id' => 'required|max:12|exists:userspicking,pers_id',
            'Cpny_id' => 'required|max:20',
            'Devi_id' => 'required|max:50|exists:device,devi_id',

            'created_userpicking'        => 'required|date',
            'created_device'             => 'required|date',
            'created_userpickingcompany' => 'required|date',
            'created_reap'               => 'required|date',
            'created_detailsreap'        => 'required|date',
        ];

        $messages = [

            'Pers_id.required' => 'pers_id - Identificador del usuario es requerido',
            'Pers_id.max'      => 'Pers_id - Id maximo de caracteres permitidos 20',
            'Pers_id.exists'   => 'pers_id - Identificador del usuario debe existir en tabla Userpicking',
            'Cpny_id.required' => 'cpny_id - Compañia es requerido',
            'Cpny_id.max'      => 'cpny_id - Compañia maximo de caracteres permitidos 12',
            'Devi_id.required' => 'devi_id - Identificador del dispositivo es requerido',
            'Devi_id.max'      => 'devi_id - Dispositivo maximo de caracteres permitidos 50',
            'Devi_id.exists'   => 'devi_id - Identificador del dispositivo debe existir en tabla Device',

            'created_userpicking.required'          => 'created_userpicking - Periodo tabla UserPicking es requerido',
            'created_userpicking.date'              => 'created_userpicking - Periodo tabla UserPicking debe ser formato Date valido',

            'created_device.required'               => 'created_device - Periodo tabla Device es requerido',
            'created_device.date'                   => 'created_device - Periodo tabla Device debe ser formato Date valido',

            'created_userpickingcompany.required'   => 'created_userpickingcompany - Periodo tabla UserPickingCompany es requerido',
            'created_userpickingcompany.date'       => 'created_userpickingcompany - Periodo tabla UserPickingCompany debe ser formato Date valido',

            'created_reap.required'                 => 'created_reap - Periodo tabla Reap es requerido',
            'created_reap.date'                     => 'created_reap - Periodo tabla Reap debe ser formato Date valido',

            'created_detailsreap.required'          => 'created_detailsreap - Periodo tabla DetailsReap es requerido',
            'created_detailsreap.date'              => 'created_detailsreap - Periodo tabla DetailsReap debe ser formato Date valido',
        ];       

        $validator = Validator::make($data, $rules, $messages);
       
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }else {

            $UserPicking = UserPicking::where('pers_id', $Pers_id)
                                        ->where('uspi_active', 1)
                                        ->where('uspi_record', 0)
                                        ->whereDate('created_at', '<=',  $created_userpicking)
                                        ->update(['uspi_record' => 1]);
            

            $Device = Device::where('devi_id', $Devi_id)
                             ->where('devi_active', 1)
                             ->where('devi_record', 0)
                             ->whereDate('created_at', '<=',  $created_device)
                             ->update(['devi_record' => 1]);


            $UserPickingCompanys = UserPickingCompany::where('pers_id', $Pers_id)
                                                        ->where('cpny_id', $Cpny_id)
                                                        ->where('cpny_active', 1)
                                                        ->where('cpny_record', 0)
                                                        ->whereDate('created_at', '<=',  $created_userpickingcompany)
                                                        ->update(['cpny_record' => 1]);

            $Reaps = Reap::where('cpny_id', $Cpny_id)
                         ->where('pers_id', $Pers_id)
                         ->where('reap_record', 0)
                         ->whereDate('created_at', '<=',  $created_reap)
                         ->update(['reap_record' => 1]);
            
            $DetailsReap = DetailsReap::whereIn('reap_id', $reap->reap_id)
                                      ->whereIn('dere_status_card', 1)
                                      ->whereIn('dere_record', 0)
                                      ->whereDate('created_at', '<=',  $created_detailsreap)
                                      ->update(['dere_record' => 1]);

            return response()->json([
                'msg' => "Synchronized Success"
            ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
        }
    }

}
