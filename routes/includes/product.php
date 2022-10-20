<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'product'],
function () {
    Route::get('/','ProductDetailController@index');
    Route::get('/{id}','ProductDetailController@show');
    Route::post('/create','ProductDetailController@store');
    Route::post('/edit/{id}', 'ProductDetailController@update');
    Route::delete('/{id}', 'ProductDetailController@destroy');
});