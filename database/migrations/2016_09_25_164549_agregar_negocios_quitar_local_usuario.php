<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AgregarNegociosQuitarLocalUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Elimino la tabla cliente_local
        Schema::table('cliente_local', function(Blueprint $table) {
            $table->dropForeign('cliente_local_cliente_id_foreign');
            $table->dropForeign('cliente_local_local_id_foreign');
        });

        Schema::drop('cliente_local');

        // Creo los negocios
        Schema::create('negocios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->timestamps();
            $table->softDeletes();
        });

        // Vinculo los negocios con los users
        Schema::table('users', function (Blueprint $table) {
            $table->integer('negocio_id')->index()->unsigned()->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('negocio_id')->references('id')->on('negocios');
        });

        // Vinculo los negocios con los clientes
        Schema::table('clientes', function (Blueprint $table) {
            $table->integer('negocio_id')->index()->unsigned()->nullable();
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->foreign('negocio_id')->references('id')->on('negocios');
        });

        // Vinculo los negocios con los locales
        Schema::table('locales', function (Blueprint $table) {
            $table->integer('negocio_id')->index()->unsigned()->nullable();
        });

        Schema::table('locales', function (Blueprint $table) {
            $table->foreign('negocio_id')->references('id')->on('negocios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Creo la tabla cliente_local
        Schema::create('cliente_local', function(Blueprint $table) {
            $table->integer('cliente_id')->unsigned()->index();
            $table->integer('local_id')->unsigned()->index();
        });

        Schema::table('cliente_local', function($table) {
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('local_id')->references('id')->on('locales');
        });

        // Elimino las foráneas de locales, clientes y users
        Schema::table('locales', function (Blueprint $table) {
            $table->dropForeign('locales_negocio_id_foreign');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('clientes_negocio_id_foreign');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_negocio_id_foreign');
        });

        // Elimino las columnas de negocio_id de locales, clientes y users
        Schema::table('locales', function (Blueprint $table) {
            $table->dropColumn('negocio_id');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('negocio_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('negocio_id');
        });

        // Elimino la tabla de negocios
        Schema::drop('negocios');
    }
}
