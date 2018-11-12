<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNegocioProveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('negocio_proveedor', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('negocio_id')->unsigned();
            $table->integer('proveedor_id')->unsigned();
        });

        Schema::table('negocio_proveedor', function (Blueprint $table) {
            $table->foreign('negocio_id')->references('id')->on('negocios');
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
        Schema::dropIfExists('negocio_proveedor');
    }
}
