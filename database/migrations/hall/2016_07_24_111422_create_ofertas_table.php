<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateOfertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ofertas', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->text('descripcion');
            $table->string('archivo');
            $table->string('estado');
            $table->dateTime('fecha');
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::table('ofertas', function($table) {
            //$table->foreign('RELACION_ID')->references('id')->on('OTRA_TABLA');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ofertas');
    }
}
