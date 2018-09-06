<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAdministradoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administradores', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->text('descripcion');
            $table->string('archivo');
            $table->string('estado');
            $table->string('password', 60);
            $table->string('domicilio');
            $table->string('email');
            $table->string('telefono');
            $table->dateTime('fecha');
            $table->rememberToken(); // Solo usuarios
            $table->timestamps();
            $table->softDeletes();
    
        });
    
        Schema::table('administradores', function($table) {
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
        Schema::table('administradores', function(Blueprint $table) {
            $table->dropForeign('administradores_FOREIGN_ID_foreign');
        });
        
        Schema::drop('administradores');
    }
}
