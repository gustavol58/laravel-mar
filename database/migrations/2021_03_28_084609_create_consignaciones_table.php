<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsignacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consignaciones', function (Blueprint $table) {
            $table->id();
            $table->string('original' , 254)->comment('Nombre del archivo que fue cargado');
            $table->date('fecha');
            $table->double('valor');
            $table->integer('estado')->comment('1 Sin asignar, 2 Asignada');
            $table->unsignedBigInteger('recaudo_id')->nullable();
            $table->unsignedBigInteger('user_importo_id'); 
            $table->unsignedBigInteger('user_asigno_id')->nullable(); 
            $table->dateTime('created_importo_at'); 
            $table->dateTime('created_asigno_at')->nullable(); 

            $table->foreign('recaudo_id')
                ->references('id')
                ->on('recaudos')
                ->onDelete('restrict')
                ->onUpdate('restrict');             
            $table->foreign('user_importo_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');             
            $table->foreign('user_asigno_id')
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
        Schema::dropIfExists('consignaciones');
    }
}
