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
//route admin
Route::middleware('auth:api')->group(function(){
    Route::prefix('v1')->group(function(){
        Route::post('login', 'AuthController@login');
        Route::post('login', 'AuthController@register');
        });
    });

