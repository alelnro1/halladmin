<?php
Route::group(['prefix' => 'mercaderia', 'middleware' => 'section:mercaderia'], function () {
    Route::get('/', 'ArticulosController@index')->name('mercaderia');

    Route::get('/actual', 'ArticulosController@index');

    Route::get('ingreso', 'MercaderiaController@ingresoForm')->middleware('section:ingreso')->name('mercaderia.ingresar');

    Route::post('ingresar', 'MercaderiaController@procesarIngresoDeMercaderia')->middleware('section:ingreso')->name('mercaderia.procesar-ingreso');

    // Cuando termina de tipear un codigo, busca si existe un articulo con ese codigo y devuelve la descripcion
    Route::post('buscar-articulo-con-codigo', 'MercaderiaController@cargarArticuloConCodigo');

    // Guardo temporalmente las filas que se van ingresando
    Route::post('guardar-filas-anteriores', 'MercaderiaController@guardarFilasTemporalmente');

    // Cargo datos dinamicamente de cada articulo cuando se está ingresando
    Route::get('cargar-contenidos-de-articulo/{codigo}', 'MercaderiaController@cargarArticuloConCodigo');

    // Cargo dinamicamente los talles de la categoria seleccionada
    //Route::post('cargar-talles-de-categoria', 'TallesController@cargarTallesDeCategoria');

    // Cargo dinamicamente los talles de la categoria seleccionada
    Route::post('cargar-talles-de-genero', 'TallesController@cargarTallesDeGenero');
});