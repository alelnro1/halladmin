<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCambiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cambios', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('venta_id')->unsigned();
            $table->integer('articulo_id')->unsigned()->comment('El nuevo artículo que decidió comprar');
            $table->text('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('cambios', function($table) {
            $table->foreign('venta_id')->references('id')->on('ventas');
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
        Schema::table('cambios', function(Blueprint $table) {
            $table->dropForeign('cambios_venta_id_foreign');
            $table->dropForeign('cambios_articulo_id_foreign');
        });

        Schema::drop('cambios');
    }
}
