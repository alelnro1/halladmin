<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriaLocalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoria_local', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('local_id')->unsigned();
            $table->integer('categoria_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('categoria_local', function(Blueprint $table) {
            $table->foreign('local_id')->references('id')->on('locales');
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
        Schema::table('categoria_local', function(Blueprint $table) {
            $table->dropForeign('categoria_local_local_id_foreign');
            $table->dropForeign('categoria_local_categoria_id_foreign');
        });

        Schema::drop('categoria_local');
    }
}
