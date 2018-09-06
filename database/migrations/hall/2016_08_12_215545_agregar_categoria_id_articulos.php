<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AgregarCategoriaIdArticulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articulos', function(Blueprint $table) {
            $table->integer('categoria_id')->unsigned()->index();
        });

        Schema::table('articulos', function($table) {
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articulos', function(Blueprint $table) {
            $table->dropForeign('articulos_categoria_id_foreign');
            $table->dropColumn('categoria_id');
        });
    }
}
