<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Validator;
use Hash;
use App\Picking;


class AuthenticateController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth', ['except' => ['authenticate']]);
	}

	public function index()
	{
		return 'INDICE';
	}

    public function authenticate(Request $request)
    {
       
        // grab credentials from the request
        $credentials = $request->only('pers_id', 'password');
       
        //dd($credentials);
        //dd($credentials['password']);
        $validator = Validator::make($credentials, [
            'pers_id' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
           
        }

        $token = null;

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        /*$credentials = $request->only('pers_id','password');
        $token = null;
        $user = null;
        try {
           
            if ($request->has('password')) {

                $user = Picking::where('pers_id', $request->input('pers_id'))->first();
                $d = 123456;
                dd(Hash::check($d, $user->password));

                if(Hash::check($credentials['password'], $user->password)) 
                {
                    dd('hola');
                    return response()->json(['error' => 'invalid_credentials'], 500);
                }
                else
                {
                    dd('chao');
                    if (!$token = JWTAuth::fromUser($user)) {
                        return response()->json(['error' => 'invalid_credentials'], 500);
                    }
                }    
                
            }else {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 500);
                }

                $user = JWTAuth::toUser($token);
            }
        } catch (JWTException $ex) {
            return response()->json(['error' => 'something_went_wrong'], 500);
        }*/


        // all good so return the token
        return response()->json(compact('token'));
    }
}
