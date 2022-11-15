<?php

use Illuminate\Support\Facades\Route;

Route::get('/schedule','SchedulesController@index');
Route::post('/schedule/{id}', 'SchedulesController@update');
Route::post('/schedule/create', 'SchedulesController@store');
Route::delete('/{id}','SchedulesController@destroy');


