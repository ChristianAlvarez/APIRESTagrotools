<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

use DB;
use Hash;
use JWTAuth;
use Carbon\Carbon;
use PushNotification;

use App\Reap;
use App\Device;
use App\Company;
use App\Picking;
use App\DetailsReap;
use App\DeviceToken;
use App\MovementReap;
use App\DetailsDevice;

class MobileController extends Controller
{
    public function __construct()
	{
		$this->middleware('jwt.auth', ['except' => ['authenticate', 'posttoken', 'storeMovementReap', 'userActive', 'storeDetailsReapManual', 'updateDetailsReapManual', 'sync']]);
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
                return response()->json([
                    'Error Credentials' => "Credenciales invalidas"
                ]);
	        }
	        else
	        {
	            //$user = Picking::where('pers_id', $request->pers_id)->first();
                
                $detail = DeviceToken::where('pers_id', $request->pers_id)
                                     ->where('devi_id', $request->devi_id)
                                     ->first();
                	
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

                        //dd(count($Login));
		                   
		                if (count($Login) > 0) 
		                {
		                   
		                    $Picking = Picking::where('pers_id',  $request->pers_id)->get();

		                    $cpny_id = $Login->pluck('cpny_id');

		                    $Company = Company::whereIn('cpny_id', $cpny_id)->get();       

		                    $Reap = Reap::whereIn('cpny_id', $cpny_id)
                                          ->where('pers_id', $request->pers_id)->get();

		                    $reap_id = $Reap->pluck('reap_id');

		                    $DetailsReap = DetailsReap::whereIn('reap_id', $reap_id)->get(); 

		                    $Data = [
		                        'Company'       => $Company,
		                        'Picking'       => $Picking,
		                        'Reap'          => $Reap,
		                        'DetailsReap'   => $DetailsReap
		                    ];

                            //dd(Carbon::now());
                            //dd($detail);

							//Actualizamos la fecha de ultima conexión
                			$detail->last_conection = Carbon::now();
                			$detail->save();

		                    // all good so return the token
        					return response()->json(compact('token', 'Data'));
		                }
		                else
		                {
		                    return response()->json([
		                        'Codigo' => "1",
		                        'Mensaje' => "Usuarios no asociado al dispositivo " . $request->devi_id
		                    ]);
		                }    
   
		            } 
		            catch(\Illuminate\Database\QueryException $e) 
		            {
		                return response()->json([
		                    'Codigo' => "2",
		                    'Descripcion' => $e
		                ]);
		                
                	}
                //}	
                //else
                //{
                //    $user = Picking::where('pers_id', $request->pers_id)->first()

                //	$Data = [
		        //        'User' => $user
		        //    ];
                	// all good so return the token
        		//	return response()->json(compact('token', 'Data'));
                //}                    
	        }                  
        } 
        catch (JWTException $e) 
        {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }      
    }

    public function posttoken(Request $request)
    {
        $requests = $request->only('registrationToken', 'devi_id', 'pers_id'); 
        
        //dd($requests['registrationToken']);
        $Token   = $requests['registrationToken'];
        $Device  = $requests['devi_id'];
        $Pers_id = $requests['pers_id'];

        $data = [
            'Token'     => $Token, 
            'Device'    => $Device,
            'Pers_id'   => $Pers_id
        ]; 

        $rules = [
            'Token'     => 'required',
            'Device'    => 'required|exists:devicetoken,devi_id',
            'Pers_id'   => 'required|exists:picking,pers_id'
        ];

        $messages = [
            'Token.required' => 'Token - Token es requerido',
            'Device.required' => 'Device - Device es requerido',
            'Device.exists'   => 'Device - Device debe existir en tabla DeviceToken',
            'Pers_id.required' => 'Pers_id - Pers_id es requerido',
            'Pers_id.exists'   => 'Pers_id - Pers_id debe existir en tabla picking',
        ];       

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error validacion' => $validator->errors()
            ]);
        }else {
        
            $DeviceToken = DeviceToken::where('devi_id', $Device)
                                      ->where('devi_active', 1)
                                      ->where('pers_id', $Pers_id)
                                      ->where('devi_token', '')
                                      ->update(['devi_token' => $Token]);

            if (count($DeviceToken) > 0) {
                $Data = [
                        'Token' => $DeviceToken
                    ];
                    
                return response()->json([
                        'Data'   => $Data,
                        'Codigo' => "2"
                    ]);

            } 
            else{
                $Data = [
                    'Token' => "Error"
                ];

                return response()->json([
                    'Data'   => $Data,
                    'Codigo' => "1"
                ]);
            }                           
        }
    }

    public function userActive(Request $request)
    {
        $requests = $request->only('pers_id'); 
        
        $Pers_id = $requests['pers_id'];

        $data = [
            'Pers_id'   => $Pers_id
        ]; 

        $rules = [
            'Pers_id'   => 'required|exists:picking,pers_id'
        ];

        $messages = [
            'Pers_id.required' => 'Pers_id - Pers_id es requerido',
            'Pers_id.exists'   => 'Pers_id - Pers_id debe existir en tabla picking',
        ];       

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error validacion' => $validator->errors()
            ]);
        }else {

            $user = Picking::where('pers_id', $request->pers_id)
                           ->where('pick_active', 1)
                           ->get();

            if (count($user)) {
                return response()->json([
                    'Picking' => $user
                ]);
            }
            else
            {
                return response()->json([
                    'Codigo' => "Error",
                    'Mensaje' => "Usuarios inactivo "
                ]);
            }

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sync(Request $request)
    {
        //REQUEST
        $collection = collect($request->all());

        //COLLECTIONS
        $movementreap = collect($collection['movementreap']); 
        $detailsreaps = collect($collection['detailsreap']);
         
        //VARIABLES
        $message = '';
        $MovementReapSuccess = Collection::make(new MovementReap);
        $MovementReapFails = Collection::make(new MovementReap);

        $DetailsReapSuccess = Collection::make(new DetailsReap);
        $DetailsReapFails = Collection::make(new DetailsReap);

        try{

            //MOVEMENTREAP
            if ($movementreap) {
                try 
                {
                    $MovementReap = new \App\MovementReap();
                    $MovementReap = MovementReap::insert($movementreap->toArray());

                    if (!$MovementReap) {
                        $MovementReapFails->push($movementreap);
                    }
                    else
                    {
                        $MovementReapSuccess->push($movementreap);
                    }
                }
                catch(\Illuminate\Database\QueryException $e)
                {
                    $MovementReapFails->push($movementreap);
                    $message = $e;
                }
            } 

            //DETAILSREAP
            if ($detailsreaps) {
                try {
                    foreach  ($detailsreaps as $id_key => $detailsreap) {

                        if ($detailsreap['card_identification_old'] == "null")
                        {
                            $DetailsReap = new \App\DetailsReap();
                            $DetailsReap->reap_id = $detailsreap['reap_id'];
                            $DetailsReap->cpny_id = $detailsreap['cpny_id'];
                            $DetailsReap->card_identification = $detailsreap['card_identification'];
                            $DetailsReap->pers_id = $detailsreap['pers_id'];
                            $DetailsReap->pers_name = $detailsreap['pers_name'];
                            $DetailsReap->quad_name = $detailsreap['quad_name'];

                            $DetailsReap->dere_status_card = $detailsreap['dere_status_card'];
                            $DetailsReap->dere_record = $detailsreap['dere_record'];
                            $DetailsReap->row_mode = $detailsreap['row_mode'];
                            $DetailsReap->created_at = $detailsreap['created_at'];
                            $DetailsReap->updated_at = $detailsreap['updated_at'];
                            $DetailsReap->dtrp_line_number = -1;
                            $DetailsReap->dere_update = $detailsreap['dere_update'];
                            $DetailsReap->dere_obs = $detailsreap['dere_obs'];

                            $DetailsReap->save();

                            if (!$DetailsReap) {
                                $DetailsReapFails->push($detailsreaps);
                            }
                            else{
                                $DetailsReapSuccess->push($detailsreaps);
                            }
                        }
                        elseif ($detailsreap['card_identification_old'] == "unsubscribe")
                        {

                            $detail = DetailsReap::where('pers_id', $detailsreap['pers_id'])
                                                 ->where('reap_id', $detailsreap['reap_id'])
                                                 ->where('cpny_id', $detailsreap['cpny_id'])
                                                 ->first();

                            if ($detail)
                            {
                                $DetailsReap =  DetailsReap::where(['reap_id' => $detailsreap['reap_id']])
                                                           ->where(['cpny_id' => $detailsreap['cpny_id']])
                                                           ->where(['card_identification' => $detailsreap['card_identification']])
                                                           ->update(['pers_id' => $detailsreap['pers_id'],
                                                                     'dere_status_card' => $detailsreap['dere_status_card'],
                                                                     'dere_record' => $detailsreap['dere_record'],
                                                                     'row_mode'     => $detailsreap['row_mode'],
                                                                     'card_identification' => $detailsreap['card_identification'],
                                                                     'dere_update' => $detailsreap['dere_update'],
                                                                     'created_at' => $detailsreap['created_at'],
                                                                     'updated_at' => $detailsreap['updated_at'],
                                                                     'dere_obs' => $detailsreap['dere_obs']]);

                                if (!$DetailsReap) {
                                    $DetailsReapFails->push($detailsreaps);
                                }
                                else{
                                    $DetailsReapSuccess->push($detailsreaps);
                                }
                            }
                            else
                            {

                                $DetailsReap = new \App\DetailsReap();
                                $DetailsReap->reap_id = $detailsreap['reap_id'];
                                $DetailsReap->cpny_id = $detailsreap['cpny_id'];
                                $DetailsReap->card_identification = $detailsreap['card_identification'];
                                $DetailsReap->pers_id = $detailsreap['pers_id'];
                                $DetailsReap->pers_name = $detailsreap['pers_name'];
                                $DetailsReap->quad_name = $detailsreap['quad_name'];

                                $DetailsReap->dere_status_card = $detailsreap['dere_status_card'];
                                $DetailsReap->dere_record = $detailsreap['dere_record'];
                                $DetailsReap->row_mode = $detailsreap['row_mode'];
                                $DetailsReap->created_at = $detailsreap['created_at'];
                                $DetailsReap->updated_at = $detailsreap['updated_at'];
                                $DetailsReap->dtrp_line_number = -1;
                                $DetailsReap->dere_update = $detailsreap['dere_update'];
                                $DetailsReap->dere_obs = $detailsreap['dere_obs'];

                                $DetailsReap->save();

                                if (!$DetailsReap) {
                                    $DetailsReapFails->push($detailsreaps);
                                }
                                else{
                                    $DetailsReapSuccess->push($detailsreaps);
                                }
                            }    
                        }
                        else
                        {
                            $detail = DetailsReap::where('pers_id', $detailsreap['pers_id'])
                                                 ->where('reap_id', $detailsreap['reap_id'])
                                                 ->where('cpny_id', $detailsreap['cpny_id'])
                                                 ->first();

                            if ($detail)
                            {
                                $DetailsReap =  DetailsReap::where(['reap_id' => $detailsreap['reap_id']])
                                                   ->where(['cpny_id' => $detailsreap['cpny_id']])
                                                   ->where(['card_identification' => $detailsreap['card_identification_old']])
                                                   ->update(['pers_id' => $detailsreap['pers_id'],
                                                             'dere_status_card' => $detailsreap['dere_status_card'],
                                                             'dere_record' => $detailsreap['dere_record'],
                                                             'row_mode'     => $detailsreap['row_mode'],
                                                             'card_identification' => $detailsreap['card_identification'],
                                                             'dere_update' => $detailsreap['dere_update'],
                                                             'created_at' => $detailsreap['created_at'],
                                                             'updated_at' => $detailsreap['updated_at'],
                                                             'dere_obs' => $detailsreap['dere_obs']]);

                                if (!$DetailsReap) {
                                    $DetailsReapFails->push($detailsreaps);
                                }
                                else{
                                    $DetailsReapSuccess->push($detailsreaps);
                                }
                            }
                            else
                            {

                                $DetailsReap = new \App\DetailsReap();
                                $DetailsReap->reap_id = $detailsreap['reap_id'];
                                $DetailsReap->cpny_id = $detailsreap['cpny_id'];
                                $DetailsReap->card_identification = $detailsreap['card_identification'];
                                $DetailsReap->pers_id = $detailsreap['pers_id'];
                                $DetailsReap->pers_name = $detailsreap['pers_name'];
                                $DetailsReap->quad_name = $detailsreap['quad_name'];

                                $DetailsReap->dere_status_card = $detailsreap['dere_status_card'];
                                $DetailsReap->dere_record = $detailsreap['dere_record'];
                                $DetailsReap->row_mode = $detailsreap['row_mode'];
                                $DetailsReap->created_at = $detailsreap['created_at'];
                                $DetailsReap->updated_at = $detailsreap['updated_at'];
                                $DetailsReap->dtrp_line_number = -1;
                                $DetailsReap->dere_update = $detailsreap['dere_update'];
                                $DetailsReap->dere_obs = $detailsreap['dere_obs'];

                                $DetailsReap->save();

                                if (!$DetailsReap) {
                                    $DetailsReapFails->push($detailsreaps);
                                }
                                else{
                                    $DetailsReapSuccess->push($detailsreaps);
                                }
                            }    
                        }
                    }
                }
                catch(\Illuminate\Database\QueryException $e)
                {
                    $DetailsReapFails->push($detailsreaps);
                    $message = $e;
                }
            }

        }catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'Message' => $e
            ]);
        }  
        
        /*
        $MovementReapSuccess = [
            'MovementReap'  => $MovementReapSuccess
        ];

        $MovementReapFails = [
            'MovementReap'  => $MovementReapFails
        ];

        $DetailsReapSuccess = [
            'DetailsReap'   => $DetailsReapSuccess
        ];

        $DetailsReapFails = [
            'DetailsReap'   => $DetailsReapFails
        ];
        */

        $Data = [
            'MovementReapSuccess'   => $MovementReapSuccess,
            'MovementReapFails'     => $MovementReapFails,
            'DetailsReapSuccess'    => $DetailsReapSuccess,
            'DetailsReapFails'      => $DetailsReapFails
        ];

        return response()->json(compact('Status' => "Ok", 'Data', 'Message' => $message));

        /*return response()->json([
            'Status' => "Ok",
            'Data' => $Data,
            'Message' => $message
        ], 200);*/

    }

    /**
     * Store a MovementReap newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMovementReap(Request $request)
    {
        $move = collect($request->all()); 
        $comp = collect($move['movementreap']); 

        try 
            {
                $MovementReap = new \App\MovementReap();
                $MovementReap = MovementReap::insert($comp->toArray());

                if (!$MovementReap) {
                    return response()->json([
                        'Codigo' => "1"
                    ]);
                }
                else
                {

                    return response()->json([
                        'Success' => "Success"
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
     * Store a MovementReap newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDetailsReapManual(Request $request)
    {
        $move = collect($request->all()); 
        $comp = collect($move['detailsreap']); 

        try {
            $DetailsReap = DetailsReap::insert($comp->toArray());
                
            if ($DetailsReap) {
                return response()->json([
                    'Codigo' => "1"
                ]);
            }
            else{
                return response()->json([
                    'Codigo' => "2",
                    'Descripcion' => "Error insert DetailsReap"
                ]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
    }

    public function updateDetailsReapManual(Request $request)
    {

        $move = collect($request->all()); 
        $detailsreaps = collect($move['detailsreap']); 

        try {
            foreach  ($detailsreaps as $id_key => $detailsreap) {

                if ($detailsreap['card_identification_old'] == "null")
                {
                    $DetailsReap = new \App\DetailsReap();
                    $DetailsReap->reap_id = $detailsreap['reap_id'];
                    $DetailsReap->cpny_id = $detailsreap['cpny_id'];
                    $DetailsReap->card_identification = $detailsreap['card_identification'];
                    $DetailsReap->pers_id = $detailsreap['pers_id'];
                    $DetailsReap->pers_name = $detailsreap['pers_name'];
                    $DetailsReap->quad_name = $detailsreap['quad_name'];

                    $DetailsReap->dere_status_card = $detailsreap['dere_status_card'];
                    $DetailsReap->dere_record = $detailsreap['dere_record'];
                    $DetailsReap->row_mode = $detailsreap['row_mode'];
                    $DetailsReap->created_at = $detailsreap['created_at'];
                    $DetailsReap->updated_at = $detailsreap['updated_at'];
                    $DetailsReap->dtrp_line_number = -1;
                    $DetailsReap->dere_update = $detailsreap['dere_update'];
                    $DetailsReap->dere_obs = $detailsreap['dere_obs'];

                    $DetailsReap->save();
                }
                elseif ($detailsreap['card_identification_old'] == "unsubscribe")
                {

                    $detail = DetailsReap::where('pers_id', $detailsreap['pers_id'])
                                         ->where('reap_id', $detailsreap['reap_id'])
                                         ->where('cpny_id', $detailsreap['cpny_id'])
                                         ->first();

                    if ($detail)
                    {
                        $DetailsReap =  DetailsReap::where(['reap_id' => $detailsreap['reap_id']])
                                           ->where(['cpny_id' => $detailsreap['cpny_id']])
                                           ->where(['card_identification' => $detailsreap['card_identification']])
                                            ->update(['pers_id' => $detailsreap['pers_id'],
                                                     'dere_status_card' => $detailsreap['dere_status_card'],
                                                     'dere_record' => $detailsreap['dere_record'],
                                                     'row_mode'     => $detailsreap['row_mode'],
                                                     'card_identification' => $detailsreap['card_identification'],
                                                     'dere_update' => $detailsreap['dere_update'],
                                                     'created_at' => $detailsreap['created_at'],
                                                     'updated_at' => $detailsreap['updated_at'],
                                                     'dere_obs' => $detailsreap['dere_obs']]);
                    }
                    else
                    {

                        $DetailsReap = new \App\DetailsReap();
                        $DetailsReap->reap_id = $detailsreap['reap_id'];
                        $DetailsReap->cpny_id = $detailsreap['cpny_id'];
                        $DetailsReap->card_identification = $detailsreap['card_identification'];
                        $DetailsReap->pers_id = $detailsreap['pers_id'];
                        $DetailsReap->pers_name = $detailsreap['pers_name'];
                        $DetailsReap->quad_name = $detailsreap['quad_name'];

                        $DetailsReap->dere_status_card = $detailsreap['dere_status_card'];
                        $DetailsReap->dere_record = $detailsreap['dere_record'];
                        $DetailsReap->row_mode = $detailsreap['row_mode'];
                        $DetailsReap->created_at = $detailsreap['created_at'];
                        $DetailsReap->updated_at = $detailsreap['updated_at'];
                        $DetailsReap->dtrp_line_number = -1;
                        $DetailsReap->dere_update = $detailsreap['dere_update'];
                        $DetailsReap->dere_obs = $detailsreap['dere_obs'];

                        $DetailsReap->save();
                    }    
                }
                else
                {
                    $detail = DetailsReap::where('pers_id', $detailsreap['pers_id'])
                                         ->where('reap_id', $detailsreap['reap_id'])
                                         ->where('cpny_id', $detailsreap['cpny_id'])
                                         ->first();

                    if ($detail)
                    {
                        $DetailsReap =  DetailsReap::where(['reap_id' => $detailsreap['reap_id']])
                                           ->where(['cpny_id' => $detailsreap['cpny_id']])
                                           ->where(['card_identification' => $detailsreap['card_identification_old']])
                                           ->update(['pers_id' => $detailsreap['pers_id'],
                                                     'dere_status_card' => $detailsreap['dere_status_card'],
                                                     'dere_record' => $detailsreap['dere_record'],
                                                     'row_mode'     => $detailsreap['row_mode'],
                                                     'card_identification' => $detailsreap['card_identification'],
                                                     'dere_update' => $detailsreap['dere_update'],
                                                     'created_at' => $detailsreap['created_at'],
                                                     'updated_at' => $detailsreap['updated_at'],
                                                     'dere_obs' => $detailsreap['dere_obs']]);
                    }
                    else
                    {

                        $DetailsReap = new \App\DetailsReap();
                        $DetailsReap->reap_id = $detailsreap['reap_id'];
                        $DetailsReap->cpny_id = $detailsreap['cpny_id'];
                        $DetailsReap->card_identification = $detailsreap['card_identification'];
                        $DetailsReap->pers_id = $detailsreap['pers_id'];
                        $DetailsReap->pers_name = $detailsreap['pers_name'];
                        $DetailsReap->quad_name = $detailsreap['quad_name'];

                        $DetailsReap->dere_status_card = $detailsreap['dere_status_card'];
                        $DetailsReap->dere_record = $detailsreap['dere_record'];
                        $DetailsReap->row_mode = $detailsreap['row_mode'];
                        $DetailsReap->created_at = $detailsreap['created_at'];
                        $DetailsReap->updated_at = $detailsreap['updated_at'];
                        $DetailsReap->dtrp_line_number = -1;
                        $DetailsReap->dere_update = $detailsreap['dere_update'];
                        $DetailsReap->dere_obs = $detailsreap['dere_obs'];

                        $DetailsReap->save();
                    }    

                    /*$DetailsReap =  DetailsReap::where(['reap_id' => $detailsreap['reap_id']])
                                           ->where(['cpny_id' => $detailsreap['cpny_id']])
                                           ->where(['card_identification' => $detailsreap['card_identification_old']])
                                           ->update(['pers_id' => $detailsreap['pers_id'],
                                                     'pers_name' => $detailsreap['pers_name'],
                                                     'quad_name' => $detailsreap['quad_name'],
                                                     'dere_status_card' => $detailsreap['dere_status_card'],
                                                     'dere_record' => $detailsreap['dere_record'],
                                                     'row_mode'     => $detailsreap['row_mode'],
                                                     'card_identification' => $detailsreap['card_identification'],
                                                     'dere_update' => $detailsreap['dere_update'],
                                                     'created_at' => $detailsreap['created_at'],
                                                     'updated_at' => $detailsreap['updated_at'],
                                                     'dere_obs' => $detailsreap['dere_obs']]);

                                           $DetailsReap =  DetailsReap::where(['reap_id' => $detailsreap['reap_id']])
                                           ->where(['cpny_id' => $detailsreap['cpny_id']])
                                           ->where(['card_identification' => $detailsreap['card_identification_old']])
                                           ->update(['pers_id' => $detailsreap['pers_id'],
                                                     'pers_name' => $detailsreap['pers_name'],
                                                     'quad_name' => $detailsreap['quad_name'],
                                                     'dere_status_card' => $detailsreap['dere_status_card'],
                                                     'dere_record' => $detailsreap['dere_record'],
                                                     'row_mode'     => $detailsreap['row_mode'],
                                                     'card_identification' => $detailsreap['card_identification'],
                                                     'dere_update' => $detailsreap['dere_update'],
                                                     'created_at' => $detailsreap['created_at'],
                                                     'updated_at' => $detailsreap['updated_at'],
                                                     'dere_obs' => $detailsreap['dere_obs']]);*/
                }
            }

            return response()->json([
                'Codigo' => "2"
            ]);

        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }  
    }

    public function index()
    {
        /*$devices = PushNotification::Device('token', array('badge' => 5));

        $deviceToken = 'https://gcm-http.googleapis.com/gcm/send';
        $picking = PushNotification::app('appNameAndroid')
                ->to($deviceToken)
                ->send('Hello World, im a push message');*/

        $devices = PushNotification::DeviceCollection(array(
            PushNotification::Device('token', array('badge' => 5)),
            PushNotification::Device('token1', array('badge' => 1)),
            PushNotification::Device('token2')
        ));
        $message = PushNotification::Message('Message Text',array(
            'badge' => 1,
            'sound' => 'example.aiff',
            
            'actionLocKey' => 'Action button title!',
            'locKey' => 'localized key',
            'locArgs' => array(
                'localized args',
                'localized args',
            ),
            'launchImage' => 'image.jpg',
            
            'custom' => array('custom data' => array(
                'we' => 'want', 'send to app'
            ))
        ));

        $collection = PushNotification::app('appNameAndroid')
            ->to($devices)
            ->send($message);

        return $collection;
    }

}
