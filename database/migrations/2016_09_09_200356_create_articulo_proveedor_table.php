<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateArticuloProveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo_proveedor', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('proveedor_id')->nullable()->index()->unsigned();
            $table->integer('articulo_id')->nullable()->index()->unsigned();
            $table->integer('local_id')->nullable()->index()->unsigned();
            $table->timestamps();
            $table->double('costo');
            $table->integer('cantidad');
        });

        Schema::table('articulo_proveedor', function($table) {
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->foreign('local_id')->references('id')->on('locales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articulo_proveedor', function($table) {
            $table->dropForeign('articulo_proveedor_proveedor_id_foreign');
            $table->dropForeign('articulo_proveedor_articulo_id_foreign');
            $table->dropForeign('articulo_proveedor_local_id_foreign');
        });

        Schema::drop('articulo_proveedor');
    }
}
