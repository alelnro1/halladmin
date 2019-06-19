<?php
Route::group(['prefix' => 'proveedores', 'middleware' => 'tiene-algun-local'], function () {
    Route::get('/', 'ProveedoresController@index')->name('proveedores');

    Route::get('nuevo', 'ProveedoresController@create')->name('proveedores.create');
    Route::post('nuevo', 'ProveedoresController@store')->name('proveedores.store');
    Route::post('asignar', 'ProveedoresController@asignar')->name('proveedores.asignar-proveedor');

    Route::group(['middleware' => ['ownership-proveedores']], function () {
        Route::get('/{proveedor}', 'ProveedoresController@show')->name('proveedores.view');


        Route::get('{proveedor}/edit', 'ProveedoresController@edit')->name('proveedores.edit');
        Route::post('{proveedor}/edit', 'ProveedoresController@update')->name('proveedores.update');

        Route::get('{proveedor}/eliminar', 'ProveedoresController@destroy')->name('proveedores.delete');
    });
});