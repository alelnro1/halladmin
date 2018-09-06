<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AgregarForaneaALocalIdVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function($table) {
            $table->dropColumn('local_id');
        });

        Schema::table('ventas', function($table) {
            $table->integer('local_id')->unsigned()->index();

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
        Schema::table('ventas', function(Blueprint $table) {
            $table->dropForeign('ventas_local_id_foreign');
        });
    }
}
