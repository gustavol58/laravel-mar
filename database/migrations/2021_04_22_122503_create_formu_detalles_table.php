<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormuDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formu_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_producto_id');
            $table->unsignedBigInteger('html_elemento_id');
            $table->string('cabecera' , 64);
            $table->unique(['tipo_producto_id' , 'cabecera']);
            $table->string('slug' , 64);
            $table->smallInteger('orden')->comment('Es independiente para cada tipo_producto_id');
            $table->boolean('obligatorio')->comment('1(true): es obligatorio, 0(false): es opcional');
            $table->smallInteger('max_largo')->nullable()->comment('Para elemento_html texto: caracteres, para elemento_html subir archivo: megas');
            $table->integer('min_num')->nullable()->comment('Aplica para elemento_html número entero o decimal');
            $table->integer('max_num')->nullable()->comment('Aplica para elemento_html número entero o decimal');
            $table->text('lista_datos')->nullable()->comment('Aplica para elemento_html selección desde tabla 10: Contiene cada id de campo grabado en formu_campos, separados por _@@@_');
            // 30jun2021 $table->text('origen_casilla_radio')->nullable()->comment('Aplica para elementos html casillas y radio: Contiene los valores a mostrar para cada opción, separados por _@@@_');
            $table->string('lista_tipos_archivos' , 254)->nullable()->comment('Aplica para elementos html subri archivo: Contiene los tipos de archivos permitidos, separados por _@@@_');
            $table->timestamps();
            $table->unsignedBigInteger('user_id');         
            
            $table->foreign('tipo_producto_id')
                ->references('id')
                ->on('formu_tipo_productos')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('html_elemento_id')
                ->references('id')
                ->on('formu_html_elementos')
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
        Schema::dropIfExists('formu_detalles');
    }
}
