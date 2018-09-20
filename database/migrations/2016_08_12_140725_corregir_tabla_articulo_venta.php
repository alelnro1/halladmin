<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CorregirTablaArticuloVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulo_venta', function(Blueprint $table) {
            $table->dropColumn('local_id');
            $table->integer('articulo_id')->unsigned()->index();
            $table->integer('cantidad');
            $table->double('precio');
        });

        Schema::table('articulo_venta', function($table) {
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
        Schema::table('articulo_venta', function(Blueprint $table) {
            $table->dropForeign('articulo_venta_articulo_id_foreign');
            $table->dropColumn('articulo_id');
            $table->dropColumn('cantidad');
        });
    }
}
