<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware'=>['api','changeLanguage'],'namespace'=>'Api'],function (){

    Route::post('get-main-categories','CategoriesController@index');
    Route::post('get-category-byId','CategoriesController@getCategoryById');
    Route::post('change-category-status','CategoriesController@changeStatus');

    Route::group(['prefix' => 'admin', 'namespace'=>'Admin'], function (){
        Route::post('login', 'AuthController@login');

        Route::post('logout','AuthController@logout')
            ->middleware('auth.guard:admin-api');

    });

    Route::group(['prefix' => 'user','namespace' => 'User'], function (){

        Route::post('login', 'AuthController@UserLogin');
    });



    Route::group(['prefix' => 'user', 'middleware'=>'auth.guard:user-api'], function(){
        Route::post('profile', function (){
           return 'Only Authenticated User can Reach me';
        });
    });
});


Route::group(['middleware'=>['api', 'checkPassword','checkLanguage','checkAdminToken:admin-api'], 'namespace' => 'Api'],function(){

});
