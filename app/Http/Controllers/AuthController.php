<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\User;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string|min:6',]);
    
        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())){
            return response()->json(['error'=>'Unauthorized'], 401);
        }
    
        return $this->createNewToken($token); 
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|between:2,100',
            'email'=>'required|string|email|max:100|unique:users',
            'password'=>'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User seuccessfully registered',
            'user' => $user
        ], 201);
    }

    public function logout(){
        auth()->logout();

        return response()->json(['message' => 'User succesfully signed out']);
    }

    public function refresh(){
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile(){
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()-> json([
            'access_token'=> $token,
            'token_type'=>'bearer',
            'expires_in'=> auth()->factory()->getTTL()*60,
            'user' => auth()->user()
        ]);
    }


    public function me()
    {
        return['nis' => '3103118084',
        'name' => 'Lintang Pinastika Yudha',
        'gender' => 'Female',
        'phone' => '+62852 9074 4132',
        'class' => 'XII RPL 3'];
    }
}
