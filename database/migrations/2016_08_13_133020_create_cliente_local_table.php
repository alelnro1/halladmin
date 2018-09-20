<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateClienteLocalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_local', function(Blueprint $table) {
            $table->integer('cliente_id')->unsigned()->index();
            $table->integer('local_id')->unsigned()->index();
        });

        Schema::table('cliente_local', function($table) {
            $table->foreign('cliente_id')->references('id')->on('clientes');
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
        Schema::table('cliente_local', function(Blueprint $table) {
            $table->dropForeign('cliente_local_cliente_id_foreign');
            $table->dropForeign('cliente_local_local_id_foreign');
        });

        Schema::drop('cliente_local');
    }
}
