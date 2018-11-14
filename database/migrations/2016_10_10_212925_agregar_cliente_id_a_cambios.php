<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarClienteIdACambios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cambios', function (Blueprint $table) {
            $table->string('nombre');
        });

        // Vinculo los negocios con los users
        Schema::table('users', function (Blueprint $table) {
            $table->integer('negocio_id')->index()->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
