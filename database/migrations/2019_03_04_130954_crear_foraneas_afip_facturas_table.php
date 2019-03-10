<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearForaneasAfipFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('afip_facturas', function (Blueprint $table) {
            $table->foreign('venta_id')->references('id')->on('ventas');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('negocio_id')->references('id')->on('negocios');
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
        Schema::table('afip_facturas', function (Blueprint $table) {
            $table->dropForeign([
                'venta_id',
                'cliente_id',
                'negocio_id',
                'local_id'
            ]);
        });
    }
}
