<?php

namespace App\Models\Pedidos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Pedidos\ConfigFormu\ConfigIndex;
use App\Models\Pedidos\FormuCampo;
use App\Models\Pedidos\FormuContenidosEstado;

class FormuTipoProducto extends Model
{
    use HasFactory;

    // en fillable hay que listar todos los campos que se usen en CREATE al modelo
    protected $fillable = ['prefijo' ,'tipo_producto_nombre' , 'tipo_producto_slug', 'columnas' , 'user_id' ];

    // public function ver_tipo_producto($tipo_producto_id){
    //     // se usó el 23abr pero luego decidió hacerse con eloquent en el 
    //     // mismo ConfigIndex.php
    //     $arr_params1 = [];
    //     $sql1 = "select tipo_producto_nombre, 
    //             titulo, subtitulo, columnas
    //         from formu_tipo_productos 
    //         where id = :tipo_producto_id ";
    //     $arr_params1 = [
    //         ':tipo_producto_id' => $tipo_producto_id ,
    //     ];
    //     return collect(DB::select($sql1 , $arr_params1));        
    // }   
    
    public function obtener_registros_formu__($tipo_producto_id , $slug_tabla_formu__ , $campos_detalle , $ordenar_campo , $ordenar_tipo , $arr_filtros_slug){
        // Llamado desde VerFormu@render() 
        // Arma y ejecuta la instrucción mysql que obtendrá todos los registros de la
        // tabla formu__.... correspondiente.
        // Tiene en cuenta si hay necesidad de armar wheres de acuerdo a filtros
        // que el usuario haya digitado en el gridview
// echo "<pre>";        
// echo "<br>tipo_producto_id : ".$tipo_producto_id;
// echo "<br>slug : ".$slug_tabla_formu__;
// echo "<br>campos_detalle : ";
// print_r($campos_detalle);
// echo "<br>ordenar_campo : ".$ordenar_campo;
// echo "<br>ordenar_tipo : ".$ordenar_tipo;
// echo "<br>arr_filtros_slug : ";
// print_r($arr_filtros_slug);
// echo "<br>*******************************************<br><br>";
// dd('fin');

        // Determinar cuál es la correspondiente tabla formu__......
        $tabla_formu__ =  'formu__' . $slug_tabla_formu__->tipo_producto_slug; 

        $campos_estados_select = "";
        if($slug_tabla_formu__->prefijo !== null){
            // 19oct2021: 
            // Columna estado, va después del código de producto, pero 
            // solamente para tipos de producto cuyo prefijo sea distinto a null
            // En la vista esta columna se mostrará como 'action' para los admin y
            // como columna normal para los otros roles
            // Cuando el admin da click a esta columna irá a un modal que le permitirá
            // cambiar los estados.
            $campos_estados_select = " fe.nombre_estado estado, fe.cerrado estado_cerrado, ";
        }
        
        // Crear la cadena para los campos intermedios (es decir los que van 
        // después del id, el código y los botones de estado; y antes de 
        // el usuario y fecha de creación):
        $campos_intermedios = "";
// echo "<pre>";
// dd($campos_detalle);   
        foreach($campos_detalle as $fila){
            switch ($fila['html_elemento_id']) {
                case 3:
                    // es un campo de lista de valores,
                    // para que muestre los textos y no los id de cada texto: 
                    $campos_intermedios = $campos_intermedios . "(select texto 
                        from formu_lista_valores flv 
                        where flv.id = f." . $fila['slug'] . ") " . $fila['slug'] . ",";
                    break;                    
                case 4:
                    // es un campo de  de casillas, debe buscar en la tabla
                    // pivote formu_casillas_escogidas las casillas que 
                    // fueron escogidas por el usuario
                    $campos_intermedios = $campos_intermedios . "(select 
                            group_concat(flv.texto) texto 
                        from formu_casillas_escogidas fce 
                            left join formu_lista_valores flv 
                                on flv.id=fce.formu_lista_valores_id 
                        where fce.formu__campo_casilla = f." . $fila['slug'] . ") " . $fila['slug'] . ",";
                    break; 
                case 5:
                    // es un campo radio button,
                    // para que muestre los textos y no los id de cada texto: 
                    $campos_intermedios = $campos_intermedios . "(select texto 
                        from formu_lista_valores flv 
                        where flv.id = f." . $fila['slug'] . ") " . $fila['slug'] . ",";
                    break;                      
                case 10:
                    // es un campo de lista desde tabla:
                    // en lista_datos llegan los id de formu_campos, por 
                    // ejemplo 5_@@@_8
                    // 24jun2021 
                    // Se mostrará en el gridview SOLAMENTE EL PRIMERO de los campos 
                    // guardados en lista_datos. 
                    // la siguiente función devolverá un array a partir del cual 
                    // obtendremos las variables que necesitamos para la próxima mysql: 
                    $arr_columnas_mostrar = explode('_@@@_' , $fila['lista_datos']);
                    $tabla_padre = $this->obtener_datos_para_join($arr_columnas_mostrar);
                    $tabla_padre_nombre = $tabla_padre[0]->tabla_padre_nom;
                    $tabla_padre_alias = $tabla_padre[0]->tabla_padre_alias;
                    $campo_mostrar_alias_slug = $tabla_padre_alias . "." . $tabla_padre[0]->campo_hijo_nom;
                    $campos_intermedios = $campos_intermedios 
                        . "(select " . $campo_mostrar_alias_slug 
                        . " from " . $tabla_padre_nombre . " " . $tabla_padre_alias 
                        . " where " . $tabla_padre_alias . ".id=f." . $fila['slug'] . ") " 
                        . $fila['slug'] . ",";
                    break;
                case 12: 
                    // 19sep2021:
                    $campos_intermedios = $campos_intermedios . " '' " . $fila['slug'] . ",";
                    break;
                default:
                    // distintos a lista de valores o los demás:
                    $campos_intermedios = $campos_intermedios . $fila['slug'] . ",";
                    break;
            }
        }
        $campos_intermedios = trim($campos_intermedios , ',');
// dd($campos_intermedios);

        // para el manejo de los where: 
        $arr_params1 = [];
        $where_ = "";
// dd($arr_filtros_slug);
        
        foreach($arr_filtros_slug as $key => $ele){
            // Si el usuario no digito un filtro para el campo, no se incluye en el where: 
            if($ele !== ""){
                // Si es el primer campo a filtrar, empezar con where, sino agregar and :
                if($where_ == ""){
                    $where_ = " where ";
                }else{
                    $where_ = $where_ . " and ";
                }

                // Averiguar si hay campos que sean de listas de valores, casillas 
                // o radio button, y si es asi, usar el case correspondiente:
                $key_campos_detalle = array_search($key, array_column($campos_detalle, 'slug'));
                $html_elemento_id_campos_detalle = $campos_detalle[$key_campos_detalle]['html_elemento_id'];
                switch ($html_elemento_id_campos_detalle) {
                    case 3:
                        // es un campo de lista de valores,
                        // para que muestre los textos y no los id de cada texto: 
                        $where_ = $where_ . "(select texto 
                            from formu_lista_valores flv 
                            where flv.id = f." .  $key . ") " . " like :" . $key;
                        break;                        
                    case 4: 
                        // es un campo de  de casillas, debe buscar en la tabla
                        // pivote formu_casillas_escogidas las casillas que 
                        // fueron escogidas por el usuario
                        $where_ = $where_ . "(select group_concat(flv.texto) texto 
                        from formu_casillas_escogidas fce 
                            left join formu_lista_valores flv 
                                on flv.id=fce.formu_lista_valores_id 
                            where fce.formu__campo_casilla = f." .  $key . ") " . " like :" . $key;                           
                        break;
                    case 5: 
                        // radio button: 
                        // para que muestre los textos y no los id de cada texto: 
                        $where_ = $where_ . "(select texto 
                            from formu_lista_valores flv 
                            where flv.id = f." .  $key . ") " . " like :" . $key;                        
                        break;
                    case 10: 
                        // ver documentación en el anterior case 10
                        $arr_columnas_mostrar = explode('_@@@_' , $campos_detalle[$key_campos_detalle]['lista_datos']);
                        $tabla_padre = $this->obtener_datos_para_join($arr_columnas_mostrar);
                        $tabla_padre_nombre = $tabla_padre[0]->tabla_padre_nom;
                        $tabla_padre_alias = $tabla_padre[0]->tabla_padre_alias;
                        $campo_mostrar_alias_slug = $tabla_padre_alias . "." . $tabla_padre[0]->campo_hijo_nom;

                        $slug_campo_aux = $campos_detalle[$key_campos_detalle]['slug'];

                        $where_ = $where_ . "(select " . $campo_mostrar_alias_slug 
                        . " from " . $tabla_padre_nombre . " " . $tabla_padre_alias 
                        . " where " . $tabla_padre_alias . ".id=f." . $slug_campo_aux . ") " . " like :" . $key;
                        break;
                    default:
                        // no es lista de valores, ni casilla, ni radio button:
                        // Para determinar el alias del campo (f. o usu. en este caso):
                        if($key == 'user_name'){
                            $where_ = $where_ . "  usu.";
                        }else if($key == 'nombre_estado'){
                            $where_ = $where_ . "  fe.";
                        }else if($key == 'cerrado'){
                            $where_ = $where_ . "  fe.";
                        }
                        else{
                            $where_ = $where_ . "  f.";
                        }  
                        $where_ = $where_ . $key . " like :" . $key;
                        break;
                }

                // el valor para comparar en el where (lo digitado por el usuario):
                $valor_aux = '%' . $ele . '%';

                // agregar el where a los params para la instrucción mysql:
                $arr_params1[$key] = $valor_aux;                
            }
        }

        // 25sep2021: Para manejo de roles comer:
        // Si es un usuario de este rol, solo debe mostrar los registros de ese usuario:
        if(Auth::user()->hasRole(['comer'])){
            if($where_ == ""){
                $where_ = " where  usu.id = " . Auth::user()->id;
            }else{
                $where_ = $where_ . " and usu.id = " . Auth::user()->id;
            }
        }

        // 05nov2021: 
        // Para manejo de roles por campos que tenga el tipo de producto: 
        // Si el tipo de producto tiene solamente campos 'comer', 
        // sus productos solo deben aparecer para los usuarios 'comer'
        // Si tiene productos 'comer' y 'produ': Para usuarios 'comer' y 'produ'
        // Si tiene productos 'comer' y 'disen': Para usuarios 'comer' y 'disen'
        // Si tiene productos 'comer', 'produ' y 'disen': Para usuarios 'comer', 'produ' y 'disen'


        
        // 20oct2021: 
        // Para mostrar los estados de los productos: 
        $campos_estados_left_join = "";
        if($slug_tabla_formu__->tipo_producto_slug !== null){
            $campos_estados_left_join = " left join formu_contenidos_estados fce 
                on (fce.formu_tipo_producto_id=" . $tipo_producto_id . " and fce.formu__id=f.id 
                    and fce.tiempo_estado=1)
                left join formu_estados fe on fe.id=fce.formu_estado_id ";
        }        
// echo $campos_estados_select;
        // instrucción mysql principal con los estados:
        $sql1 = "select f.id, f.codigo, " . $campos_estados_select . $campos_intermedios . " , 
                usu.user_name creado_por, f.created_at 
            from " . $tabla_formu__ . " f
                left join users usu on usu.id = f.user_id " . $campos_estados_left_join;
        
        // la parte where:
        $sql1 = $sql1 . $where_;                   
        $sql1 = $sql1 . " order by " . $ordenar_campo . $ordenar_tipo; 
// dd($sql1);      
        return collect(DB::select($sql1 , $arr_params1));   
    }

    private function obtener_datos_para_join($arr_columnas_mostrar){
        // 24jun2024
        // para mostrar en el gridview de las formu__....... los 
        // textos de html_elemento_id que sean listas desde tablas:
        $primer_campo_mostrar = $arr_columnas_mostrar[0];
        // Para determinar nombre y alias de la tabla 'padre':
        $arr_params = [];
        $sql = "select ftab.nombre tabla_padre_nom,
                ftab.alias tabla_padre_alias,
                fcam.nombre campo_hijo_nom 
            from formu_campos fcam 
                left join formu_tablas ftab on ftab.id=fcam.tabla_id 
            where fcam.id = :campo_id ";
        $arr_params = [
            ':campo_id' => $primer_campo_mostrar ,
        ];
        // armar el campo para que no muestre el código grabado en
        // formu__..... sino el valor al que corresponde en la
        // tabla "padre":
        return collect(DB::select($sql , $arr_params));        
    }
}