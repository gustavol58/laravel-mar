<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRecaudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recaudos', function (Blueprint $table) {
            $table->text('notas_asiento')->nullable()->after('asentado');  
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
            $table->dropColumn('notas_asiento');
        });
    }
}
