<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$perfil = function () {
    Route::get('/cambiar-clave-personal', 'PerfilController@cambiarClaveForm');
    Route::post('/cambiar-clave-personal', 'PerfilController@actualizarClave');

    Route::get('/perfil', 'PerfilController@verPerfil');
    Route::get('/perfil/edit', 'PerfilController@editarPerfil');
    Route::patch('/perfil/update', 'PerfilController@actualizarPerfil');
};

Route::get('/', 'HomeController@index');

// Rutas del front-end
    Route::get('/tpl', 'FrontendController@index');

Route::auth();
Route::group(['middleware' => ['auth']], function () use ($perfil) {
    Route::get('/home', 'HomeController@index');

    /***** LOCALES *****/
    Route::group(['prefix' => 'locales'], function () {
        Route::get('/', 'LocalesController@index')->name('locales');

        Route::get('nuevo', 'LocalesController@create')->name('locales.create');
        Route::post('nuevo', 'LocalesController@store')->name('locales.store');

        Route::group(['middleware' => ['ownership-locales']], function () {
            Route::get('{local}', 'LocalesController@show')->name('locales.view');

            Route::get('{local}/edit', 'LocalesController@edit')->name('locales.edit');
            Route::post('{local}/edit', 'LocalesController@update')->name('locales.update');
        });
    });

    /***** PROVEEDORES *****/
    Route::group(['prefix' => 'proveedores'], function () {
        Route::get('/', 'ProveedoresController@index')->name('proveedores');

        Route::get('nuevo', 'ProveedoresController@create')->name('proveedores.create');
        Route::post('nuevo', 'ProveedoresController@store')->name('proveedores.store');

        Route::group(['middleware' => ['ownership-proveedores']], function () {
            Route::get('/{proveedor}', 'ProveedoresController@show')->name('proveedores.view');

            Route::get('{proveedor}/edit', 'ProveedoresController@edit')->name('proveedores.edit');
            Route::post('{proveedor}/edit', 'ProveedoresController@update')->name('proveedores.update');
        });
    });

    /***** USUARIOS *****/
    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/', 'UsuariosController@index')->name('usuarios');

        Route::get('nuevo', 'UsuariosController@create')->name('usuarios.create');
        Route::post('nuevo', 'UsuariosController@store')->name('usuarios.store');

        Route::group(['middleware' => ['ownership-usuarios']], function () {
            Route::get('{usuario}', 'UsuariosController@show')->name('usuarios.view');

            Route::get('{usuario}/edit', 'UsuariosController@edit')->name('usuarios.edit');
            Route::post('{usuario}/edit', 'UsuariosController@update')->name('usuarios.update');
        });
    });

    //Route::resource('usuarios', 'UsuariosController');



    Route::resource('promociones', 'PromocionesController');

    Route::resource('ofertas', 'OfertasController');
    Route::resource('articulos', 'ArticulosController');
    Route::resource('talles', 'TallesController');
    Route::resource('categorias', 'CategoriasController');
    Route::resource('clientes', 'ClientesController');
    Route::resource('alarmas', 'AlarmasController');

    Route::get('caja', 'CajaController@listar');
    Route::get('caja/abrir', 'CajaController@abrirCaja');
    Route::get('caja/cerrar', 'CajaController@cerrarCaja');
    Route::post('caja/abrir', 'CajaController@procesarApertura');
    Route::post('caja/cerrar', 'CajaController@procesarCierre');

    // Listado de ventas canceladas
    Route::get('ventas-canceladas', 'VentasController@ventasCanceladas');

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

    Route::group(['prefix' => 'cambios'], function () {
        Route::get('/', 'CambiosController@index');

        // Paso 1: Se elije el articulo a cambiar
        Route::get('/nuevo-cambio', 'CambiosController@ingresarArticuloACambiarForm');

        // Paso 2: Se crea la sesion con el articulo seleccionó para cambiar
        Route::post('seleccionar-articulo', 'CambiosController@seleccionarArticuloACambiar');

        // Paso 3: Se muestran los articulos para vender
        Route::get('articulos', 'CambiosController@seleccionarArticulosParaCambiarForm');

        // Cancelar el articulo
        Route::get('nuevo', 'CambiosController@cambiarArticuloADevolver');
    });

    Route::group(['prefix' => 'ventas', 'middleware' => 'section:ventas'], function () {
        Route::get('/', 'VentasController@index');

        Route::get('/orden/{orden}', 'VentasController@show')->name('ventas.ver');

        // Paso 1: Seleccionar articulo para vender
        Route::get('nueva-venta', 'VentasController@nuevaVentaForm');

        // Guardar articulos temporales de la venta
        Route::post('guardar-articulos-venta', 'VentasController@guardarFilasTemporalmente');

        // Paso 2: Datos del cliente
        Route::get('datos-de-cliente', 'VentasController@pedirDatosDeCliente');

        // Paso 2.5: Si el cliente ya existe, se selecciona y hay que crear la variable de de sesion
        Route::post('seleccionar-cliente', 'ClientesController@seleccionarClienteParaVender');

        // Paso 3: Previsualizar la venta
        Route::get('previsualizar', 'VentasController@previsualizarVenta');

        // Paso 3.5: Seteo el medio de pago y el numero de factura
        Route::post('medio-y-factura', 'VentasController@setMedioDePagoYFactura');

        // Paso 4: concretar la venta
        Route::get('concretar-venta', 'VentasController@concretarVenta');

        // Cancelación de una venta
        Route::post('cancelar', 'VentasController@cancelarVenta');

        // Se manda a imprimir los datos de la venta
        Route::get('imprimir', 'VentasController@imprimir');

        // Se actualizan los precios de los archivos temporales por descuentos
        Route::post('aplicar-descuento', 'VentasController@aplicarDescuento');
    });

    Route::get('cambiar-de-local/{local}', 'Controller@setLocalDesdeVista');

    // Rutas solo del super admin
    Route::group(['middleware' => 'acceso:super admin'], function () {
        Route::resource('administradores', 'AdministradoresController');
    });

    $perfil();
});