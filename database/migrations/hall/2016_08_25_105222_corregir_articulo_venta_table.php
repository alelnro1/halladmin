<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CorregirArticuloVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulo_venta', function(Blueprint $table) {
            $table->dropForeign('articulo_venta_local_id_foreign');
            $table->dropColumn('local_id');
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
            $table->integer('local_id')->unsigned()->index();
        });

        Schema::table('articulo_venta', function($table) {
            $table->foreign('local_id')->references('id')->on('locales');
        });
    }
}
