<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PushNotification;

class RespaldoController extends Controller
{
        //COMPANY
    public function saveCompany(Request $request)
    {
        $companys = collect($request->all()); 
        //dd($companys  );
        $results = $companys->slice(0, -1); 

        $insert = $results->where('row_mode', 1);
        $update = $results->where('row_mode', 0);

		//dd($insert);
        //INSERT       
        if (count($insert) > 0) {
        	
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->InsertCompany($arr->toArray());
                //dd($arr);
            });
        }

        //UPDATE
        if (count($update) > 0) {
        	
            $this->UpdateCompany($update->toArray());
        }

        return response()->json([
            'Codigo' => "2"
        ]);
    }

    private function InsertCompany($companys)
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
        $results = $pickings->slice(0, -1);  
        $insert = $results->where('row_mode', 1);
        $update = $results->where('row_mode', 0);
       
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
        ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
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
        $insert = $results->where('row_mode', 1);
        $update = $results->where('row_mode', 0);
       
        //INSERT       
        if (count($insert) > 0) {
            $new = $insert->map(function ($comp) {
                unset($comp['row_mode']);
                $arr = collect($comp);
                $this->InsertDevice($arr->toArray());
            });
        }

        //UPDATE
        if (count($update) > 0) {
            $this->UpdateDevice($update->toArray());
        }

        return response()->json([
                        'Codigo' => "2"
                    ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
    }

    private function InsertDevice($devices)
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
        $results = $detailsdevices->slice(0, -1);       
        $insert = $results->where('row_mode', 1);
        $update = $results->where('row_mode', 0);
       
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
                    ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
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
        $insert = $results->where('row_mode', 1);
        $update = $results->where('row_mode', 0);
        
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
        ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
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
        $insert = $results->where('row_mode', 1);
        $update = $results->where('row_mode', 0);
       
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
                    ])->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
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

    public function index()
    {
        $devices = PushNotification::DeviceCollection(array(
                PushNotification::Device('eE8OK96aGT8:APA91bEoTAlRdNq0wcPsL4LFu69GpVJobQCaT9hBDVTVf2wwOw_I1omWYAsqnOtx6XTABUcdsSTLB64SPMJnVHClX6AElByN7-4c6l-I0lByt1RsntCPJQRXm-np0ToJ4nNI0QTtDW82')
        ));

        $message = "hola";

        
        $collection = PushNotification::app('appNameAndroid')
            ->to($devices)
            ->send($message);

        // get response for each device push
        foreach ($collection->pushManager as $push) {
            $response = $push->getAdapter()->getResponse();
        }

        var_dump($response);
    }
}
