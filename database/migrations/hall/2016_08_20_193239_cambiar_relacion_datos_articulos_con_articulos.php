<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambiarRelacionDatosArticulosConArticulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datos_articulos', function(Blueprint $table) {
            $table->dropForeign('datos_articulos_articulo_id_foreign');
            $table->dropColumn('articulo_id');
        });

        Schema::table('articulos', function(Blueprint $table) {
            $table->integer('datos_articulo_id')->unsigned()->index();
            $table->foreign('datos_articulo_id')->references('id')->on('datos_articulos');
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
            $table->dropForeign('articulos_datos_articulo_id_foreign')->references('id')->on('datos_articulos');
            $table->dropColumn('datos_articulo_id');
        });

        Schema::table('datos_articulos', function(Blueprint $table) {
            $table->integer('articulo_id')->unsigned()->index();
            $table->foreign('articulo_id')->references('id')->on('articulos');
        });
    }
}
