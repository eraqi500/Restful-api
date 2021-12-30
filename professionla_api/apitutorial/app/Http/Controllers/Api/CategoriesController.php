<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    use GeneralTrait;

    public function index(){
    $categories = Category::Selection()->get();

//    return response()->json($categories);
        return $this-> returnData('categories', $categories);
    }

    public function getCategoryById(Request $request){

        $category = Category::Selection()->find($request->id);
        if(!$category)
          return  $this-> returnError('001', 'this department not exist');

        return $this -> returnData('category',$category );
    }


    public function changeStatus(Request $request){
        Category::where('id', $request->id)->
            update(['active' => $request->active]);

        return $this-> returnSuccessMessage('the status has been changed');
    }

}
