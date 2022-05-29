<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormuTipoProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campos = [
            [
                'tipo_producto_nombre' => 'Tablas no formu__' , 
                'tipo_producto_slug' => 'noaplicanoaplica' , 
                'columnas' => 0 , 
                'user_id' => 1 , 
            ],
        ];

        // You always try to use query builder as much as possible, it prevents SQL injection.
        // query builder:  DB::tablet(...)->insert(...);  // this prevents SQL injection.
        // raw builder:    DB::insert(...);    // this dont prevent injection  
        DB::table('formu_tipo_productos')->insert($campos);

    }
}
	
	
	
	
	
	
	
	