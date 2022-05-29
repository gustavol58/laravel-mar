<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormuCamposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formu_campos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tabla_id');   
            $table->string('nombre' , 64)->comment('Corresponde al nombre físico del campo dentro de la tabla en la b.d.');
            $table->string('alias' , 64);  
            $table->string('titulo' , 64);
            $table->boolean('titulo_visible')->comment('1(true): se mostrará en campos disponibles, 0(false): no se mostrará');
            $table->text('left_joins')->nullable();
            $table->text('equivalentes')->nullable();

            $table->foreign('tabla_id')
                ->references('id')
                ->on('formu_tablas')
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
        Schema::dropIfExists('formu_campos');
    }
}
