<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('categoria_id')->unsigned();
            $table->timestamps();

        });

        Schema::table('talles', function($table) {
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
        Schema::table('talles', function(Blueprint $table) {
            $table->dropForeign('talles_categoria_id_foreign');
        });

        Schema::drop('talles');
    }
}
