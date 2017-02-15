<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreCompanyRequest;

class CompanyController extends Controller
{
    
    

    public function saveCompany(Request $request)
    {
        $companys = collect($request->all());    
        $insert = $companys->where('row_mode', 1);
        $update = $companys->where('row_mode', 0);
       
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

    private function Insert($companys)
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

    private function Update($companys)
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function SaveCompanyTest(Request $request)
    {
       
        $request = $request->all();
        
        try
            {
                $Company = new \App\Company();
                $Company = Company::insert($request);

                if ($Company) {
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

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UpdateCompanyTest(Request $request)
    {
       
        $companys = collect($request->all());    
        $com = $companys->where('row_mode', 0);
        dd(count($com));

        /*foreach  ($companys as $company) {
            dd($company['cpny_id']);
        }*/

        /*foreach ($companys as $key => $value) {
                            $Company = Company::find($key);
                            $Company->cpny_name = $value;
                            $Company->cpny_active = $value;
                            $Company->cpny_record = $value;
                            $Company->save();
                }*/

        foreach  ($companys as $id_key => $company) {
            $Company =  Company::where(['cpny_id' => $company['cpny_id']])
                               ->update(['cpny_name' => $company['cpny_name'],
                                         'cpny_active' => $company['cpny_active'],
                                         'cpny_record' => $company['cpny_record']]);
        }

        /*try
            {
                if (count($companys) > 1) {
                    foreach  ($companys as $id_key => $company) {
                      $Company =  Company::where(['cpny_id' => $id_key])->update($company);
                    }
                }
                else{
                    dd(count($companys));
                    $Company = Company::find($companys['cpny_id']);
                    $Company->cpny_name = $companys['cpny_name'];
                    $Company->cpny_active = $companys['cpny_active'];
                    $Company->cpny_record = $companys['cpny_record'];
                    $Company->save();
                }
                
                if ($Company) {
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

            }*/
    }

}
