<?php

use App\Http\Controllers\ReceiptDetailController;
use Illuminate\Support\Facades\Route;

 Route::apiResource('/detail', 'ReceiptDetailController')-> except('store','create') ;
 Route::post('/detail/product','ReceiptDetailController@store');
 Route::post('/detail/course','ReceiptDetailController@courseStore');