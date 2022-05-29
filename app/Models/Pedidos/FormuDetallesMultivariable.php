<?php

namespace App\Models\Pedidos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FormuDetallesMultivariable extends Model
{
    use HasFactory;

    public $timestamps = false;

    // en fillable hay que listar todos los campos que se usen en CREATE al modelo
    protected $fillable = ['formu_detalles_id' , 'col' , 'cabecera' , 'roles' , 'origen_tipo' , 'origen_datos'   ];

    // public function obtener_siguiente_orden($tipo_producto_id){
    //     $arr_params = [];
    //     $sql = "select max(orden) orden_max
    //         from formu_detalles 
    //         where tipo_producto_id = :tipo_producto_id ";
    //     $arr_params = [
    //         'tipo_producto_id' => $tipo_producto_id ,
    //     ];
    //     $tipo_productos = collect(DB::select($sql , $arr_params));
    //     return $tipo_productos[0]->orden_max + 1;        
    // } 

    // public function leer_cabeceras_orden($tipo_producto_id){
    //     $arr_params = [];
    //     $sql = "select det.cabecera,ele.nombre_elemento tipo,det.id detalle_id
    //         FROM formu_detalles det 
    //             left join formu_html_elementos ele on ele.id=det.html_elemento_id 
    //         where det.tipo_producto_id = :tipo_producto_id 
    //         order by det.orden ";
    //     $arr_params = [
    //         'tipo_producto_id' => $tipo_producto_id ,
    //     ];
    //     // return collect(DB::select($sql , $arr_params))->toArray();
    //     return collect(DB::select($sql , $arr_params));
    // } 

    // public function leer_cabeceras_eliminar($tipo_producto_id){ 
    //     // llamado desde el mount() de InputEliminar para obtener los campos 
    //     // de la tabla que corresponden al parámetro tipo_producto_id
    //     $arr_params = [];
    //     // $sql = "select det.slug campo, ele.nombre_elemento tipo, 
    //     //         false eliminar, false marca_eliminar, 
    //     //         det.id detalle_id
    //     //     from formu_detalles det 
    //     //         left join formu_html_elementos ele on ele.id=det.html_elemento_id 
    //     //     where det.tipo_producto_id = :tipo_producto_id and det.html_elemento_id <> 8 
    //     //     order by det.orden ";
    //     $sql = "select det.slug campo, ele.nombre_elemento tipo, ele.id html_elemento_id,
    //             false eliminar, false marca_eliminar, 
    //             det.id detalle_id,
    //             (SELECT cam.id 
    //                 FROM formu_campos cam 
    //                     left join formu_tablas tab on tab.id=cam.tabla_id 
    //                 where cam.nombre=det.slug 
    //                     and tab.tipo_producto_id = :tipo_producto_id1 ) campo_id
    //         from formu_detalles det 
    //             left join formu_html_elementos ele on ele.id=det.html_elemento_id 
    //         where det.tipo_producto_id = :tipo_producto_id2 
    //         order by det.orden ";
    //     $arr_params = [
    //         'tipo_producto_id1' => $tipo_producto_id ,
    //         'tipo_producto_id2' => $tipo_producto_id ,
    //     ];
    //     // return collect(DB::select($sql , $arr_params))->toArray();
    //     return collect(DB::select($sql , $arr_params));
    // } 
}
