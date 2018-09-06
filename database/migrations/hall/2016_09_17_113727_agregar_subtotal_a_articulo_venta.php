<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarSubtotalAArticuloVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulo_venta', function(Blueprint $table) {
            $table->double('subtotal')->comment('Se guarda por si hay un descuento');
            $table->double('descuento')->comment('Porcentaje de descuento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articulo_venta', function(Blueprint $table) {
            $table->dropColumn('subtotal');
            $table->dropColumn('descuento');
        });
    }
}
