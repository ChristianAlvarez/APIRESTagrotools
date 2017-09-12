<?php

namespace App\Http\Controllers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use DB;
use Hash;
use Carbon\Carbon;

use App\Reap;
use App\Device;
use App\Company;
use App\Picking;
use App\DetailsReap;
use App\DeviceToken;
use App\MovementReap;
use App\DetailsDevice;

class DesktopController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth', ['except' => ['authenticate']]);
	}

    public function authenticate(Request $request)
    {   
        // grab credentials from the request
        $credentials = $request->only('pers_id', 'password');       

        $rules = [
            'pers_id'   => 'required|max:12',
            'password'  => 'required',
        ];

        $messages = [
            'pers_id.required'  => 'pers_id - Identificador del usuario es requerido',
            'pers_id.max'       => 'Pers_id - Id maximo de caracteres permitidos 12',
            'password.required' => 'password es requerido',
        ];       

        $validator = Validator::make($credentials, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error validacion' => $validator->errors()
            ]);
        }
       
        $user = null;
        $token = null;

        //$customClaims = ['pers_role' => '1'];
        
    	try {
            if (!$request->has('password')) {
                $user = User::where('pers_id', $request->pers_id)->first();
                if (empty($user)) {
                    return response()->json(['error' => 'usuario del servicio no existe'], 500);
                }

                if (!$token = JWTAuth::fromUser($user)) {
                    return response()->json(['error' => 'invalid_credentials'], 500);
                }
                
            }else {
        		if (!$token = JWTAuth::attempt($credentials)) {
        			return response()->json(['error' => 'invalid_credentials'], 500);
        		}

                $user = JWTAuth::toUser($token);
            }
    	} catch (JWTException $ex) {
    		return response()->json(['error' => $ex], 500);
    	}

    	return response()->json(compact('token'));
    }

    //COMPANY
    public function saveCompany(Request $request)
    {

        $companys = collect($request->all()); 
        $comp = collect($companys['Company']);
 
        //$results = $companys->slice(0, -1); 

        $insert = $comp->where('row_mode', 1);
        $update = $comp->where('row_mode', 0);     
    
        //INSERT       
        if (count($insert) > 0) {
            $this->InsertCompanys($insert);    
        }

        //UPDATE
        if (count($update) > 0) {      	
            $this->UpdateCompanys($update->toArray());
        }   

        return response()->json([
            'Codigo' => "2"
        ]);
    }

    private function InsertCompanys($companys)
    {
    
        try {
                
            $Company = Company::insert($companys->toArray());

            if (!$Company) {
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

    private function UpdateCompanys($companys)
    {
        try {
            foreach  ($companys as $id_key => $company) {
                $Company =  Company::where(['cpny_id' => $company['cpny_id']])
                                   ->update(['cpny_name' => $company['cpny_name'],
                                             'cpny_active' => $company['cpny_active'],
                                             'cpny_record' => $company['cpny_record']]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }  
    }

    //PICKING
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savePicking(Request $request)
    { 
        $pickings = collect($request->all());   
        $comp = collect($pickings['Picking']);

        $insert = $comp->where('row_mode', 1);
        $update = $comp->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $this->InsertPicking($insert);
        }

        //UPDATE
        if (count($update) > 0) {
            $this->UpdatePicking($update->toArray());
        }

        return response()->json([
            'Codigo' => "2"
        ]);
    }

    private function InsertPicking($pickings)
    {
        try {

        	foreach ($pickings as $picking) {
        		$Picking = new \App\Picking();
			    $Picking->pers_id 	  = $picking['pers_id'];
			    $Picking->cpny_id 	  = $picking['cpny_id'];
			    $Picking->pers_name   = $picking['pers_name'];
			    $Picking->password 	  = Hash::make($picking['password']);
			    $Picking->pick_active = $picking['pick_active'];
			    $Picking->pick_record = $picking['pick_record'];
                $Picking->row_mode = $picking['row_mode'];

				$Picking->save();
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

    private function UpdatePicking($pickings)
    {
        try {
            foreach  ($pickings as $id_key => $picking) {
                $Picking =  Picking::where(['pers_id' => $picking['pers_id']])
                                   ->where(['cpny_id' => $picking['cpny_id']])
                                   ->update(['pers_name' 	=> $picking['pers_name'],
                                             'password' 	=> Hash::make($picking['password']),
                                             'pick_active' 	=> $picking['pick_active'],
                                             'pick_record' 	=> $picking['pick_record'],
                                             'row_mode'     => $picking['row_mode']]);
                }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
    }

    //DEVICE
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveDevice(Request $request)
    {
       
        $devices = collect($request->all());         
        $comp = collect($devices['Device']);

        $insert = $comp->where('row_mode', 1);
        $update = $comp->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $this->InsertDevices($insert);
        }

        //UPDATE
        if (count($update) > 0) {
            $this->UpdateDevice($update->toArray());
        }

        return response()->json([
            'Codigo' => "2"
        ]);
    }

    private function InsertDevices($devices)
    {
        try {
                $Device = Device::insert($devices->toArray());           

                if (!$Device) {
                    return response()->json([
                        'Codigo' => "1"
                    ]);
                }
                else
                {
                    foreach ($devices as $device) {

                        $Devicetoken = DeviceToken::where('devi_id', $device['devi_id'])->get();
                        
                        if (count($Devicetoken) == 0)
                        {
                            $DeviceToken = new \App\DeviceToken();
                            $DeviceToken->devi_id = $device['devi_id'];
                            $DeviceToken->devi_token = '';
                            $DeviceToken->devi_active = 1;
                            $DeviceToken->pers_id = '';
                            $DeviceToken->save();   

                            if (!$DeviceToken) {
                                return response()->json([
                                    'Error' => "Error insert DeviceToken"
                                ]);
                            }     
                        }
                    }
                }

        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
    }

    private function UpdateDevice($devices)
    {
        try {
            foreach  ($devices as $id_key => $device) {
                $Device =  Device::where(['devi_id' => $device['devi_id']])
                                  ->where(['cpny_id' => $device['cpny_id']])
                                  ->update(['devi_name' => $device['devi_name'],
                                            'devi_active' => $device['devi_active'],
                                            'devi_record' => $device['devi_record'],
                                            'row_mode'    => $device['row_mode']]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }        
    }

    //DETAILSDEVICE
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveDetailsDevice(Request $request)
    {
        $detailsdevices = collect($request->all()); 
        $comp = collect($detailsdevices['DetailDevice']);      

        $insert = $comp->where('row_mode', 1);
        $update = $comp->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $this->InsertDetailsDevice($insert);
        }

        //UPDATE
        if (count($update) > 0) {
            $this->UpdateDetailsDevice($update->toArray());
        }

        return response()->json([
            'Codigo' => "2"
        ]);
    }
    
    private function InsertDetailsDevice($detailsdevices)
    {
       
        try {
            $DetailsDevice = DetailsDevice::insert($detailsdevices->toArray());

            if (!$DetailsDevice) {
                
                return response()->json([
                    'Codigo' => "1"
                ]);
            }
            else
            {
                foreach ($detailsdevices as $devices) {
                        
                        $Detail = DeviceToken::where('devi_id', $devices['devi_id'])
                                             ->where('devi_active', 1)
                                             ->update(['pers_id' => $devices['pers_id']]);

                }
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }
    }

    private function UpdateDetailsDevice($detailsdevices)
    {
        try {
            foreach  ($detailsdevices as $id_key => $detailsdevice) {
                $DetailsDevice =  DetailsDevice::where(['devi_id' => $detailsdevice['devi_id']])
                                                 ->where(['cpny_id' => $detailsdevice['cpny_id']])
                                                 ->where(['pers_id' => $detailsdevice['pers_id']])
                                                 ->update(['dtde_active' => $detailsdevice['dtde_active'],
                                                           'dtde_record' => $detailsdevice['dtde_record'],
                                                           'row_mode'    => $detailsdevice['row_mode']]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }       
    }

    //REAP
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveReap(Request $request)
    {
        $reaps = collect($request->all()); 
        $comp = collect($reaps['Reap']);         

        $insert = $comp->where('row_mode', 1);
        $update = $comp->where('row_mode', 0);
        
        //INSERT       
        if (count($insert) > 0) {
            $this->InsertReap($insert);           
        }

        //UPDATE
        if (count($update) > 0) {
            $this->UpdateReap($update->toArray());
        }

        return response()->json([
            'Codigo' => "2"
        ]);
    }

    private function InsertReap($reaps)
    {
       
        try {
                $Reap = Reap::insert($reaps->toArray());
               
                if (!$Reap) {
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

    private function UpdateReap($reaps)
    {
        try {
            foreach  ($reaps as $id_key => $reap) {
                $Reap =  Reap::where(['reap_id' => $reap['reap_id']])
                             ->where(['cpny_id' => $reap['cpny_id']])
                             ->update(['stus_id' => $reap['stus_id'],
                                       'pers_id' => $reap['pers_id'],
                                       'pers_name' => $reap['pers_name'],
                                       'land_name' => $reap['land_name'],
                                       'prun_name' => $reap['prun_name'],
                                       'ticu_name' => $reap['ticu_name'],
                                       'vare_name' => $reap['vare_name'],
                                       'mere_name' => $reap['mere_name'],
                                       'reap_record' => $reap['reap_record'],
                                       'row_mode'    => $reap['row_mode']]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }        
    }

    //DETAILSREAP
    public function updateDetailManual(Request $request)
    {
        $detailsreaps = collect($request->all());
        $comp = collect($detailsreaps['DetailsReap']); 

        foreach  ($comp as $id_key => $detail) {
            $Detalle =  DetailsReap::where(['reap_id' => $detail['reap_id']])
                                    ->where(['card_identification' => $detail['card_identification']])
                                    ->update(['pers_id' => 'N']);
            }
        
    }

    public function getDetailsReap($cpny_id)
    {
        $DetailsReap = DetailsReap::where('pers_id', '<>','N')
                                    ->where('cpny_id', $cpny_id)
                                    ->get();
        
        return Response()->json(array('DetailsReap' => $DetailsReap));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveDetailsReap(Request $request)
    {
        $detailsreaps = collect($request->all());  
        $comp = collect($detailsreaps['DetailReap']);    

        $insert = $comp->where('row_mode', 1);
        $update = $comp->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $this->InsertDetailsReap($insert);
        }

        //UPDATE
        if (count($update) > 0) {
            $this->UpdateDetailsReap($update->toArray());
        }

        return response()->json([
            'Codigo' => "2"
        ]);
    }

    private function InsertDetailsReap($detailsreaps)
    {
       
        try {
            $DetailsReap = DetailsReap::insert($detailsreaps->toArray());
                
            if (!$DetailsReap) {
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

    private function UpdateDetailsReap($detailsreaps)
    {
        try {
            foreach  ($detailsreaps as $id_key => $detailsreap) {
                $DetailsReap =  DetailsReap::where(['reap_id' => $detailsreap['reap_id']])
                                           ->where(['cpny_id' => $detailsreap['cpny_id']])
                                           ->where(['card_identification' => $detailsreap['card_identification']])
                                           ->update(['pers_id' => $detailsreap['pers_id'],
                                                     'pers_name' => $detailsreap['pers_name'],
                                                     'quad_name' => $detailsreap['quad_name'],
                                                     'dere_status_card' => $detailsreap['dere_status_card'],
                                                     'dere_record' => $detailsreap['dere_record'],
                                                     'row_mode'     => $detailsreap['row_mode']]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }  
    }

    //MOVEMENTREAP
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMovementReap($updated_at, $cpny_id)
    {
        $MovementReap = MovementReap::where('updated_at', '>', $updated_at)
                                    ->where('cpny_id', $cpny_id)
                                    ->get();
        
        return Response()->json(array('MovementReap' => $MovementReap));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postMovementReap(Request $request)
    {

        $move = collect($request->all()); 
        $comp = collect($move['MovementReap']);
        
        $credentials = $request->only('cpny_id', 'updated_at');

        $p = $comp[0];

        $cpny_id   = $p['cpny_id'];
        $updated_at  = $p['updated_at'];

        $id = MovementReap::where('updated_at', '<=', $updated_at)
                         ->where('cpny_id', $cpny_id)
                         ->where('more_record', 0)
                         ->pluck('id')->toArray();  

        dd($id);
        
        if (!empty($id)) 
        {

            try 
                {
                    
                  $MovementReap = MovementReap::whereIn('id',
                    $id)->update(['more_record' => 1]);

                  return response()->json([
                      'Codigo' => "2"
                  ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
            }
            catch(\Illuminate\Database\QueryException $e)
            {
                return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
            }
        }
        else
        {
          return response()->json([
              'Codigo' => "1",
              'Descripcion' => "No existen registros para la condición de su consulta"
          ]);
        }
        
    }
}
