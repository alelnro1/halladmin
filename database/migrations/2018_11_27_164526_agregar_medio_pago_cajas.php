<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarMedioPagoCajas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('cajas', function (Blueprint $table) {
            $table->integer('medio_pago_id')->unsigned();
        });

        Schema::table('cajas', function (Blueprint $table) {
            $table->foreign('medio_pago_id')->references('id')->on('medios_pago');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cajas', function (Blueprint $table) {
            $table->dropForeign(['medio_pago_id']);
        });

        Schema::table('cajas', function (Blueprint $table) {
            $table->dropColumn(['medio_pago_id']);
        });
    }
}
