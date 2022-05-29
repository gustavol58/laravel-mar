<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormuListaValoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formu_lista_valores', function (Blueprint $table) {
            $table->id();
            $table->string('formu__tabla' , 64);
            $table->string('formu__campo' , 64);
            $table->integer('conse');
            $table->string('texto' , 254);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formu_lista_valores');
    }
}
