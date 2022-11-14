<?php
/* -------------------------------------------------------------------------- */
/*                                 route user                                 */
/* -------------------------------------------------------------------------- */
use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'education'],
    function () {
        Route::get('/', 'EducationsController@index');
        Route::post('/create','EducationsController@store');
        Route::post('/{id}', 'EducationsController@update');
        Route::delete('/{id}','EducationsController@destroy');

    });
