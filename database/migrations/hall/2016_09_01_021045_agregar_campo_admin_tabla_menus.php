<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarCampoAdminTablaMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function(Blueprint $table) {
            $table->boolean('admin')->comment('Si el campo es true => el valor se le asignará a los admins ni bien se crean');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function(Blueprint $table) {
            $table->dropColumn('admin');
        });
    }
}
