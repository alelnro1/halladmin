<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePleHistoricosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ple_historicos', function (Blueprint $table) {
            $table->increments('id');

            $table->dateTime('vigencia_desde');
            $table->dateTime('vigencia_hasta');
            $table->double('precio');

            $table->integer('articulo_id')->unsigned()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ple_historicos');
    }
}
