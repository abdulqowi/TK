<?php
/* -------------------------------------------------------------------------- */
/*                                 route user                                 */
/* -------------------------------------------------------------------------- */
use Illuminate\Support\Facades\Route;

Route::get('/user', 'UserController@index');
Route::get('/user/{id}', 'UserController@show');
Route::post('/user/{id}', 'UserController@update');
Route::post('/user', 'UserController@store');
Route::delete('/user/{id}', 'UserController@destroy');
