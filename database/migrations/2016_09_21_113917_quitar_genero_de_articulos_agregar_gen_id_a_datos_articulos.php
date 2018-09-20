<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class QuitarGeneroDeArticulosAgregarGenIdADatosArticulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulos', function($table) {
            $table->dropColumn('genero');
        });

        Schema::table('datos_articulos', function($table) {
            $table->integer('genero_id')->index()->unsigned()->nullable();
        });

        Schema::table('datos_articulos', function($table) {
            $table->foreign('genero_id')->references('id')->on('generos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articulos', function($table) {
            $table->string('genero');
        });

        Schema::table('datos_articulos', function($table) {
            $table->dropForeign('datos_articulos_genero_id_foreign');
        });


        Schema::table('datos_articulos', function($table) {
            $table->dropColumn('genero_id');
        });
    }
}
