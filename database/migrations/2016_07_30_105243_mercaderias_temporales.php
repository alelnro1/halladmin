<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class MercaderiasTemporales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercaderias_temporales', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('local_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('link');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('mercaderias_temporales', function($table) {
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
        Schema::table('mercaderias_temporales', function(Blueprint $table) {
            $table->dropForeign('mercaderias_temporales_local_id_foreign');
            $table->dropForeign('mercaderias_temporales_user_id_foreign');
        });

        Schema::drop('mercaderias_temporales');
    }
}
