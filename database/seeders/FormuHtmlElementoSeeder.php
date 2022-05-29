<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormuHtmlElementoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $elementos = [
            'Texto', 
            'Número entero', 
            'Selección desde valores', 
            'Casillas', 
            'Botón radio', 
            'Email', 
            'Fecha', 
            'Nueva sección', 
            'Número decimal',
            'Selección desde tabla',
            'Subir archivo'
        ];

        // You always try to use query builder as much as possible, it prevents SQL injection.
        // query builder:  DB::tablet(...)->insert(...);  // this prevents SQL injection.
        // raw builder:    DB::insert(...);    // this dont prevent injection          
        foreach($elementos as $ele){
            DB::table('formu_html_elementos')->insert([
                'nombre_elemento' => $ele,
            ]);
        }
    }
}
	
	
	
	
	
	
	
	