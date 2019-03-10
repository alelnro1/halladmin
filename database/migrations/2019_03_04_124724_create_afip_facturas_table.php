<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAfipFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afip_facturas', function (Blueprint $table) {
            $table->increments('id');

            $table->double('importe_total');
            $table->string('documento');
            $table->string('voucher');

            $table->integer('venta_id')->nullable()->unsigned()->index();
            $table->integer('cliente_id')->nullable()->unsigned()->index();
            $table->integer('negocio_id')->nullable()->unsigned()->index();
            $table->integer('local_id')->nullable()->unsigned()->index();


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
        Schema::dropIfExists('afip_facturas');
    }
}
