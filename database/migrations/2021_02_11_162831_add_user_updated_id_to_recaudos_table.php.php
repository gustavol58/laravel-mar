<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserUpdatedIdToRecaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recaudos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_updated_id')->after('user_asiento_id')->nullable();

            $table->foreign('user_updated_id')
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
            $table->dropColumn('user_updated_id');
        });
    }
}
