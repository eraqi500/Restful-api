<?php

namespace App\Http\Controllers\Api\Admin;

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
             "email" => "required|exists:admins,email",
         ];

         $validator = Validator::make($request->all(), $rules);

         if ($validator->fails()){
             $code = $this->returnCodeAccordingToInput($validator);
             return $this->returnValidationError($code , $validator);
         }

         //login
         $credential = $request->only(['email','password']);
        $token =  Auth::guard('admin-api')->attempt($credential);

        if(!$token)
            return $this->returnError('E001', 'the data of registration not right');

        $admin = Auth::guard('admin-api')->user();

        //return token
         $admin-> api_token = $token;
        return $this-> returnData('admin' , $admin);

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

