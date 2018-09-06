<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AgregarUserIdAVentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function(Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
        });

        Schema::table('ventas', function($table) {
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
        Schema::table('ventas', function(Blueprint $table) {
            $table->dropForeign('ventas_user_id_foreign');
        });

        Schema::table('ventas', function(Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
