<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAuditoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditoria', function(Blueprint $table) {
            $table->increments('id');
            $table->double('costo_anterior')->comment('El costo del artículo cuando antes de ingresar al sistema');
            $table->double('costo_nuevo')->comment('El costo del artículo cuando se ingresó al sistema');
            $table->double('precio_anterior')->comment('El precio del artículo que tenia el articulo antes de ingresarlo al sistema');
            $table->double('precio_nuevo')->comment('El precio del artículo cuando se ingresó al sistema');
            $table->integer('stock_anterior');
            $table->integer('stock_nuevo');
            $table->integer('cambio_id')->unsigned()->nullable();
            $table->integer('venta_id')->unsigned()->nullable();
            $table->integer('articulo_id')->unsigned()->comment('El articulo que auditamos');
            $table->integer('proveedor_id')->unsigned()->nullable()->comment('El proveedor del artículo');
            $table->integer('user_id')->unsigned()->comment('El usuario que ingresó el artículo o realizó la venta o el cambio');
            $table->integer('oferta_id')->unsigned()->nullable()->comment('La oferta a la que pertenece el artículo al momento de ingresarlo');
            $table->timestamps();
        });

        Schema::table('auditoria', function(Blueprint $table) {
            $table->foreign('articulo_id')->references('id')->on('articulos');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('oferta_id')->references('id')->on('ofertas');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('cambio_id')->references('id')->on('cambios');
            $table->foreign('venta_id')->references('id')->on('ventas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auditoria', function(Blueprint $table) {
            $table->dropForeign('auditoria_articulo_id_foreign');
            $table->dropForeign('auditoria_user_id_foreign');
            $table->dropForeign('auditoria_oferta_id_foreign');
            $table->dropForeign('auditoria_proveedor_id_foreign');
            $table->dropForeign('auditoria_cambio_id_foreign');
            $table->dropForeign('auditoria_venta_id_foreign');
        });

        Schema::drop('auditoria');
    }
}
