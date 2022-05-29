<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormuTipoProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formu_tipo_productos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_producto_nombre' , 58)->unique();
            $table->string('tipo_producto_slug' , 58);
            $table->string('titulo' , 254)->nullable();
            $table->string('subtitulo' , 254)->nullable();
            $table->tinyInteger('columnas')->default(2)->comment('Inicialmente siempre serán 2 columnas por formulario');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');            

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
        Schema::dropIfExists('formu_tipo_productos');
    }
}
