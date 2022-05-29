<?php

namespace App\Models\Pedidos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormuDetalle extends Model
{
    use HasFactory;

    // en fillable hay que listar todos los campos que se usen en CREATE al modelo
    protected $fillable = ['tipo_producto_id' , 'html_elemento_id' , 'cabecera' , 'slug' , 'orden' , 'roles' , 'obligatorio' , 'max_largo' , 'min_num','max_num' , 'lista_datos', 'origen_casilla_radio' , 'lista_tipos_archivos' , 'multivariable_id', 'user_id' ];

    public function obtener_siguiente_orden($tipo_producto_id){
        $arr_params = [];
        $sql = "select max(orden) orden_max
            from formu_detalles 
            where tipo_producto_id = :tipo_producto_id ";
        $arr_params = [
            'tipo_producto_id' => $tipo_producto_id ,
        ];
        $tipo_productos = collect(DB::select($sql , $arr_params));
        return $tipo_productos[0]->orden_max + 1;        
    } 

    public function leer_cabeceras_orden($tipo_producto_id){
        $arr_params = [];
        $sql = "select det.cabecera,ele.nombre_elemento tipo,det.id detalle_id
            FROM formu_detalles det 
                left join formu_html_elementos ele on ele.id=det.html_elemento_id 
            where det.tipo_producto_id = :tipo_producto_id 
            order by det.orden ";
        $arr_params = [
            'tipo_producto_id' => $tipo_producto_id ,
        ];
        // return collect(DB::select($sql , $arr_params))->toArray();
        return collect(DB::select($sql , $arr_params));
    } 

    public function leer_cabeceras_eliminar($tipo_producto_id){ 
        // llamado desde el mount() de InputEliminar para obtener los campos 
        // de la tabla que corresponden al parámetro tipo_producto_id
        $arr_params = [];
        // $sql = "select det.slug campo, ele.nombre_elemento tipo, 
        //         false eliminar, false marca_eliminar, 
        //         det.id detalle_id
        //     from formu_detalles det 
        //         left join formu_html_elementos ele on ele.id=det.html_elemento_id 
        //     where det.tipo_producto_id = :tipo_producto_id and det.html_elemento_id <> 8 
        //     order by det.orden ";
        $sql = "select det.cabecera, det.slug campo, ele.nombre_elemento tipo, ele.id html_elemento_id,
                false eliminar, false marca_eliminar, 
                det.id detalle_id,
                (SELECT cam.id 
                    FROM formu_campos cam 
                        left join formu_tablas tab on tab.id=cam.tabla_id 
                    where cam.nombre=det.slug 
                        and tab.tipo_producto_id = :tipo_producto_id1 ) campo_id
            from formu_detalles det 
                left join formu_html_elementos ele on ele.id=det.html_elemento_id 
            where det.tipo_producto_id = :tipo_producto_id2 
            order by det.orden ";
        $arr_params = [
            'tipo_producto_id1' => $tipo_producto_id ,
            'tipo_producto_id2' => $tipo_producto_id ,
        ];
        // return collect(DB::select($sql , $arr_params))->toArray();
        return collect(DB::select($sql , $arr_params));
    } 

    public function obtener_slug_formu_detalles($cabecera , $orden){
        // 15sep2021 
        // El slug para la tabla formu_detalles es obtenido a partir de la cabecera, 
        // se le cambian los - por _ y se le agrega un número que haga que el 
        // tipo_producto_id+slug sean únicos en la tabla formu_detalles. 
        // También se verifica que la cabecera tenga menos de 60 caracteres para que
        // al agregar el número al final, no vaya a sobrepasar los 64 caracteres que exige el 
        // nombre de los campos en formu__.... 
        if(strlen(trim($cabecera)) <= 60){
            $slug = str_replace('-','_',Str::slug($cabecera)) . '_' . $orden; 
        }else{
            $slug = str_replace('-','_',Str::slug(substr($cabecera, 0, 60))) . '_' . $orden; 
        }     
        return $slug;   
    }    
}
