<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('proveedores', function(Blueprint $table) {
        $table->increments('id');
        $table->string('nombre');
        $table->text('descripcion');
        $table->string('archivo');
        $table->string('domicilio');
        $table->string('email');
        $table->string('telefono');
        $table->integer('usuario_id')->unsigned();
        $table->timestamps();
        $table->softDeletes();

    });

    Schema::table('proveedores', function($table) {
        $table->foreign('usuario_id')->references('id')->on('users');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proveedores', function(Blueprint $table) {
            $table->dropForeign('proveedores_usuario_id_foreign');
        });

        Schema::drop('proveedores');
    }
}
