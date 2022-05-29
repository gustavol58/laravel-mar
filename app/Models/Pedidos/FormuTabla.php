<?php

namespace App\Models\Pedidos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FormuTabla extends Model
{
    use HasFactory;

    // en fillable hay que listar todos los campos que se usen en CREATE al modelo
    protected $fillable = ['tipo_producto_id' , 'nombre' , 'alias', 'titulo' ];

    public $timestamps = false;

    public function leer_nombre_alias_tabla($campo_id){
        // Devuelve el registro que corresponde, en formu_tablas, al campo_id recibido: 
        $arr_params = [];
        $sql = "select 
                tab.nombre, tab.alias 
            from formu_campos cam 
                left join formu_tablas tab on tab.id = cam.tabla_id 
            where cam.id = :campo_id ";
        $arr_params = [
            ':campo_id' => $campo_id ,
        ];
        return collect(DB::select($sql , $arr_params));
    }     
}
