<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function(Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->double('precio');
            $table->text('descripcion');
            $table->integer('cantidad');
            $table->string('color');
            $table->integer('local_id')->unsigned();
            $table->integer('talle_id')->unsigned();
            $table->integer('proveedor_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('articulos', function($table) {
            $table->foreign('local_id')->references('id')->on('locales');
            $table->foreign('talle_id')->references('id')->on('talles');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articulos', function(Blueprint $table) {
            $table->dropForeign('articulos_local_id_foreign');
            $table->dropForeign('articulos_talle_id_foreign');
            $table->dropForeign('articulos_proveedor_id_foreign');
        });

        Schema::drop('articulos');
    }
}
