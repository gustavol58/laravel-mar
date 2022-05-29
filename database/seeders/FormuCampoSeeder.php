<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormuCampoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campos = [
            // tabla clientes:
            [
                'tabla_id' => 1 , 
                'nombre' => 'id' , 
                'alias' => 'cli.id' , 
                'titulo' => 'Id' , 
                'titulo_visible' => 0 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],
            [
                'tabla_id' => 1 , 
                'nombre' => 'nit' , 
                'alias' => 'cli.nit' , 
                'titulo' => 'Nit' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],
            [
                'tabla_id' => 1 , 
                'nombre' => 'nom_cliente' , 
                'alias' => 'cli.nom_cliente' , 
                'titulo' => 'Nombre del cliente' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],
            [
                'tabla_id' => 1 , 
                'nombre' => 'comercial_id' , 
                'alias' => 'usu1.name' , 
                'titulo' => 'Nombre del comercial' , 
                'titulo_visible' => 1 , 
                'left_joins' => ' left join users usu1 on usu1.id=cli.comercial_id ' , 
                'equivalentes' => '' 
            ],
             
            // tabla recaudos: 
            [
                'tabla_id' => 2 , 
                'nombre' => 'id' , 
                'alias' => 'rec.id' , 
                'titulo' => 'Número de recaudo' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],
            [
                'tabla_id' => 2 , 
                'nombre' => 'categoria' , 
                'alias' => 'rec.categoria' , 
                'titulo' => 'Categoria(Anticipo/Pago)' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => "1 then 'Anticipo'_@@@_2 then 'Pago facturas'" 
            ],       
            [
                'tabla_id' => 2 , 
                'nombre' => 'fec_pago' , 
                'alias' => 'rec.fec_pago' , 
                'titulo' => 'Fecha recaudo' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],
            [
                'tabla_id' => 2 , 
                'nombre' => 'valor' , 
                'alias' => 'rec.valor' , 
                'titulo' => 'Valor recaudado' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],
            [
                'tabla_id' => 2 , 
                'nombre' => 'tipo' , 
                'alias' => 'rec.tipo' , 
                'titulo' => 'Tipo(Efectivo/Consignación)' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => "1 then 'Efectivo'_@@@_2 then 'Consignación'" 
            ],
            [
                'tabla_id' => 2 , 
                'nombre' => 'estado' , 
                'alias' => 'rec.estado' , 
                'titulo' => 'Estado' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => "1 then 'Nuevo'_@@@_2 then 'Aprobado'_@@@_3 then 'Asentado'_@@@_4 then 'Anulado'" 
            ],    
            [
                'tabla_id' => 2 , 
                'nombre' => 'cliente_id' , 
                'alias' => 'cli.nom_cliente' , 
                'titulo' => 'Nombre del cliente' , 
                'titulo_visible' => 1 , 
                'left_joins' => ' left join clientes cli on cli.id=rec.cliente_id ' , 
                'equivalentes' => '' 
            ],
            [
                'tabla_id' => 2 , 
                'nombre' => 'cliente_id_comercial_id' , 
                'alias' => 'usu2.name' , 
                'titulo' => 'Nombre del comercial' , 
                'titulo_visible' => 1 , 
                'left_joins' => ' left join clientes cli2 on cli2.id=rec.cliente_id left join users usu2 on usu2.id = cli2.comercial_id ' , 
                'equivalentes' => '' 
            ],
            [
                'tabla_id' => 2 , 
                'nombre' => 'user_id' , 
                'alias' => 'usu3.name' , 
                'titulo' => 'Creado por' , 
                'titulo_visible' => 1 , 
                'left_joins' => ' left join users usu3 on usu3.id = rec.user_id ' , 
                'equivalentes' => '' 
            ],    
            
            // tabla consignaciones: 
            [
                'tabla_id' => 3 , 
                'nombre' => 'id' , 
                'alias' => 'con.id' , 
                'titulo' => 'Número de consignación' , 
                'titulo_visible' => 0 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],              
            [
                'tabla_id' => 3 , 
                'nombre' => 'fecha' , 
                'alias' => 'con.fecha' , 
                'titulo' => 'Fecha consignación' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],              
            [
                'tabla_id' => 3 , 
                'nombre' => 'valor' , 
                'alias' => 'con.valor' , 
                'titulo' => 'Valor' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],              
            [
                'tabla_id' => 3 , 
                'nombre' => 'estado' , 
                'alias' => 'con.estado' , 
                'titulo' => 'Estado(Sin asignar/Asignada)' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => "1 then 'Sin asignar'_@@@_2 then 'Asignada'" 
            ],              
            [
                'tabla_id' => 3 , 
                'nombre' => 'recaudo_id' , 
                'alias' => 'con.recaudo_id' , 
                'titulo' => 'Número de recaudo asignado' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],              
            [
                'tabla_id' => 3 , 
                'nombre' => 'referencia' , 
                'alias' => 'con.referencia' , 
                'titulo' => 'Referencia (Cliente)' , 
                'titulo_visible' => 1 , 
                'left_joins' => '' , 
                'equivalentes' => '' 
            ],              
        ];

        // You always try to use query builder as much as possible, it prevents SQL injection.
        // query builder:  DB::tablet(...)->insert(...);  // this prevents SQL injection.
        // raw builder:    DB::insert(...);    // this dont prevent injection        
        DB::table('formu_campos')->insert($campos);

        // foreach($campos as $campo){
        //     DB::table()->insert([
        //         foreach($campo as $key => $valor){
        //             $key => $valor,
        //         }
        //     ]);
        // }
    }
}
