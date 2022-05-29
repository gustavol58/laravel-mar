<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoToRecaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recaudos', function (Blueprint $table) {
            $table->integer('estado')->comment('1 Nuevo, 2 Aprobado, 3 Asentado, 4 Anulado')->after('obs');
            $table->unsignedBigInteger('user_aprobo_id')->after('user_updated_id')->nullable(); 
            $table->dateTime('created_aprobo_at')->after('updated_at')->nullable(); 

            $table->foreign('user_aprobo_id')
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
            $table->dropColumn('estado');
            $table->dropColumn('user_aprobo_id');
            $table->dropColumn('created_aprobo_at');
        });
    }
}
