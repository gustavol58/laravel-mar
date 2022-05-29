<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariosToRecaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recaudos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_asiento_id')->after('user_id')->nullable; 
            $table->dateTime('created_asiento_at')->after('updated_at')->nullable(); 
            $table->double('valor_asiento')->after('asentado')->nullable(); 

            $table->foreign('user_asiento_id')
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
            $table->dropColumn('user_asiento_id');
            $table->dropColumn('created_asiento_at');
            $table->dropColumn('valor_asiento');
            $table->dropForeign('user_asiento_id'); 
        });
    }
}
