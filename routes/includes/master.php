<?php
/* -------------------------------------------------------------------------- */
/*                                 route user                                 */
/* -------------------------------------------------------------------------- */
use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'master'],
    function () {
        Route::get('/', 'MasterController@index');
        Route::post('/create','MasterController@store');
        Route::post('/{id}', 'MasterController@update');
        Route::delete('/{id}','MasterController@destroy');

    });
