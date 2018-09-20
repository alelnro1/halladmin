<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('padre_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('categorias', function($table) {
            $table->foreign('padre_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorias', function(Blueprint $table) {
            $table->dropForeign('categorias_padre_id_foreign');
        });

        Schema::drop('categorias');
    }
}
