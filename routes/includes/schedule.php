<?php

use Illuminate\Support\Facades\Route;

Route::get('/schedule','SchedulesController@index');
Route::post('/schedule/create', 'SchedulesController@store');
Route::post('/schedule/{id}', 'SchedulesController@update');
Route::delete('/{id}','SchedulesController@destroy');


