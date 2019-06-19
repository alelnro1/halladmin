<?php
Route::group(['prefix' => 'empleados'], function () {
    Route::get('/', 'EmpleadosController@index')->name('empleados');

    Route::get('nuevo', 'EmpleadosController@create')->name('empleados.create');
    Route::post('nuevo', 'EmpleadosController@store')->name('empleados.store');

    Route::group(['middleware' => ['ownership-empleados']], function () {
        Route::get('/{usuario}', 'EmpleadosController@show')->name('empleados.view');

        Route::get('{usuario}/edit', 'EmpleadosController@edit')->name('empleados.edit');
        Route::post('{usuario}/edit', 'EmpleadosController@update')->name('empleados.update');

        Route::get('{usuario}/eliminar', 'EmpleadosController@destroy')->name('empleados.delete');
    });
});