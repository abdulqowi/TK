<?php

use Illuminate\Support\Facades\Route;

require_once('includes/auth.php');
Route::get('/education', 'EducationsController@index');

Route::group(
    ['middleware' => 'auth:api'],
    function () {
        Route::post('/user/edit', 'UserController@update');
        Route::post('/transaction/payment', 'TransactionsController@payment');
        require_once('includes/receipt.php');
        require_once('includes/receiptDetail.php');
        Route::middleware('admin')->group(function () {
            require_once('includes/user.php');
            require_once('includes/course.php');
            require_once('includes/product.php');
            require_once('includes/education.php');
            require_once('includes/master.php');
            require_once('includes/schedule.php');
            require_once('includes/masterPrice.php');
            require_once ('includes/Notification.php');
            require_once('includes/transaction.php');
        });
    }
);
