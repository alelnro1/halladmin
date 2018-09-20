<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('padre_id')->unsigned()->nullable();
        });

        Schema::table('menus', function(Blueprint $table) {
            $table->foreign('padre_id')->references('id')->on('menus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function(Blueprint $table) {
            $table->dropForeign('menus_padre_id_foreign');
        });

        Schema::drop('menus');
    }
}
