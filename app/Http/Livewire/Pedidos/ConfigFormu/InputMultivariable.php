<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedidos\FormuDetallesMultivariable;
use App\Models\Pedidos\FormuDetalle;
use App\Models\Pedidos\FormuTabla;
use App\Models\Pedidos\FormuCampo;
use App\Models\Pedidos\FormuListaValore;
// use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use LivewireUI\Modal\ModalComponent;

class InputMultivariable extends Component
{
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_multivariable;

    public $multivariable_cabecera;
    // public $multivariable_rol_escogido;
    public $multivariable_filas_min; 
    public $multivariable_filas_max;

    // para mostrar los roles en cada columna (llenados desde constantes.php) 
    public $arr_roles_disponibles = [];

    // 05oct2021 para mostrar roles A NIVEL GENERAL DEL CAMPO
    // public $arr_roles_disponibles_todo_el_campo = [];     

    // para seleccionar en la lista desde tablas: 
    public $arr_tablas;  
    
    // 03sep2021: Cambio: columnas configurables: 
    public $columnas_actuales = 1;
    public $arr_columnas_configurables = [[]];

    // se tuvo que agregar rules() en vez de protected $rules=[], debido a que se debe
    // hacer una regla unique para dos campos de la tabla (cabecera y tipo_producto_id)
    public function rules(){
        return [ 
            'multivariable_cabecera' =>  [ 
                'required', 
                'max:64',
                'unique:palabras_reservadas,palabra',  // para evitar uso de palabras reservadas mysql
                Rule::unique('formu_detalles' , 'cabecera')
                    ->where('tipo_producto_id', $this->tipo_producto_recibido_id),  // para evitar que en un mismo tipo prodcuto se usen dos cabeceras iguales
            ] ,  
            'multivariable_filas_min' => 'required|numeric|min:0',         
            'multivariable_filas_max' => 'nullable|numeric|min:0',         

            // 05sep2021: array de columnas multivariables: 
            'arr_columnas_configurables.*.1' => 'required',                    
            'arr_columnas_configurables.*.2' => 'required',     
            'arr_columnas_configurables.*.3' => 'required',     
            'arr_columnas_configurables.*.4' => 'required_if:arr_columnas_configurables.*.2,1',     
            'arr_columnas_configurables.*.5' => 'required_if:arr_columnas_configurables.*.2,2',     
            'arr_columnas_configurables.*.7' => 'required_if:arr_columnas_configurables.*.2,2|array',     
        ];
    } 

    protected $messages = [
        'multivariable_cabecera.required' => 'Debe digitar la cabecera que identificará al nuevo elemento.',
        'multivariable_cabecera.max' => 'La longitud máxima de la cabecera es: 64 caracteres.',
        'multivariable_cabecera.unique' => 'La cabecera ya esta siendo utilizada, o escribió una palabra reservada. Por favor escriba otra.',
        'multivariable_filas_min.required' => 'Debe digitar el número mínimo de filas. Puede ser cero.',
        'multivariable_filas_min.numeric' => 'El número mínimo de filas debe ser numérico.',
        'multivariable_filas_min.min' => 'El mínimo de filas debe ser mayor o igual a cero.',
        'multivariable_filas_max.numeric' => 'El número máximo de filas debe ser numérico.',
        'multivariable_filas_max.numeric' => 'El máximo de filas debe ser mayor que 1.',

        // 05sep2021: array de columnas multivariables: 
        // 'arr_columnas_configurables.*.1.required' => 'No pueden haber columnas sin cabeceras.',                    
        'arr_columnas_configurables.*.1.required' => 'Error: Por favor revise que a todas las columnas se les haya digitado cabecera.',                    
        'arr_columnas_configurables.*.2.required' => 'Error: Por favor revise que a todas las columnas se les haya asignado un tipo de columna.',                    
        'arr_columnas_configurables.*.3.required' => 'Error: Por favor revise que a todas las columnas se les haya asignado un rol.',                    
        'arr_columnas_configurables.*.4.required_if' => 'Error: Por favor revise que no falte la lista de valores para las columnas que lo requieren.',                    
        'arr_columnas_configurables.*.5.required_if' => 'Error: Por favor revise que se haya escogido una tabla, para las columnas que lo requieren.',                    
        'arr_columnas_configurables.*.7.required_if' => 'Error: Por favor revise que se hayan escogido campos para mostrar, en las columnas que lo requieren.',                    
 
    ];      

    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_multivariable = true;
        
        // 21ago2021
        // Leer el array que tiene los nombres cortos y largos de los roles, y  asignarla 
        // a los array desde donde se armarán los input checkbox de las 3 columnas en el blade:
        $arr_roles_aux = config('constantes.roles_nombres_largos');
        foreach($arr_roles_aux as $key => $valor){
            if($key !== 'admin' && $key !== 'contab'){
                $arr_roles_fila = [
                    'id' => $key,
                    'titulo' => $valor,
                ];
                array_push($this->arr_roles_disponibles , $arr_roles_fila);
            }
        }  
// dd('revisar....');
        // 30oct2021: No se usa a partir de que se decidio pedir los 
        // roles con radio button y no con casillas:
        // $this->multivariable_rol_escogido['admin']= 'admin';

        // Inicializar propiedades roles y hacer que, por defecto, el rol administrador este activo:
        // $this->multivariable_col1_roles = [];
        // $this->multivariable_col1_roles[0] = 'admin';        
        // $this->multivariable_col2_roles = [];
        // $this->multivariable_col2_roles[0] = 'admin';        
        // $this->multivariable_col3_roles = [];
        // $this->multivariable_col3_roles[0] = 'admin';    
        // 03sep2021: Cambio: columnas configurables: 
        // Inicializar propiedades roles poner por defecto un vacio(''),
        // en arr_columnas_configurables (esto para validar obligatoriedad del rol):
        // $this->arr_columnas_configurables[1][3]['admin']= 'admin';
        $this->arr_columnas_configurables[1][3] = '';
        // las propiedades 'Cabecera de columna' y 'Tipo de columna' se deben
        // inicializar porque si no en las RULES siempre saldria error debido a
        // que la fila CERO siempre estará vacia.
        $this->arr_columnas_configurables[0][1]='...';
        $this->arr_columnas_configurables[0][2]=0;
        $this->arr_columnas_configurables[0][3]='...';
        $this->arr_columnas_configurables[0][4]='...';

        // para la lista valores/tabla de la columna 2:
        // para que al abrir el modal, muestre por defecto la lista de valores
        $this->seleccion_tipo_origen = 1; 
        $this->provis_campos_disponibles = [];
        $this->provis_campos_disponibles_canti = 0;
        $this->provis_campos_mostrar = [];

        // llenado del array de dos columnas a partir de la tabla formu_tablas: 
        // $obj_formu_tablas = new FormuTabla();
        // $this->arr_tablas = $obj_formu_tablas->obtener_id_titulo();
        $this->arr_tablas = FormuTabla::select('id' , 'titulo')->where('tipo_producto_id' , '<>' , $this->tipo_producto_recibido_id)->orderBy('titulo')->get();

// dd($this->arr_columnas_configurables);    

    }

    public function render()
    {
        return view('livewire.pedidos.config-formu.input-multivariable');
    }

    public function mostrar_modal_multivariable(){
        $this->reset(['multivariable_cabecera' , 'multivariable_filas_min' , 'multivariable_filas_min' ,  'seleccion_tipo_origen']);
        $this->resetValidation();        
        $this->modal_visible_multivariable = true;
    }  
    
    public function cerrar_modal_multivariable(){
        $this->modal_visible_multivariable = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }  

    public function tabla_escogida($tabla_id , $num_col){
        $this->arr_columnas_configurables[$num_col][6] = [];   // campos disponibles
        $this->arr_columnas_configurables[$num_col][7] = [];   // campos escogidos para mostrar
        if($tabla_id == ""){
            $this->arr_columnas_configurables[$num_col][8] = 0;
        }else{
            // llenar $this->provis_campos_disponibles que tiene 2 columnas: 
            //     el id de los campos en la tabla formu_campos 
            //     el titulo de los campos en la misma tabla 
            $this->arr_columnas_configurables[$num_col][6]= FormuCampo::select('id' , 'titulo')->where('tabla_id', $tabla_id)->where('titulo_visible' , true)->get()->toArray();
            $this->arr_columnas_configurables[$num_col][8] = count($this->arr_columnas_configurables[$num_col][6]);
        }        
    }

    public function dobleClick_disponibles($campo_id , $campo_titulo , $num_col){
        if(in_array($campo_id , array_column($this->arr_columnas_configurables[$num_col][7] , 'id'))){
            // por alguna intrincada razón (alpine js evento doble click), el programa 
            // llegó dos veces a este método y por eso este if se hizo para evitar que se dupliquen 
            // filas en los campos a mostrar: arr_columnas_configurables[$num_col][7]
        }else{
            // como el id campo no está ya en el array, puede procesar, es decir: agregar 
            // el campo al array para mostrar, y sacarlo del array disponibles:
            $arr_aux = ['id' => $campo_id , 'titulo' => $campo_titulo];
            array_push($this->arr_columnas_configurables[$num_col][7] , $arr_aux);
            $this->arr_columnas_configurables[$num_col][6] = $this->eliminar_fila_campos($this->arr_columnas_configurables[$num_col][6] , 'id' , $campo_id);
        }
    }

    public function dobleClick_mostrar($campo_id , $campo_titulo , $num_col){
        if(in_array($campo_id , array_column($this->arr_columnas_configurables[$num_col][6] , 'id'))){
            // por alguna intrincada razón (alpine js evento doble click), el programa 
            // llegó dos veces a este método y por eso este if se hizo para evitar que se dupliquen 
            // filas en los campos disponibles: arr_columnas_configurables[$num_col][6]
        }else{
            // como el id campo no está ya en el array, puede procesar, es decir: agregar 
            // el campo al array disponible, y sacarlo del array para mostrar:
            $arr_aux = ['id' => $campo_id , 'titulo' => $campo_titulo];
            array_push($this->arr_columnas_configurables[$num_col][6] , $arr_aux);
            $this->arr_columnas_configurables[$num_col][7] = $this->eliminar_fila_campos($this->arr_columnas_configurables[$num_col][7] , 'id' , $campo_id);
        }
    }   
    
    public function agregar_columna(){
        $this->columnas_actuales ++;
        // Poner por defecto un vacio(''),
        // en arr_columnas_configurables (esto para validar obligatoriedad del rol):
        $this->arr_columnas_configurables[$this->columnas_actuales][3]= '';
    }

    public function eliminar_columna($num_columna){
        $this->columnas_actuales --;
        // Para borrar la fila(unset) y además la posición(array_values) en el array de columnas:
        unset($this->arr_columnas_configurables[$num_columna]);
        $this->arr_columnas_configurables = array_values($this->arr_columnas_configurables);
    }

    public function submit_multivariable(){
        // 27ago2021:
        // submit para campos multivariables, es similar a los submit de los 
        // demás elementos con dos diferencias fundamentales: 
        // a. El campo obligatorio depende: Si el usuario digitó 0 filas mínimas, el 
        //    campo será opcional, en caso contrario será obligatorio.
        // b. No se insertan registros en la tabla formu_campos, esto se debe a que los
        //    campos multivariables NUNCA se usarán en los combobox de campos para 
        //    escoger valores desde una tabla (Pues los multivariables ni siquiera es 
        //    que tengan un valor, ellos tienen muchos valores)
        // c. No se crean campos en la tabla formu__......

// dd($this->arr_columnas_configurables);
        
        $this->validate();
        $this->modal_visible_multivariable = false;  

        $tipo_producto_id = $this->tipo_producto_recibido_id;
        $elemento_html = 12;  // 12 es multivariable
        
        if ($this->multivariable_filas_min == 0) {
            $obligatorio = false;
        } else {
            $obligatorio = true;
        }
        
        DB::beginTransaction();
        try {
            // 1. Insertar un registro en formu_detalles: 
            // para saber el orden en que se va: 
            $obj_formu_detalle = new FormuDetalle();
            // para saber el orden en que se va: 
            $orden = $obj_formu_detalle->obtener_siguiente_orden($tipo_producto_id);
            // para armar el slug:
            $slug = $obj_formu_detalle->obtener_slug_formu_detalles($this->multivariable_cabecera , $orden);      
            // 05oct2021: convertir roles en ..._@@@_......
            // 30oct2021: se debe recorrer $this->arr_columnas_configurables, la 
            // columna [3] contiene el rol ('comer', 'produ' o 'dise) que haya escogido el usuario
            $roles_grabar_todo_el_campo = 'admin_@@@_';            
            foreach($this->arr_columnas_configurables as $key => $fila){
                // La fila 0 del array no tiene info, por eso no se procesa: 
                if($key !== 0){
                    // convertir roles en ..._@@@_...... :  
                    $roles_grabar_todo_el_campo = $roles_grabar_todo_el_campo . $fila[3] . '_@@@_';
                }                
            }   
            $roles_grabar_todo_el_campo = substr(trim($roles_grabar_todo_el_campo), 0, -5);
// dd($roles_grabar_todo_el_campo);            

            // Crea el registro en la tabla formu_detalles 
            $nuevo_detalles = FormuDetalle::create([
                'tipo_producto_id' => $tipo_producto_id,
                'html_elemento_id' => $elemento_html,
                'cabecera' => $this->multivariable_cabecera,
                'slug' => $slug,
                'orden' => $orden,
                'roles' => $roles_grabar_todo_el_campo,
                'obligatorio' => $obligatorio,
                'min_num' => $this->multivariable_filas_min,
                'max_num' => $this->multivariable_filas_max,
                'user_id' => Auth::user()->id
            ]);   

            // 2. Insertar registro en la tabla formu_detalles_multivariable, la 
            //    cual contienen la configuración de cabeceras, roles, y orígen 
            //    de datos, que serán pedidos al comercial etc... 
            //    12sep2021: En origen de datos debe ir como primer componente 
            //    el tabla_id y luego los id de la tabla formu_campos
            //    La info llega en: arr_columnas_configurables
// dd($this->arr_columnas_configurables);                 
            foreach($this->arr_columnas_configurables as $key => $fila){
                // La fila 0 del array no tiene info, por eso no se procesa: 
                if($key !== 0){
                    // convertir roles en ..._@@@_...... :  
                    $roles_grabar = 'admin_@@@_' . $fila[3];

                    $origen_datos_grabar = null;
                    // si es una lista de valores:
                    if($fila[2] == 1){
                        // Para quitar las comillas que sobran en la lista de valores pedida en el textarea:
                        $aux_valores = str_replace('\"' , '', $fila[4]);
                        // para obtener un array partiendo de los saltos de linea del textarea:
                        $arr_aux_valores = explode(chr(10) , $aux_valores);
                        $origen_datos_grabar = implode('_@@@_' , $arr_aux_valores);                            
                    }
                    // si es una lista de tablas:
                    if($fila[2] == 2){
                        // $origen_datos_grabar = 
                        $origen_datos_grabar = implode('_@@@_' , array_column($fila[7] , 'id'));
                    }

                    $nuevo_detalles_multivariable = FormuDetallesMultivariable::create([
                        'formu_detalles_id' =>  $nuevo_detalles->id,
                        'col' => $key,
                        'cabecera' => $fila[1],
                        'roles' => $roles_grabar,
                        'origen_tipo' => $fila[2],
                        'origen_datos' => $origen_datos_grabar,
                    ]);                          

                }
            }

            DB::commit();
            return redirect(url('generar-formu-index' , [
                'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
                'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
                'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
            ]));                
        } catch (\Throwable $th) {
// dd($th);                
            // envío de email al programador:
            $destinatario = 'catchen@tavohen.com';
            $asunto = "Markka-Catch: transacción.";
            $titulo_para_email = "Catch " . date("Y-m-d h:i:s a");
            $titulo_para_usuario = "El tipo de elemento multivariable: " . $this->multivariable_cabecera . " no pudo ser agregado.<br>";
            $cuerpo_para_usuario = "Pueden haber varias causas distintas. Por favor envie la imagen de esta pantalla al programador del sistema.<br>" . date("Y-m-d h:i:s a");
            $cuerpo_para_email = "<br><br>Catch en una de las transacciones de: /markka-pruebas22/laravel/app/Http/
                Livewire/Pedidos/ConfigFormu/InputMultivariable.php:" . "<br><br><br><pre>" . $th . "</pre>";
            
            $contenido = [
                'titulo_para_email' => $titulo_para_email,
                'cuerpo_para_email' => $cuerpo_para_email,
                'titulo_para_usuario' => $titulo_para_usuario,
                'cuerpo_para_usuario' => $cuerpo_para_usuario
            ]; 
            \Mail::to($destinatario)->send(new \App\Mail\enviosCatch($asunto , $contenido));
            // mensaje en la ventana del navegador:
            session()->flash('message_titulo', $titulo_para_usuario);
            session()->flash('message_cuerpo', $cuerpo_para_usuario);
            // Regresar al gridview de tipos de producto:
            return redirect()->to('/generar-formu-admin');  
        }
    }    
    
    private function eliminar_fila_campos($arr_ , $col_comparar , $valor_eliminar){
        // recibe el array bidimensional $arr_ y borra de él las filas cuya 
        // $col_comparar sean iguales a $valor_eliminar: 
        $arr_resultado = [];
        foreach($arr_ as $fila){
            if($fila[$col_comparar] == $valor_eliminar){
                // será eliminada al no pasarse al array resultado
            }else{
                $arr_resultado[] = $fila;
            }
        }
        return $arr_resultado;
    }    



    // // ================================
// public static function closeModalOnEscape(): bool
// {
//     return false;
// }
// public static function closeModalOnEscape()
// {
//     return false;
// }

    // public function cerrar_modal_multivariable222(){
    //     // return redirect(url('generar-multivariable' , [
    //     //     'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
    //     //     'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
    //     //     'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
    //     // ]));
    //     // dd('hola');
    //     $this->modal_visible_multivariable = true;
    //     // return redirect(url('generar-formu-index' , [
    //     //     'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
    //     //     'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
    //     //     'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
    //     // ]));
    // }      
    // public $foo;

 

    // // public function mount()

    // // {

    // //     // dd('mount');

    // // }

 

    // public function hydrateFoo($value)

    // {
    //     // return false;
    //     echo "<script>alert('333hydrateFoo')</script>";

    //     // dd('hydrateFoo');


    // }

 

    // public function dehydrateFoo($value)

    // {
    //     echo "<script>alert('222dehydrateFoo')</script>";

    //     // dd('dehydrateFoo');


    // }

 

    // public function hydrate()

    // {
    //     // return false;
    //     echo "<script>alert('444hydrate')</script>";
    //     // dd('hydrate');


    // }

 

    // public function dehydrate()

    // {

    //     // dd('dehydrate');
    //     echo "<script>alert('111dehydrate')</script>";


    // }

 

    // public function updating($name, $value)

    // {
    //     // return false;
    //     echo "<script>alert('555updating')</script>";
    //     // dd('updating');


    // }

 

    // public function updated($name, $value)

    // {
    //     // return false;
    //     echo "<script>alert('666updated')</script>";
    //     // dd('updated');


    // }

 

    // public function updatingFoo($value)

    // {

    //     dd('updatingFoo');


    // }

 

    // public function updatedFoo($value)

    // {

    //     dd('updatedFoo');


    // }

 

    // public function updatingFooBar($value)

    // {

    //     dd('updatingFooBar');


    // }

 

    // public function updatedFooBar($value)

    // {

    //     dd('updatedFooBar');


    // }
    // // ================================



}
