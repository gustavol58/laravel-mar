<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recaudos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->date('fec_pago');
            $table->double('valor');
            $table->text('obs')->nullable();            
            $table->boolean('asentado')->comment('false: No asentado, true: Asentado');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('cliente_id')
                ->references('id')
                ->on('clientes')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recaudos');
    }
}
