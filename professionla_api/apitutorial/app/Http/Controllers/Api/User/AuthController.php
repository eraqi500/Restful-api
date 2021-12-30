<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use GeneralTrait;

    public function login(Request $request){
        //validation

     try{
         $rules = [
             "password"=> "required",
             "email" => "required",
         ];

         $validator = Validator::make($request->all(), $rules);

         if ($validator->fails()){
             $code = $this->returnCodeAccordingToInput($validator);
             return $this->returnValidationError($code , $validator);
         }

         //login
         $credential = $request->only(['email','password']);
        $token =  Auth::guard('user-api')->attempt($credential);

        if(!$token)
            return $this->returnError('E001', 'the data of registration not right');

        $user = Auth::guard('user-api')->user();

        //return token
         $user-> api_token = $token;
        return $this-> returnData('user' , $user);

     }catch (\Exception $ex){
        return $this->returnError($ex->getCode(), $ex->getMessage());
     }


    }

    public function logout(Request $request){
     $token = $request->header('auth-token');
     if($token){
         try{
             JWTAuth::setToken($token)->invalidate(); // logout
         }
         catch (TokenInvalidException $e){
            return $this->returnError('', 'maybe token invalid');
         }
         return $this->returnSuccessMessage('Logged out successfully');
     }else{
        $this->returnError('', 'something went wrongs');
     }
    }
}

