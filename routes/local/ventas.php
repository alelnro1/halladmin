<?php
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