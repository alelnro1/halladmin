<?php
Route::group(['prefix' => 'cambios', 'middleware' => 'tiene-algun-local'], function () {
    Route::get('/', 'CambiosController@index')->name('cambios');

    // Paso 1: Se elije el articulo a cambiar
    Route::get('/nuevo-cambio', 'CambiosController@ingresarArticuloACambiarForm');

    // Paso 2: Se crea la sesion con el articulo seleccionó para cambiar
    Route::post('seleccionar-articulo', 'CambiosController@seleccionarArticuloACambiar');

    // Paso 3: Se muestran los articulos para vender
    Route::get('articulos', 'CambiosController@seleccionarArticulosParaCambiarForm');

    // Cancelar el articulo
    Route::get('nuevo', 'CambiosController@cambiarArticuloADevolver');
});