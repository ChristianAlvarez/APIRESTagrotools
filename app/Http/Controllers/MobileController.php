<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

use DB;
use Hash;
use JWTAuth;
use Carbon\Carbon;

use App\Reap;
use App\Device;
use App\Company;
use App\Picking;
use App\DetailsReap;
use App\MovementReap;

class MobileController extends Controller
{
    public function __construct()
	{
		$this->middleware('jwt.auth', ['except' => ['authenticate']]);
	}

	public function indexUser()
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

        $user = null;
        $token = null;

        try 
        {   
            
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) 
	        {  
	            return response()->json(['error' => 'invalid_credentials'], 401);
	        }
	        else
	        {
	            $user = Picking::where('pers_id', $request->pers_id)->first();
                	
                if ($user->last_conection === null) 
                {
                	
                	try 
		            {

		                $Login = DB::table('device')
		                            ->join('detailsdevice', function ($join) {
		                                    $join->on('detailsdevice.devi_id', '=', 'device.devi_id');
		                                })
		                            ->join('picking', function ($join) {
		                                    $join->on('detailsdevice.pers_id', '=', 'picking.pers_id');
		                                })
		                            ->join('company', function ($join) {
		                                    $join->on('device.cpny_id', '=', 'company.cpny_id');
		                                })
		                            ->where('detailsdevice.dtde_active', 1)
		                            ->where('device.devi_active', 1)
		                            ->where('picking.pick_active', 1)
		                            ->where('company.cpny_active', 1)
		                            ->where('picking.pers_id', $request->pers_id)
		                            ->where('device.devi_id', $request->devi_id)
		                            ->get();
		                   
		                if (count($Login) > 0) 
		                {
		                   
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

		                    // all good so return the token
        					return response()->json(compact('token', 'Data'));
		                }
		                else
		                {
		                    return response()->json([
		                        'Codigo' => "1",
		                        'Mensaje' => "Usuarios y/o contraseña incorrecto"
		                    ]);
		                }    

		            //Actualizamos la fecha de ultima conexión
                	$user->last_conection = Carbon::now();
                	$user->save();
   
		            } 
		            catch(\Illuminate\Database\QueryException $e) 
		            {
		                return response()->json([
		                    'Codigo' => "2",
		                    'Descripcion' => $e
		                ]);
		                
                	}
                }	
                else
                {
                	$Data = [
		                'User' => $user
		            ];
                	// all good so return the token
        			return response()->json(compact('token', 'Data'));
                }                    
	        }                  
        } 
        catch (JWTException $e) 
        {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        
    }

    /**
     * Store a User newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUser(Request $request)
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexMovementReap()
    {
        $MovementReap = MovementReap::all();
        
        return Response()->json(array('MovementReap' => $MovementReap));
    }

    /**
     * Store a MovementReap newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMovementReap(Request $request)
    {
        $request = $request->all();
        try 
            {
                $MovementReap = new \App\MovementReap();
                $MovementReap = MovementReap::insert($request);

                if ($MovementReap) {
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
}
