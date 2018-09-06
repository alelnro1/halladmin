<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AgregarLocalACambios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cambios', function(Blueprint $table) {
            $table->integer('local_id')->unsigned()->index();
        });

        Schema::table('cambios', function($table) {
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
        Schema::table('cambios', function(Blueprint $table) {
            $table->dropColumn('local_id');
            $table->dropForeign('cambios_local_id_foreign');
        });
    }
}
