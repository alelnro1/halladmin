<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class VincularTallesAGeneros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talles', function($table) {
            $table->integer('genero_id')->nullable()->unsigned()->index();
        });

        Schema::table('talles', function($table) {
            $table->foreign('genero_id')->references('id')->on('generos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talles', function($table) {
            $table->dropForeign('talles_genero_id_foreign');
        });

        Schema::table('talles', function($table) {
            $table->dropColumn('genero_id');
        });
    }
}
