<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoriaToRecaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recaudos', function (Blueprint $table) {
            $table->integer('categoria')->comment('1: Anticipo, 2: Pago facturas')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recaudos', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
}
