<?php

use Illuminate\Support\Facades\Route;

require_once('includes/auth.php');

Route::group(
    ['middleware' => 'auth:api'],
    function () {
        Route::get('/product', 'ProductDetailController@index');
        Route::get('/product/{id}', 'ProductDetailController@show');
        Route::middleware('admin')->group(function () {
            require_once('includes/user.php');
            require_once('includes/course.php');
            require_once('includes/product.php');
        });
    }
);
