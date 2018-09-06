<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateLocalTalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_talle', function(Blueprint $table) {
            $table->integer('local_id')->unsigned();
            $table->integer('talle_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('local_talle', function(Blueprint $table) {
            $table->foreign('local_id')->references('id')->on('locales');
            $table->foreign('talle_id')->references('id')->on('talles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('local_talle', function(Blueprint $table) {
            $table->dropForeign('local_talle_local_id_foreign');
            $table->dropForeign('local_talle_talle_id_foreign');
        });

        Schema::drop('local_talle');
    }
}
