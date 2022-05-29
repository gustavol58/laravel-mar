<?php

namespace App\Models\Pedidos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Pedidos\FormuTabla;

class FormuCampo extends Model
{
    use HasFactory;

    // en fillable hay que listar todos los campos que se usen en CREATE al modelo
    protected $fillable = ['tabla_id' , 'nombre', 'alias', 'titulo', 'titulo_visible', 'left_joins', 'equivalentes'];

    public $timestamps = false;

    public function agregar_campo($tipo_producto_id , $slug , $titulo , $leftjoin_agregar = false , $tabla_nombre_join = null){
        // Agregar a la tabla formu_campos un registro de acuerdo al campo tipo 
        // desde valores o desde tabla que acaba de ser creado por el usuario.
        // Si $leftjoin_agregar es true: lo debe calcular.
        // Notar que es un método recargado en los dos últimos parámetros, los cuales 
        // solamente llegarán con info si este método es llamado desde InputSeleccion.php

        // Para saber cual es el id de la tabla a partir de tipo_producto_id: 
        $tabla_ = FormuTabla::select('id' , 'alias')->where('tipo_producto_id' , $tipo_producto_id)->first();
// 06oct2021:
// echo $tipo_producto_id;
// echo $slug;
// dd($tabla_);
        $tabla_id = $tabla_->id;
        $tabla_alias = $tabla_->alias;
        $nuevo_campo_alias = $tabla_alias  . "." . $slug;

// echo "<pre>";
// // print_r($tabla_);
// echo "<br>TABLA ID $tabla_id";    //  4
// echo "<br>TABLA NOMBRE JOIN $tabla_nombre_join";   //    clientes
// echo "<br>TABLA ALIAS $tabla_alias";   //  f4 
// echo "<br>NUEVO CAMPO ALIAS $nuevo_campo_alias";   //  f4.aaa
// dd('fin');

        // Si es necesario calcular los leftjoins (o sea que es lista desde tabla):
        if($leftjoin_agregar){
            // porque los alias hay que usarlos con un id distinto: 
            $sgte_id = FormuCampo::max('id') + 1;
            $tabla_alias_join = substr($tabla_nombre_join , 0 , 3) . $sgte_id;

            // 08sep2021: 
            // Se corrigió un error de hace mucho: no se estaba agregando el consecutivo 
            // a la condición del left join:
            $leftjoin_aux = 'left join ' . $tabla_nombre_join . ' ' . $tabla_alias_join . ' on ' .  $tabla_alias_join . '.id = f' . $tabla_id . '.' . $slug;
        }else{
            $leftjoin_aux = '';
        }
// echo "lefjoin es: <br>";
// dd($leftjoin_aux);

        FormuCampo::create([
            'tabla_id' => $tabla_id,
            'nombre' => $slug,
            'alias' => $nuevo_campo_alias,
            'titulo' => $titulo,
            'titulo_visible' => 1,
            'left_joins' => $leftjoin_aux ,
            'equivalentes' => '',
        ]);   
     
    }     
}
