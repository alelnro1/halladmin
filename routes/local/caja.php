<?php
Route::group(['prefix' => 'caja'], function () {
    Route::get('/', 'CajaController@index')->name('caja');
    Route::get('abrir', 'CajaController@abrirCaja')->name('caja.abrir');
    Route::post('abrir', 'CajaController@procesarApertura')->name('caja.procesar-apertura');

    Route::get('cerrar', 'CajaController@cerrarCaja')->name('caja.cerrar');
    Route::post('cerrar', 'CajaController@procesarCierre')->name('caja.procesar-cierre');

    Route::get('contabilidad', 'ContabilidadController@index');
});