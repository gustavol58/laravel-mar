<?php

namespace App\Models\Pedidos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FormuContenidosEstado extends Model
{
    use HasFactory;

    public $timestamps = false;

    // en fillable hay que listar todos los campos que se usen en CREATE al modelo
    protected $fillable = ['formu_tipo_producto_id' , 'formu__id' , 'formu_estado_id' , 'tiempo_estado' , 'fec_rgto' , 'user_id' ];

    public function tiempos_a_cero($tipo_producto_id , $formu__id){
        // 19oct2021 
        // Recibe un tipo producto id y un id de tabla formu__... y pone 0 en todos 
        // los campos tiempo_estado de la tabla formu_contenidos_estados: 

        FormuContenidosEstado::where('formu_tipo_producto_id', $tipo_producto_id)
            ->where('formu__id', $formu__id)
            ->update(['tiempo_estado' => 0]);            

    }
   

}
