<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AgregarUserIdYSaldoACambios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cambios', function($table) {
            $table->integer('user_id')->unsigned()->index();
            $table->double('saldo');
        });

        Schema::table('cambios', function($table) {
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
        Schema::table('cambios', function($table) {
            //$table->dropForeign('cambios_user_id_foreign');
        });

        Schema::table('cambios', function($table) {
            $table->dropColumn('user_id');
            $table->dropColumn('saldo');
        });
    }
}




