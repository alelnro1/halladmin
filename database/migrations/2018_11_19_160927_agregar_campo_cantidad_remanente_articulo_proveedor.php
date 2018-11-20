<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarCampoCantidadRemanenteArticuloProveedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulo_proveedor', function (Blueprint $table) {
            $table->integer('cantidad_remanente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articulo_proveedor', function (Blueprint $table) {
            $table->dropColumn(['cantidad_remanente']);
        });
    }
}
