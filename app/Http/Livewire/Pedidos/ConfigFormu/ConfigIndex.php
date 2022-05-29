<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

use App\Models\Pedidos\FormuTipoProducto;
use App\Models\Pedidos\FormuDetalle;
use App\Models\Pedidos\FormuTabla;
use App\Models\Pedidos\FormuCampo;
use App\Models\Pedidos\FormuListaValore;


class ConfigIndex extends Component
{
    use WithFileUploads;
    
    // Las variables que se enviarán a todos los demás componentes:
    public $tipo_producto_recibido_id;  
    public $tipo_producto_recibido_nombre;  
    public $tipo_producto_recibido_slug;  

    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;  
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre; 
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug; 
// $this->arr_tablas_campos = config('constantes.tablas_campos');
    }    

    public function render(){
        // determinar titulo del formulario de config y vista preliminar:
        $mostrar_titulo = "Configuración del formulario: " . $this->tipo_producto_recibido_nombre;

        // información del tipo de producto (formu_tipo_productos): 
            // instrucciones si no se fuera a hacer por eloquent:
                // $obj_formu_tipo_productos = new FormuTipoProducto();
                // $tipo_productos = $obj_formu_tipo_productos->ver_tipo_producto($this->tipo_producto_recibido_id);
        $tipo_productos = FormuTipoProducto::select('tipo_producto_nombre' , 'tipo_producto_slug' , 'titulo' , 'subtitulo' , 'columnas')->where('id', $this->tipo_producto_recibido_id )->get();
        $tipo_producto = $tipo_productos[0];

        // información de formu_detalles 
            // instrucciones si no se fuera a hacer por eloquent:
                // $obj_formu_detalle = new FormuDetalle();
                // $elementos_html = $obj_formu_detalle->ver_elementos_html($this->tipo_producto_recibido_id);
        $elementos_html = FormuDetalle::select('id' , 'html_elemento_id' , 'cabecera' , 
                'slug' , 'obligatorio' , 'max_largo' , 'lista_datos' ,
                DB::raw("' ' as arr_para_combobox") ,
                DB::raw("' ' as arr_para_casillas") , 
                DB::raw("' ' as arr_para_radio")  
            )->where('tipo_producto_id', $this->tipo_producto_recibido_id )->orderBy('orden')->get();
        // para llenar los combobox que estén presentes en el formulario, se van a recorrer
        // las fila de $elementos_html (que es una colección) y se procesará aquellas que 
        // correspondan a html_elemento_id iguales a 3 o 10: 

        $elementos_html->map(function($fila , $key) use($tipo_producto){
            if($fila->html_elemento_id == 3){
                // agrega a $elementos_html, el array para combobox cuyo origen 
                // es una lista de valores: 
                $fila->arr_para_combobox = FormuListaValore::select('conse' , 'texto')->where('formu__tabla', 'formu__'.$tipo_producto->tipo_producto_slug)->where('formu__campo' , $fila->slug)->where('conse' , '<>' , 0)->orderBy('conse')->get()->toArray();
            }else if($fila->html_elemento_id == 10){
                // agrega a $elementos_html, el array para combobox cuyo origen 
                // está en una tabla: 
                $fila->arr_para_combobox = $this->obtener_lista_desde_tabla($fila->lista_datos)->toArray();
            }else if($fila->html_elemento_id == 4){
                // agrega a $elementos_html, el array para casillas:
                $fila->arr_para_casillas = FormuListaValore::select('conse' , 'texto')->where('formu__tabla', 'formu__'.$tipo_producto->tipo_producto_slug)->where('formu__campo' , $fila->slug)->where('conse' , '<>' , 0)->orderBy('conse')->get()->toArray();
            }else if($fila->html_elemento_id == 5){
                // agrega a $elementos_html, el array para radio buttons
                    // $fila->arr_para_radio = explode('_@@@_' , $fila->origen_casilla_radio); 
                $fila->arr_para_radios = FormuListaValore::select('conse' , 'texto')->where('formu__tabla', 'formu__'.$tipo_producto->tipo_producto_slug)->where('formu__campo' , $fila->slug)->where('conse' , '<>' , 0)->orderBy('conse')->get()->toArray();
            }
        }); // fin de $elementos_html->map() para crear arrays para comboboxes
// echo "<pre>";        
// foreach($elementos_html as $item){
//     if($item->html_elemento_id == 10){
//         foreach($item->arr_para_combobox as $fila_combobox){
//             print_r($fila_combobox->id);
//             print_r($fila_combobox->salida);
//     echo "<br>****************************************************+<br>";
//         }
//     }
// }
// dd('fin');
// dd($elementos_html);          

        return view('livewire.pedidos.config-formu.config-index' , 
            compact('tipo_producto' , 'elementos_html' , 'mostrar_titulo')
        );
    }

    public function llamar_gral($encab_titulo, $encab_subtitulo, $encab_columnas){
        return redirect(url('generar-config-gral' , [          
            'encab_titulo' => ($encab_titulo=='')?' ':$encab_titulo,
            'encab_subtitulo' => ($encab_subtitulo=='')?' ':$encab_subtitulo,
            'encab_columnas' => $encab_columnas,
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_seccion(){
        return redirect(url('generar-seccion' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }
    
    public function llamar_texto(){
        return redirect(url('generar-texto' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_numero(){
        return redirect(url('generar-numero' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_seleccion(){
        return redirect(url('generar-seleccion' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_casilla(){
        return redirect(url('generar-casilla' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_radio(){
        return redirect(url('generar-radio' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_email(){
        return redirect(url('generar-email' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_fecha(){
        return redirect(url('generar-fecha' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_archivo(){
        return redirect(url('generar-archivo' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_multivariable(){
        return redirect(url('generar-multivariable' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_ordenar(){
        return redirect(url('generar-ordenar' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_eliminar(){
        return redirect(url('generar-eliminar' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function llamar_regresar(){
        return redirect(url('generar-formu-admin' ));
    } 
    
    public function obtener_lista_desde_tabla($lista_datos){
        // Retorna una colección que servirá para llenar el combobox de una lista
        // cuyo origen de datos sea una tabla, la colección a devolver 
        // tendrá 2 columnas y tantos registros como filas tenga la tabla, 
        // las 2 columnas son: 
        //         el id de cada fila de la tabla 
        //         los campos que se mostrarán por cada fila, separados por: - 
        // En $lista_datos llegan los id campo que deben ser mostrados,
        // por ejemplo: 1_@@@_7_@@@_15. Estos id campo corresponden a 
        // los id de la tabla formu_campos. 
        // El contenido del array que se devuelve es llenado con la información 
        // que tenga la tabla a la que pertenezcan los campos de $lista_datos, 
        // para obtenerla lo primero que hay que hacer es determinar $mysql_instrucccion 
        // a partir de las tablas formu_campos y formu_tablas. 

        $arr_campos = explode('_@@@_' , $lista_datos);
        // Para obtener nombre y alias de la tabla correspondiente: 
        $obj_formu_tabla = new FormuTabla();
        $tabla_rgto = $obj_formu_tabla->leer_nombre_alias_tabla($arr_campos[0]);
        $tabla_nombre = $tabla_rgto[0]->nombre;
        $tabla_alias = $tabla_rgto[0]->alias;

        $cad = " concat(";
        $left_joins = "";
        foreach($arr_campos as $un_campo){
            $campo_rgto = FormuCampo::find($un_campo);
            if($campo_rgto->equivalentes == ""){
                // el ifnull es la funcion mysql para que si el campo sea null, no anule a 
                // los que se están concatenando con él, sino que coloque un espacio en blanco: 
                $cad = $cad . "ifnull(" . $campo_rgto->alias ." , ' ')" . " , ' - ' , ";
            }else{
                $arr_equivalentes = explode('_@@@_' , $campo_rgto->equivalentes);
                $cad = $cad . "(case ";
                foreach($arr_equivalentes as $un_equivalente){
                    $cad = $cad . " when " . $campo_rgto->alias . " = " . $un_equivalente . " ";
                }
                // $cad = substr($cad , 0 , -2);
                // El else que se agrega es para que si el valor de la tabla leida no 
                // coincide con ningun when, no anule a los demás  campos, sino que 
                // ponta un espacio en blanco:
                $cad = $cad . " else ' ' end)" . " , ' - ' , ";
            }
            $left_joins = $left_joins . $campo_rgto->left_joins . " ";
        }  // fin del foreach() que recorre $arr_campos
        $cad = substr($cad , 0, -11);
        $cad = $cad . " ) ";
        $mysql_instruccion = "select " . $tabla_alias . ".id , " . $cad . " salida from " . $tabla_nombre . " " . $tabla_alias; 
        if(trim($left_joins) !== ""){
            $mysql_instruccion = $mysql_instruccion . " " . $left_joins;
        } 

        // para que no tenga en cuenta algun eventual id=0 que pueda haber en la tabla 'padre': 
        $mysql_instruccion = $mysql_instruccion . " where " . $tabla_alias . ".id <> 0";

// dd($mysql_instruccion);        

        // ejecuta la mysql en la base de datos: 
        return collect(DB::select($mysql_instruccion));
   
    }

    public function obtener_lista_desde_valores($tabla_formu__ , $campo_formu__){
        // Retorna una colección que servirá para llenar el combobox de una lista
        // cuyo origen de datos sean valores (tabla formu_lista_valores)
        // La colección a devolver 
        // tendrá 2 columnas y tantos registros como valores correspondan:
        // las 2 columnas son: 
        //         el conse de cada registro de formu_lista_valores
        //         el texto de cada registro de formu_lista_valores
        // Recibe 2 parámetros: 
        //      $tabla_formu__ nombre de la tabla formu__.... 
        //      $campo_formu__ el campo del cual se obtendrán los valores
        // El contenido del array que se devuelve es llenado con la información 
        // que tenga la tabla a la que pertenezcan los campos de $lista_datos, 
        // para obtenerla lo primero que hay que hacer es determinar $mysql_instrucccion 
        // a partir de las tablas formu_campos y formu_tablas. 

// dd($tabla_formu__ ." ". $campo_formu__);

        return  FormuListaValore::select('conse' , 'texto')->where('formu__tabla', $tabla_formu__)->where('formu__campo' , $campo_formu__)->orderBy('conse')->get();

        // // ejecuta la mysql en la base de datos: 
        // return collect(DB::select($mysql_instruccion));
   
    }


}
