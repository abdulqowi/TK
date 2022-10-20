<?php
/* -------------------------------------------------------------------------- */
/*                                 route user                                 */
/* -------------------------------------------------------------------------- */
use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'course'],
    function () {
        Route::get('/', 'CourseController@index');
        Route::post('/create','CourseController@store');
        Route::post('/{id}', 'CourseController@update');
        Route::delete('/{id}','CourseController@destroy');

    });
