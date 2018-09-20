<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateArticuloVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo_venta', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('local_id')->unsigned();
            $table->integer('venta_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('articulo_venta', function(Blueprint $table) {
            $table->foreign('local_id')->references('id')->on('locales');
            $table->foreign('venta_id')->references('id')->on('ventas');
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
            $table->dropForeign('articulo_venta_local_id_foreign');
            $table->dropForeign('articulo_venta_venta_id_foreign');
        });

        Schema::drop('articulo_venta');
    }
}
