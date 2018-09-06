<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMenuUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_usuario', function(Blueprint $table) {
            $table->integer('menu_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('menu_usuario', function(Blueprint $table) {
            $table->foreign('menu_id')->references('id')->on('menus');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_usuario', function(Blueprint $table) {
            $table->dropForeign('menu_usuario_menu_id_foreign');
            $table->dropForeign('menu_usuario_user_id_foreign');
        });

        Schema::drop('menu_usuario');
    }
}
