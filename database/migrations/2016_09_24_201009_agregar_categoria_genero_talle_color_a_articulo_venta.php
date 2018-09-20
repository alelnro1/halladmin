<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AgregarCategoriaGeneroTalleColorAArticuloVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulo_venta', function($table) {
            $table->integer('categoria_id')->index()->unsigned()->nullable();
            $table->integer('genero_id')->index()->unsigned()->nullable();
            $table->integer('talle_id')->index()->unsigned()->nullable();
        });

        Schema::table('articulo_venta', function($table) {
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('genero_id')->references('id')->on('generos');
            $table->foreign('talle_id')->references('id')->on('talles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articulo_venta', function($table) {
            $table->dropForeign('articulo_venta_categoria_id_foreign');
            $table->dropForeign('articulo_venta_genero_id_foreign');
            $table->dropForeign('articulo_venta_talle_id_foreign');
        });

        Schema::table('articulo_venta', function($table) {
            $table->dropColumn('categoria_id');
            $table->dropColumn('genero_id');
            $table->dropColumn('talle_id');
        });
    }
}
