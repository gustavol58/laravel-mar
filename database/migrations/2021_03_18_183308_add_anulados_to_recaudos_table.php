<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnuladosToRecaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recaudos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_anulo_id')->after('user_aprobo_id')->nullable(); 
            $table->dateTime('created_anulo_at')->after('created_asiento_at')->nullable(); 
            $table->text('notas_anulado')->after('notas_asiento')->nullable();

            $table->foreign('user_anulo_id')
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
        Schema::table('recaudos', function (Blueprint $table) {
            $table->dropColumn('user_anulo_id');
            $table->dropColumn('created_anulo_at');
            $table->dropColumn('notas_anulado');
            $table->dropForeign('user_anulo_id'); 
        });
    }
}
