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
use App\MovementReap;

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

        /*if ($request->has('Company')) {
            //$comp = $request->all();

            if (count($request->all()) > 1) {
                $this->InsertCompany($request);
            }

            //dd(count($request->all()));
        }*/

        $companys = collect($request->all()); 
        $comp = collect($companys['Company']);
 
        //$results = $companys->slice(0, -1); 

        $insert = $comp->where('row_mode', 1);
        $update = $comp->where('row_mode', 0);

        //dd(count($insert));

		//dd($update);
        //INSERT       
        if (count($insert) > 1) {
        	
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->InsertCompanys($arr->toArray());
                //dd($arr);
            });
        }
        else
        {
            $this->InsertCompany($insert);
        }

        //UPDATE
        if (count($update) > 0) {
        	
            $this->UpdateCompany($update->toArray());
        }

        return response()->json([
            'Codigo' => "2"
        ]);
    }

    private function InsertCompany($company)
    {
        try {
            $Company = new \App\Company();
            $Company->cpny_id = $company->cpny_id;
            $Company->cpny_name = $company->cpny_name;
            $Company->cpny_active = $company->cpny_active;
            $Company->cpny_record = $company->cpny_record;
            $Company->created_at = $company->created_at;
            $Company->updated_at = $company->updated_at;
            $Company->save();

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

    private function InsertCompanys($companys)
    {
       try {
                $Company = new \App\Company();
                $Company = Company::insert($companys);

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

    private function UpdateCompany($companys)
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

        //$results = $pickings->slice(0, -1);  
        $insert = $pickings->where('row_mode', 1);
        $update = $pickings->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->InsertPicking($arr->toArray());
                //$this->Insert($comp);
            });
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
			    $Picking->pers_id 	  = $picking->pers_id;
			    $Picking->cpny_id 	  = $picking->cpny_id;
			    $Picking->pers_name   = $picking->pers_name;
			    $Picking->password 	  = Hash::make($picking->password);
			    $Picking->pick_active = $picking->pick_active;
			    $Picking->pick_record = $picking->pick_record;

				$Picking->save();
			}
                /*$Picking = new \App\Picking();
                $Picking = Picking::insert($pickings);
                if (!$Picking) {
                    return response()->json([
                        'Codigo' => "1"
                    ]);
                }*/
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
                $Picking =  Picking::where(['pers_id' => $company['pers_id']])
                                   ->where(['cpny_id' => $company['cpny_id']])
                                   ->update(['pers_name' 	=> $company['pers_name'],
                                             'password' 	=> Hash::make($company['password|']),
                                             'pick_active' 	=> $company['pick_active'],
                                             'pick_record' 	=> $company['pick_record']]);
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
       
        //$results = $devices->slice(0, -1);  

        $insert = $devices->where('row_mode', 1);
        $update = $devices->where('row_mode', 0);
       
        /*if ($devices->count() > 1) {

            dd($devices->count());
        }*/

        //INSERT       
        if (count($insert) > 0) {
            dd("insert");
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->InsertDevices($arr->toArray());
            });
        }

        //UPDATE
        if (count($update) > 0) {
            dd("update");
            $this->UpdateDevice($update->toArray());
        }

        return response()->json([
            'Codigo' => "2"
        ]);
    }

    private function InsertDevices($devices)
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

    private function UpdateDevice($devices)
    {
        try {
            foreach  ($devices as $id_key => $device) {
                $Device =  Device::where(['devi_id' => $device['devi_id']])
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
        //$results = $detailsdevices->slice(0, -1);       

        $insert = $detailsdevices->where('row_mode', 1);
        $update = $detailsdevices->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->InsertDetailsDevice($arr->toArray());
                //$this->Insert($comp);
            });
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

    private function UpdateDetailsDevice($detailsdevices)
    {
        try {
            foreach  ($detailsdevices as $id_key => $detailsdevice) {
                $DetailsDevice =  DetailsDevice::where(['devi_id' => $detailsdevice['devi_id']])
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
        //$results = $reaps->slice(0, -1);         

        $insert = $reaps->where('row_mode', 1);
        $update = $reaps->where('row_mode', 0);
        
        //INSERT       
        if (count($insert) > 0) {
        
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->InsertReap($arr->toArray());
            });
                
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
                $Reap = new \App\Reap();
                $Reap = Reap::insert($reaps);

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
                                       'reap_record' => $reap['reap_record']]);
            }
        } catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                    'Codigo' => "1",
                    'Descripcion' => $e
                ]);
        }        
    }

    //DETAILSREAP
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveDetailsReap(Request $request)
    {
        $detailsreaps = collect($request->all());  
        //$results = $detailsreaps->slice(0, -1);    

        $insert = $detailsreaps->where('row_mode', 1);
        $update = $detailsreaps->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->InsertDetailsReap($arr->toArray());
                //$this->Insert($comp);
            });
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
                $DetailsReap = new \App\DetailsReap();
                $DetailsReap = DetailsReap::insert($detailsreaps);
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
                                           ->update(['pers_name' => $detailsreap['pers_name'],
                                                     'quad_name' => $detailsreap['quad_name'],
                                                     'dere_status_card' => $detailsreap['dere_status_card'],
                                                     'dere_record' => $detailsreap['dere_record']]);
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
}
