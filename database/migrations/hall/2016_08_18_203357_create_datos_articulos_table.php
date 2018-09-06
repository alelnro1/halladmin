<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateDatosArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_articulos', function(Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->double('precio');
            $table->text('descripcion');
            $table->integer('categoria_id')->unsigned();
            $table->integer('articulo_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('datos_articulos', function($table) {
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('articulo_id')->references('id')->on('articulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('datos_articulos', function(Blueprint $table) {
            $table->dropForeign('datos_articulos_categoria_id_foreign');
            $table->dropForeign('datos_articulos_articulo_id_foreign');
        });

        Schema::drop('datos_articulos');
    }
}
