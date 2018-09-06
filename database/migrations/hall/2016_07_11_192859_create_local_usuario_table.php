<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateLocalUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_usuario', function(Blueprint $table) {
            $table->integer('local_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('es_admin');
            $table->timestamps();
        });

        Schema::table('local_usuario', function(Blueprint $table) {
            $table->foreign('local_id')->references('id')->on('locales');
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
        Schema::table('local_usuario', function(Blueprint $table) {
            $table->dropForeign('local_usuario_local_id_foreign');
            $table->dropForeign('local_usuario_user_id_foreign');
        });

        Schema::drop('local_usuario');
    }
}
