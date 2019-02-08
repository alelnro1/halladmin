<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list', function (Blueprint $table) {
            $table->increments('id');

            $table->string('nombre');
            $table->text('descripcion');
            $table->dateTime('vigencia_desde')->nullable();
            $table->dateTime('vigencia_hasta')->nullable();
            $table->boolean('activo');
            $table->string('dias')->nullable();

            $table->integer('negocio_id')->nullable()->unsigned();
            $table->integer('local_id')->nullable()->unsigned();

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
        Schema::dropIfExists('price_list');
    }
}
