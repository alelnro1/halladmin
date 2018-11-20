<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarCuitIvaNegocios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->string('cuit');
            $table->string('condicion_iva');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropColumn([
                'cuit',
                'condicion_iva'
            ]);
        });
    }
}
