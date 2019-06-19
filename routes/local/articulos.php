<?php
Route::group(['prefix' => 'articulos', 'middleware' => 'tiene-algun-local'], function () {
    Route::get('/', 'ArticulosController@index')->name('articulos');

    Route::get('nuevo', 'ArticulosController@create')->name('articulos.create');
    Route::post('nuevo', 'ArticulosController@store')->name('articulos.store');

    Route::group(['middleware' => ['ownership-articulos']], function () {
        Route::get('{articulo}', 'ArticulosController@show')->name('articulos.view');

        Route::get('{articulo}/edit', 'ArticulosController@edit')->name('articulos.edit');
        Route::post('{articulo}/edit', 'ArticulosController@update')->name('articulos.update');
    });
});