<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarForaneasPleHistoricos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ple_historicos', function (Blueprint $table) {
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
        Schema::table('ple_historicos', function (Blueprint $table) {
            $table->dropForeign(['articulo_id']);
        });
    }
}
