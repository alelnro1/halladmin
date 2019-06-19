<?php
Route::group(['prefix' => 'perfil'], function () {
    Route::get('/', 'PerfilController@verPerfil')->name('perfil');

    Route::get('/cambiar-clave-personal', 'PerfilController@cambiarClaveForm');
    Route::post('/cambiar-clave-personal', 'PerfilController@actualizarClave');

    Route::get('modificar', 'PerfilController@editarPerfil')->name('perfil.modificar');
    Route::post('modificar', 'PerfilController@actualizarPerfil')->name('perfil.procesar-modificacion');
});