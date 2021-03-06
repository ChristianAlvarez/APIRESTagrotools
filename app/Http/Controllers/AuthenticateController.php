<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;
use Hash;
use App\DetailsReap;
use App\Company;
use App\Reap;
use App\Device;
use App\Picking;
use DB;

class AuthenticateController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth', ['except' => ['authenticate']]);
	}

	public function index()
	{
        $picking = Picking::all();
		return response()->json(compact('picking'));
	}

    public function authenticate(Request $request)
    {   
        // grab credentials from the request
        $credentials = $request->only('pers_id', 'password');       
        $requests = $request->only('pers_id', 'password', 'devi_id'); 

        $rules = [
            'pers_id'   => 'required|max:12',
            'password'  => 'required',
            'devi_id'   => 'required|max:50',
        ];

        $messages = [
            'pers_id.required'  => 'pers_id - Identificador del usuario es requerido',
            'pers_id.max'       => 'Pers_id - Id maximo de caracteres permitidos 12',
            'password.required' => 'password es requerido',
            'devi_id.required'  => 'devi_id - Identificador del dispositivo es requerido',
            'devi_id.max'       => 'devi_id - Identificador del dispositivo maximo de caracteres permitidos 50',
        ];       

        $validator = Validator::make($requests, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error validacion' => $validator->errors()
            ]);
        }
       
        $token = null;

        $customClaims = ['pick_record' => '0'];
        //dd($customClaims);
        try 
        {   

            //$user = Picking::where('pers_id', $request->pers_id)->first();
            //dd(JWTAuth::fromUser($user, $customClaims));
            //dd($token = JWTAuth::fromUser($user,['pick_record' => '0']));
            //if (!$token = JWTAuth::fromUser($user, $customClaims))
            //{
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials, $customClaims)) 
            {  
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            else
            {
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
                            ->where('Picking.pers_id', $request->pers_id)
                            ->where('Device.devi_id', $request->devi_id)
                            ->get();
                        
                    if (count($Login) > 0) {
                   
                        $Picking = Picking::where('pers_id',  $request->pers_id)->get();

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
        } 
        catch (JWTException $e) 
        {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token', 'Data'));
    }

    public function get()
    {
        $id = '16173026-2';
        $pass = '123456';
        $Picking = Picking::where('pers_id', $id)->first();

        if($Picking->count()) 
        {
            //dd($Picking);
            if(Hash::check($pass, $Picking->pick_password)) 
            {
                 //User has provided valid credentials :)
                dd($Picking);
            }
        }

        //dd($Picking->pick_password);
        //dd(Hash::check('pick_password', $Picking->pick_password));

            return response()->json([
                'Picking' => $Picking
            ]);
       
    }

    public function store(Request $request)
    {
 
        $Picking = new \App\Picking();
        $Picking->pers_id = $request->pers_id;
        $Picking->cpny_id = $request->cpny_id;
        $Picking->pers_name = $request->pers_name;
        $Picking->password = Hash::make($request->password);
        $Picking->pick_active = $request->pick_active;
        $Picking->pick_record = $request->pick_record;

        if ($Picking->save()) {
            return response()->json([
                'Picking' => $Picking
            ]);
        } 
    }
}
