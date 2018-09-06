<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CorregirVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function(Blueprint $table) {
            $table->integer('cliente_id')->unsigned()->index()->nullable()->comment('Puede ser nulo si no elije ningun cliente para vender');
            $table->double('monto_total');
        });

        Schema::table('ventas', function($table) {
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas', function(Blueprint $table) {
            $table->dropForeign('ventas_cliente_id_foreign');

        });

        Schema::table('ventas', function(Blueprint $table) {
            $table->dropColumn('cliente_id');
            $table->dropColumn('monto_total');
        });
    }
}
