<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarNegocioIdAProveedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            //$table->integer('negocio_id')->unsigned()->nullable();
        });

        Schema::table('proveedores', function (Blueprint $table) {
            //$table->foreign('negocio_id')->references('id')->on('negocios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn('negocio_id');
        });
    }
}
