<?php

use Illuminate\Support\Facades\Route;

Route::get('/receipt','ReceiptController@index');
Route::get('/receipt/{id}', 'ReceiptController@show');
Route::post('/receipt/create', 'ReceiptController@store');
//  Created to make an order by receipt id, contain orders by receipt details id

