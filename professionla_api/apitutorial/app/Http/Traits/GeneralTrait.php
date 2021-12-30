<?php
namespace App\Http\Traits;

trait GeneralTrait
{

    public function getCurrentLang(){
        return app()->getLocale();
    }

    public function returnData($key,$value , $msg=" ")
    {
       return response()->json([
          'status' => true ,
           'errNum' => 'S000',
           'msg' => $msg,
           $key => $value
       ]);
    }

    public function returnError($errNum , $msg){
        return response()->json([
           'status' => false ,
           'errNum' => $errNum ,
            'msg' => $msg
        ]);
    }

    public function returnSuccessMessage($msg="" , $errNum= "5000"){
        return [
            'status' => true ,
            'errNum'=> $errNum ,
            'msg' => $msg
        ];
    }

    public function returnCodeAccordingToInput($validator){
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input){
        if($input== "name")
            return 'E0011';
        else if($input == "password")
            return 'E002';

        else
            return "";
    }



    public function returnValidationError($code="E001", $validator){

        return $this->returnError($code, $validator->errors()->first());
    }

}
