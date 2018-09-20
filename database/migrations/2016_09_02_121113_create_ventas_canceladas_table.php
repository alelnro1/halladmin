<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateVentasCanceladasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_canceladas', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('local_id')->unsigned()->index();
            $table->integer('cliente_id')->nullable()->unsigned()->index();
            $table->string('motivo');
            $table->timestamps();
        });

        Schema::table('ventas_canceladas', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('local_id')->references('id')->on('locales');
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas_canceladas', function(Blueprint $table) {
            $table->dropForeign('ventas_canceladas_user_id_foreign');
            $table->dropForeign('ventas_canceladas_local_id_foreign');
            $table->dropForeign('ventas_canceladas_cliente_id_foreign');
        });

        Schema::drop('ventas_canceladas');
    }
}
