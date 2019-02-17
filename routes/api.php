<?php

use Illuminate\Http\Request;



Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::middleware('auth:api')->group(function(){
  Route::post('details', 'API\AuthController@getDetails');
});