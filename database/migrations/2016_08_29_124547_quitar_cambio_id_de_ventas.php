<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuitarCambioIdDeVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function(Blueprint $table) {
            $table->dropForeign('ventas_cambio_id_foreign');

            $table->dropColumn('cambio_id');
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
            $table->integer('cambio_id')->unsigned()->index()->nullable();

            $table->foreign('cambio_id')->references('id')->on('cambios');
        });
    }
}
