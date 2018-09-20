<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalProveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_proveedor', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('local_id')->unsigned();
            $table->integer('proveedor_id')->unsigned();

            $table->timestamps();
        });

        Schema::table('local_proveedor', function (Blueprint $table) {
            $table->foreign('local_id')->references('id')->on('locales');
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
        Schema::table('local_proveedor', function (Blueprint $table) {
            $table->dropForeign('local_proveedor_local_id_foreign');
            $table->dropForeign('local_proveedor_proveedor_id_foreign');
        });

        Schema::dropIfExists('local_proveedor');
    }
}
