<?php
Route::group(['prefix' => 'afip', 'middleware' => ['tiene-algun-local']], function () {
    // Se muestra la informacion para generar una factura
    Route::get('facturar/datos', 'AfipController@datosParaFacturar')->name('afip.datos-para-facturar');

    Route::get('comprobantes-disponibles', 'AfipController@getComprobantesDisponibles')->name('afip.comprobantes-disponibles');

    Route::post('contribuyente', 'AfipController@getInfoContribuyente')->name('afip.get-info-contribuyente');

    Route::post('facturar', 'AfipController@generarFactura')->name('afip.generar-factura');

    Route::get('voucher/{voucher}', 'AfipController@getVoucher')->name('afip.ver-voucher');
});