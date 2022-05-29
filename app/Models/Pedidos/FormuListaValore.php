<?php

namespace App\Models\Pedidos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FormuListaValore extends Model
{
    use HasFactory;

    public function agregar_campo($lista_valores , $tabla_rel , $slug_cabecera){
        // llamada desde InputSeleccion.php, InputCasilla.php 
        // Agregar a la tabla formu_lista_valores. 

        // Para quitar las comillas que sobran en la lista de valores:
        $aux_valores = str_replace('\"' , '', $lista_valores);
        // para obtener un array partiendo en los saltos de linea:
        $arr_aux_valores = explode(chr(10) , $aux_valores);
        // Crear el array con las opciones, sin valores vacios
        $arr_valores = [];
        // 19jun2021: 
        // la siguiente instrucción es porque el primer elemento 
        // de la lista de valores (0) debe ser una cadena vacia, todo
        // se debe a que cuando este campo sea obligatorio  mysql pondrá 
        // automáticamente cero si este campo es creado cuando la 
        // tabla formu__.... ya tenga registros
        array_push($arr_valores , '');

        foreach($arr_aux_valores as $ele){
            if($ele !== ""){
                array_push($arr_valores , $ele);
            }
        }
        // graba en la tabla formu_lista_valores: 
        foreach($arr_valores as $key => $valor){
            DB::table('formu_lista_valores')->insert([
                'formu__tabla' => $tabla_rel,
                'formu__campo' => $slug_cabecera,
                'conse' => $key,
                'texto' => $valor,
            ]);
        }
        return true;
    }    
}
