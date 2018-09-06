<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarLocalADatosArticulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datos_articulos', function(Blueprint $table) {
            $table->integer('local_id')->unsigned()->index()->nullable();
        });

        Schema::table('datos_articulos', function($table) {
            $table->foreign('local_id')->references('id')->on('locales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('datos_articulos', function($table) {
            $table->dropForeign('datos_articulos_local_id_foreign');
        });

        Schema::table('datos_articulos', function($table) {
            $table->dropColumn('local_id');
        });
    }
}
