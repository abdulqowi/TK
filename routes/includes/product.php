<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'product'],
function () {
    Route::get('/','ProductDetailController@index');
    Route::get('/product/create','ProductDetailController@store');
    Route::post('/product/create','ProductDetailController@store');
    Route::post('/product/edit/{id}', 'ProductDetailController@update');
    Route::get('/product/delete/{id}', 'ProductDetailController@destroy');
});