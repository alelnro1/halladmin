<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearForaneasStockLocal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('local_stock', function (Blueprint $table) {
            $table->foreign('local_id')->references('id')->on('locales');
            $table->foreign('articulo_id')->references('id')->on('articulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('local_stock', function (Blueprint $table) {
            $table->dropForeign([
                'local_id',
                'articulo_id'
            ]);
        });
    }
}
