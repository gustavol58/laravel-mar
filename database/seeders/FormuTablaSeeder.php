<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormuTablaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tablas = [
            ['clientes' , 'cli' , 'Clientes' , 1],
            ['recaudos' , 'rec' , 'Recaudos' , 1],
            ['consignaciones' , 'con' , 'Consignaciones' , 1],
        ];

        foreach($tablas as $tabla){
            // You always try to use query builder as much as possible, it prevents SQL injection.
            // query builder:  DB::tablet(...)->insert(...);  // this prevents SQL injection.
            // raw builder:    DB::insert(...);    // this dont prevent injection
            DB::table('formu_tablas')->insert([
                'tipo_producto_id' => $tabla[3],
                'nombre' => $tabla[0],
                'alias' => $tabla[1],
                'titulo' => $tabla[2],
            ]);
        }
    }
}
