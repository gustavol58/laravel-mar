<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormuTablasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formu_tablas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_producto_id');
            $table->string('nombre' , 64)->unique()->comment('Corresponde al nombre físico de la tabla en la b.d.');
            $table->string('alias' , 6)->unique();
            $table->string('titulo' , 64)->unique();

            $table->foreign('tipo_producto_id')
                ->references('id')
                ->on('formu_tipo_productos')
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
        Schema::dropIfExists('formu_tablas');
    }
}
