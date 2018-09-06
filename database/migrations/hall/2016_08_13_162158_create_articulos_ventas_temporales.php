<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateArticulosVentasTemporales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo_venta_temporal', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('local_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('link');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('articulo_venta_temporal', function(Blueprint $table) {
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
        Schema::table('articulo_venta_temporal', function(Blueprint $table) {
            $table->dropForeign('articulo_venta_temporal_local_id_foreign');
            $table->dropForeign('articulo_venta_temporal_user_id_foreign');
        });

        Schema::drop('articulo_venta_temporal');
    }
}
