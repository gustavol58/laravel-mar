<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormuCasillasEscogidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formu_casillas_escogidas', function (Blueprint $table) {
            $table->integer('formu__campo_casilla');
            $table->bigInteger('formu_lista_valores_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formu_casillas_escogidas');
    }
}
