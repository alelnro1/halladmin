<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearIndicesPriceListEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_list_entry', function (Blueprint $table) {
            $table->index(['articulo_id', 'price_list_id']);
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
            $table->dropIndex(['articulo_id', 'price_list_id']);
        });
    }
}
