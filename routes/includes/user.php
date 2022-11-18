<?php
/* -------------------------------------------------------------------------- */
/*                                 route user                                 */
/* -------------------------------------------------------------------------- */
use Illuminate\Support\Facades\Route;

Route::get('/user', 'UserController@index');
Route::get('/user/{id}', 'UserController@show');
Route::delete('/user/{id}', 'UserController@destroy');
