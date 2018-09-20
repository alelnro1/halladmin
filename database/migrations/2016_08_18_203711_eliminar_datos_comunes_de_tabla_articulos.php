<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EliminarDatosComunesDeTablaArticulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulos', function(Blueprint $table) {
            $table->dropForeign('articulos_proveedor_id_foreign');
            $table->dropForeign('articulos_categoria_id_foreign');
        });

        Schema::table('articulos', function(Blueprint $table) {
            $table->dropColumn('codigo');
            $table->dropColumn('precio');
            $table->dropColumn('descripcion');
            $table->dropColumn('proveedor_id');
            $table->dropColumn('categoria_id');
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
            $table->string('codigo');
            $table->double('precio');
            $table->text('descripcion');
            $table->integer('proveedor_id')->unsigned();
            $table->integer('categoria_id')->unsigned();
        });

        Schema::table('articulos', function($table) {
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }
}
