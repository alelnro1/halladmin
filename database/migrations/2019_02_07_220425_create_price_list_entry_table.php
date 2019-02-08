<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceListEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_list_entry', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('articulo_id')->unsigned();
            $table->integer('price_list_id')->unsigned();
            $table->double('precio');
            $table->boolean('es_absoluto');

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
        Schema::dropIfExists('price_list_entry');
    }
}
