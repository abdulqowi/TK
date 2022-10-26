<?php

use Illuminate\Support\Facades\Route;

Route::get('/receipt','ReceiptController@index');
Route::get('/receipt/{id}', 'ReceiptController@show');
Route::post('/receipt/create/{id}', 'ReceiptController@store');
Route::post('/receipt/course/', 'ReceiptController@courseStore');