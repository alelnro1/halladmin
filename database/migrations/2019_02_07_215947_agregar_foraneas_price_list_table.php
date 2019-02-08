<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarForaneasPriceListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_list', function (Blueprint $table) {
            $table->foreign('negocio_id')->references('id')->on('negocios');
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
        Schema::table('price_list', function (Blueprint $table) {
            $table->dropForeign([
                'negocio_id',
                'local_id'
            ]);
        });
    }
}
