<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearRelacionesPriceListEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_list_entry', function (Blueprint $table) {
            $table->foreign('price_list_id')->references('id')->on('price_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_list_entry', function (Blueprint $table) {
            $table->dropForeign([
                'price_list_id'
            ]);
        });
    }
}
