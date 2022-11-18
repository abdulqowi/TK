<?php

use Illuminate\Support\Facades\Route;

Route::get('/transaction','TransactionsController@index');
Route::post('/transaction/create', 'TransactionsController@store');
Route::post('/transaction/{id}', 'TransactionsController@update');
Route::delete('/{id}','TransactionsController@destroy');

