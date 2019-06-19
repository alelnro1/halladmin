<?php
Route::group(['prefix' => 'administradores', 'middleware' => 'acceso:super admin'], function () {
    Route::get('/', 'AdministradoresController@index')->name('administradores');

    Route::get('nuevo', 'AdministradoresController@create')->name('administradores.create');
    Route::post('nuevo', 'AdministradoresController@store')->name('administradores.store');

    Route::get('{administrador}', 'AdministradoresController@show')->name('administradores.view');

    Route::get('{administrador}/edit', 'AdministradoresController@edit')->name('administradores.edit');
    Route::post('{administrador}/edit', 'AdministradoresController@update')->name('administradores.update');

    Route::post('{administrador}/edit-pass', 'AdministradoresController@updatePassword')->name('administradores.update-password');
});