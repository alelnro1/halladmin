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

    /***** USUARIOS *****/
    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/', 'UsuariosController@index')->name('usuarios');

        Route::get('nuevo', 'UsuariosController@create')->name('usuarios.create');
        Route::post('nuevo', 'UsuariosController@store')->name('usuarios.store');

        Route::group(['middleware' => ['ownership-usuarios']], function () {
            Route::get('{usuario}', 'UsuariosController@show')->name('usuarios.view');

            Route::get('{usuario}/edit', 'UsuariosController@edit')->name('usuarios.edit');
            Route::post('{usuario}/edit', 'UsuariosController@update')->name('usuarios.update');

            Route::get('{usuario}/eliminar', 'UsuariosController@destroy')->name('usuarios.delete');
        });
    });

    /***** ARTICULOS *****/
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


    Route::resource('promociones', 'PromocionesController');
    Route::resource('ofertas', 'OfertasController');
    //Route::resource('articulos', 'ArticulosController');
    Route::resource('talles', 'TallesController');
    Route::resource('categorias', 'CategoriasController');
    Route::resource('clientes', 'ClientesController');
    Route::resource('alarmas', 'AlarmasController');

    Route::group(['prefix' => 'caja'], function () {
        Route::get('/', 'CajaController@listar')->name('caja');
        Route::get('abrir', 'CajaController@abrirCaja')->name('caja.abrir');
        Route::post('abrir', 'CajaController@procesarApertura')->name('caja.procesar-apertura');

        Route::get('cerrar', 'CajaController@cerrarCaja')->name('caja.cerrar');
        Route::post('cerrar', 'CajaController@procesarCierre')->name('caja.procesar-cierre');

        Route::get('contabilidad', 'ContabilidadController@index');
    });

    // Listado de ventas canceladas
    Route::get('ventas-canceladas', 'VentasController@ventasCanceladas')->name('ventas.canceladas');

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

    Route::group(['prefix' => 'ventas', 'middleware' => ['section:ventas', 'tiene-algun-local']], function () {
        Route::get('/', 'VentasController@index')->name('ventas');

        Route::get('/orden/{orden}', 'VentasController@show')->name('ventas.ver');

        // Paso 1: Seleccionar articulo para vender
        Route::get('nueva-venta', 'VentasController@nuevaVentaForm')->name('ventas.nueva');

        // Guardar articulos temporales de la venta
        Route::post('guardar-articulos-venta', 'VentasController@guardarFilasTemporalmente');

        // Paso 2: Datos del cliente
        Route::get('datos-de-cliente', 'VentasController@pedirDatosDeCliente')->name('ventas.datos-de-cliente');

        // Paso 2.5: Si el cliente ya existe, se selecciona y hay que crear la variable de de sesion
        Route::post('seleccionar-cliente', 'ClientesController@seleccionarClienteParaVender');

        // Paso 3: Previsualizar la venta
        Route::get('previsualizar', 'VentasController@previsualizarVenta');

        // Paso 3.5: Seteo el medio de pago y el numero de factura
        Route::post('medio-y-factura', 'VentasController@setMedioDePagoYFactura');

        // Paso 4: concretar la venta
        Route::get('concretar-venta', 'VentasController@concretarVenta');

        // Cancelación de una venta
        Route::post('cancelar', 'VentasController@cancelarVenta')->name('ventas.cancelar');

        // Se manda a imprimir los datos de la venta
        Route::get('imprimir', 'VentasController@imprimir')->name('ventas.imprimir');

        // Se actualizan los precios de los archivos temporales por descuentos
        Route::post('aplicar-descuento', 'VentasController@aplicarDescuento');
    });

    Route::group(['prefix' => 'afip', 'middleware' => ['tiene-algun-local']], function () {
        // Se muestra la informacion para generar una factura
        Route::get('facturar/datos', 'AfipController@datosParaFacturar')->name('afip.datos-para-facturar');

        Route::get('comprobantes-disponibles', 'AfipController@getComprobantesDisponibles')->name('afip.comprobantes-disponibles');

        Route::post('contribuyente', 'AfipController@getInfoContribuyente')->name('afip.get-info-contribuyente');

        Route::get('voucher/generar', 'AfipController@generarFactura')->name('afip.generar-factura');

        Route::get('voucher/{voucher}', 'AfipController@getVoucher')->name('afip.ver-voucher');
    });

    Route::get('cambiar-de-local/{local}', 'BaseController@setLocalDesdeVista');

    // Rutas solo del super admin
    /***** ADMINISTRADORES *****/
    Route::group(['prefix' => 'administradores', 'middleware' => 'acceso:super admin'], function () {
        Route::get('/', 'AdministradoresController@index')->name('administradores');

        Route::get('nuevo', 'AdministradoresController@create')->name('administradores.create');
        Route::post('nuevo', 'AdministradoresController@store')->name('administradores.store');

        Route::get('{administrador}', 'AdministradoresController@show')->name('administradores.view');

        Route::get('{administrador}/edit', 'AdministradoresController@edit')->name('administradores.edit');
        Route::post('{administrador}/edit', 'AdministradoresController@update')->name('administradores.update');

        Route::post('{administrador}/edit-pass', 'AdministradoresController@updatePassword')->name('administradores.update-password');
    });

    Route::group(['prefix' => 'perfil'], function () {
        Route::get('/', 'PerfilController@verPerfil')->name('perfil');

        Route::get('/cambiar-clave-personal', 'PerfilController@cambiarClaveForm');
        Route::post('/cambiar-clave-personal', 'PerfilController@actualizarClave');

        Route::get('modificar', 'PerfilController@editarPerfil')->name('perfil.modificar');
        Route::post('modificar', 'PerfilController@actualizarPerfil')->name('perfil.procesar-modificacion');
    });
});