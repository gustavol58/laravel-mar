<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariosToConsignacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consignaciones', function (Blueprint $table) {
            $table->string('documento' , 254)->nullable()->default(null)->collation('utf8_unicode_ci');
            $table->string('oficina' , 254)->nullable()->default(null)->collation('utf8_unicode_ci');
            $table->string('descripcion' , 254)->nullable()->default(null)->collation('utf8_unicode_ci');
            $table->string('referencia' , 254)->nullable()->default(null)->collation('utf8_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consignaciones', function (Blueprint $table) {
            $table->dropColumn('documento');
            $table->dropColumn('oficina');
            $table->dropColumn('descripcion');
            $table->dropColumn('referencia');
        });
    }
}
