<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class EliminarCategoriaDeTalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talles', function($table) {
            $table->dropForeign('talles_categoria_id_foreign');
        });

        Schema::table('talles', function($table) {
            $table->dropColumn('categoria_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talles', function($table) {
            $table->integer('categoria_id')->unsigned()->index()->nullable();
        });

        Schema::table('talles', function($table) {
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }
}
