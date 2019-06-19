<?php

Route::get('/', 'HomeController@index');

// Rutas del front-end
Route::get('/tpl', 'FrontendController@index');

Route::auth();
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    /***** LOCALES *****/
    Route::group(['prefix' => 'locales'], function () {
        Route::get('/', 'LocalesController@index')->name('locales');

        Route::get('nuevo', 'LocalesController@create')->name('locales.create');
        Route::post('nuevo', 'LocalesController@store')->name('locales.store');

        Route::group(['middleware' => ['ownership-locales']], function () {
            Route::get('{local}', 'LocalesController@show')->name('locales.view');

            Route::get('{local}/edit', 'LocalesController@edit')->name('locales.edit');
            Route::post('{local}/edit', 'LocalesController@update')->name('locales.update');

            Route::get('{local}/eliminar', 'LocalesController@destroy')->name('locales.delete');

        });
    });

    /***** PROVEEDORES *****/
    include_once 'local/proveedores.php';

    /***** EMPLEADOS *****/
    include_once 'local/empleados.php';

    /***** ARTICULOS *****/
    include_once 'local/articulos.php';

    Route::group(['prefix' => 'facturacion', 'middleware' => 'tiene-algun-local'], function () {
        Route::get('/', 'FacturacionController@index')->name('facturacion');
    });

    //Route::resource('promociones', 'PromocionesController');
    //Route::resource('ofertas', 'OfertasController');
    Route::resource('talles', 'TallesController');
    Route::resource('categorias', 'CategoriasController');

    Route::group(['middleware' => 'tiene-algun-local'], function () {
        Route::resource('clientes', 'ClientesController');
    });

    Route::group(['prefix' => 'clientes'], function () {

    });

    Route::resource('alarmas', 'AlarmasController');

    Route::resource('lista-precios', 'PriceListController');

    include_once 'local/caja.php';

    // Listado de ventas canceladas
    Route::get('ventas-canceladas', 'VentasController@ventasCanceladas')->name('ventas.canceladas');

    include_once 'local/ingreso-mercaderia.php';

    include_once 'local/cambios.php';

    include_once 'local/ventas.php';

    include_once 'local/afip.php';

    Route::get('cambiar-de-local/{local}', 'BaseController@setLocalDesdeVista');

    // Rutas solo del super admin
    /***** ADMINISTRADORES *****/
    include_once 'super-admin/administradores.php';

    include_once 'usuario/perfil.php';

    Route::group(['prefix' => 'mi-negocio'], function () {
        Route::get('/', 'NegocioController@index')->name('negocio');

        Route::post('actualizar', 'NegocioController@actualizarConfig')->name('negocio.actualizar');
    });
});