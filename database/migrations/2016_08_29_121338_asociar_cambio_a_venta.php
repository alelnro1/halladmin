<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AsociarCambioAVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function($table) {
            $table->integer('cambio_id')->nullable()->unsigned()->index();
        });

        Schema::table('ventas', function($table) {
            $table->foreign('cambio_id')->references('id')->on('cambios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas', function($table) {
            $table->dropForeign('ventas_cambio_id_foreign');
            $table->dropColumn('cambio_id');
        });
    }
}
