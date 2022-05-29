<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Pedidos\FormuDetalle;
use App\Models\Pedidos\FormuTabla;
use App\Models\Pedidos\FormuCampo;
use App\Models\Pedidos\FormuListaValore;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InputSeleccion extends Component
{
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_seleccion;

    public $seleccion_cabecera;
    public $seleccion_rol_escogido;
    public $seleccion_obligatorio;
    public $seleccion_tipo_origen;
    public $seleccion_valores; 
    public $seleccion_tabla; 

    // 05oct2021 para mostrar roles
    public $arr_roles_disponibles = [];      

    // si seleccion_tipo_origen es tabla, se usarán estas propiedades: 
    public $provis_campos_disponibles; 
    public $provis_campos_disponibles_canti; 
    public $provis_campos_mostrar;

    public $arr_tablas;

    // se tuvo que agregar rules() en vez de protected $rules=[], debido a que se debe
    // hacer una regla unique para dos campos de la tabla (cabecera y tipo_producto_id)
    public function rules(){
        return [ 
            'seleccion_cabecera' =>  [
                'required', 
                'max:64',
                'unique:palabras_reservadas,palabra',  // para evitar uso de palabras reservadas mysql
                Rule::unique('formu_detalles' , 'cabecera')
                    ->where('tipo_producto_id', $this->tipo_producto_recibido_id),  // para evitar que en un mismo tipo prodcuto se usen dos cabeceras iguales
            ] ,
            // 'seleccion_rol_escogido' => 'required',
            'seleccion_obligatorio' => 'required',
            'seleccion_tipo_origen' => 'required',
            'seleccion_valores' => 'required_if:seleccion_tipo_origen,1',
            'seleccion_tabla' => 'required_if:seleccion_tipo_origen,2',
            'provis_campos_mostrar' => 'exclude_if:seleccion_tipo_origen,1|array|min:1',          
        ];
    }    

    protected $messages = [
        'seleccion_cabecera.required' => 'Debe digitar la cabecera que identificará al nuevo elemento.',
        'seleccion_cabecera.max' => 'La longitud máxima de la cabecera es: 64 caracteres.',
        'seleccion_cabecera.unique' => 'La cabecera ya esta siendo utilizada, o escribió una palabra reservada. Por favor escriba otra.',
        // 'seleccion_rol_escogido.required' => 'Debe asignar un rol.',
        'seleccion_obligatorio.required' => 'Debe escoger si el nuevo elemento se debe suministrar o no de forma obligatoria.',
        'seleccion_tipo_origen.required' => 'Debe escoger un tipo de origen.',
        'seleccion_valores.required_if' => 'Debe escribir los valores origen.',
        'seleccion_tabla.required' => 'Debe escoger una tabla.',
        'provis_campos_mostrar.array' => 'Campos mostrar debe ser un array.',
        'provis_campos_mostrar.min' => 'Debe escoger al menos un campo para mostrar.',
    ];   
    
    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_seleccion = true;
        $this->seleccion_tipo_origen = 1; // para que al abrir el modal, muestre por 
                                        // origen de datos desde lista de valores.
        $this->provis_campos_disponibles = [];
        $this->provis_campos_disponibles_canti = 0;
        $this->provis_campos_mostrar = [];

        // 05oct2021
        // Leer el array que tiene los nombres cortos y largos de los roles:
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
        // 30oct2021: No se usa a partir de que se decidio pedir los 
        // roles con radio button y no con casillas:        
        // $this->seleccion_rol_escogido['admin']= 'admin';        

        // llenado del array de dos columnas a partir de la tabla formu_tablas: 
        // $obj_formu_tablas = new FormuTabla();
        // $this->arr_tablas = $obj_formu_tablas->obtener_id_titulo();
        $this->arr_tablas = FormuTabla::select('id' , 'titulo')->where('tipo_producto_id' , '<>' , $this->tipo_producto_recibido_id)->orderBy('titulo')->get();
    }    

    public function render()
    {
        // return view('livewire.pedidos.config-formu.input-seleccion' , ['tablas_campos' => $this->arr_tablas_campos]);
        return view('livewire.pedidos.config-formu.input-seleccion' );
    }

    public function mostrar_modal_seleccion(){
        $this->reset(['seleccion_cabecera' , 'seleccion_obligatorio' ,  'seleccion_tipo_origen'  ]);
        $this->resetValidation();        
        $this->modal_visible_seleccion = true;
    }      

    public function cerrar_modal_seleccion(){
        $this->modal_visible_seleccion = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function tabla_escogida($tabla_id){
        $this->provis_campos_disponibles = [];
        $this->provis_campos_mostrar = [];
        if($tabla_id == ""){
            $this->provis_campos_disponibles_canti = 0;
        }else{
            // llenar $this->provis_campos_disponibles que tiene 2 columnas: 
            //     el id de los campos en la tabla formu_campos 
            //     el titulo de los campos en la misma tabla 
            $this->provis_campos_disponibles = FormuCampo::select('id' , 'titulo')->where('tabla_id', $tabla_id)->where('titulo_visible' , true)->get()->toArray();
            $this->provis_campos_disponibles_canti = count($this->provis_campos_disponibles);
        }
    }

    public function dobleClick_disponibles($campo_id , $campo_titulo){
        $arr_aux = ['id' => $campo_id , 'titulo' => $campo_titulo];
        array_push($this->provis_campos_mostrar , $arr_aux);
        $this->provis_campos_disponibles = $this->eliminar_fila($this->provis_campos_disponibles , 'id' , $campo_id);
    }

    public function dobleClick_mostrar($campo_id , $campo_titulo){
        $arr_aux = ['id' => $campo_id , 'titulo' => $campo_titulo];
        array_push($this->provis_campos_disponibles ,  $arr_aux);
        $this->provis_campos_mostrar = $this->eliminar_fila($this->provis_campos_mostrar , 'id' , $campo_id);
    }

    public function submit_seleccion(){
        // grabará en formu_detalles un registro tipo selección: 
        $this->validate();
        $this->modal_visible_seleccion = false;

        $tipo_producto_id = $this->tipo_producto_recibido_id;

        $obj_formu_detalle = new FormuDetalle();
        // para saber el orden en que se va: 
        $orden = $obj_formu_detalle->obtener_siguiente_orden($tipo_producto_id);
        // para armar el slug:
        $slug_cabecera = $obj_formu_detalle->obtener_slug_formu_detalles($this->seleccion_cabecera , $orden);      

        if($this->seleccion_tipo_origen == 1){
            // Es un combobox y sus datos origen son una lista de valores que serán 
            // grabados en la tabla formu_lista_valores
            $elemento_html = 3;  
            $lista_datos_origen = null;
        }else{
            $elemento_html = 10;  // 10 lista desde tabla
            // Es un combobox y sus datos origen son los campos de una 
            // tabla,  que serán ..._@@@_...               
            $lista_datos_origen = "";
            foreach($this->provis_campos_mostrar as $fila){

                $lista_datos_origen = $lista_datos_origen . $fila['id'] . "_@@@_";
            }
            $lista_datos_origen = substr($lista_datos_origen , 0 , -5);
        }

        // 05oct2021: convertir roles en ..._@@@_...... :  
        $roles_grabar = 'admin_@@@_' . $this->seleccion_rol_escogido;   

        // Crea el registro en la tabla formu_detalles 
        $nuevo = FormuDetalle::create([
            'tipo_producto_id' => $tipo_producto_id,
            'html_elemento_id' => $elemento_html,
            'cabecera' => $this->seleccion_cabecera,
            'slug' => $slug_cabecera,
            'orden' => $orden,
            'roles' => $roles_grabar,
            'obligatorio' => $this->seleccion_obligatorio,
            'lista_datos' => $lista_datos_origen,
            'user_id' => Auth::user()->id
        ]);

        // Crea el campo en la tabla formu__...... correspondiente: 
        // 07oct2021: 
        // Debido al manejo de roles: A partir de hoy se ha decidido
        // que TODOS los campos de formu__.... sean NULLABLES:        
        $tabla_rel = "formu__" . $this->tipo_producto_recibido_slug;
         
        if($elemento_html == 3){
            // el origen de datos es una lista de valores
            $tabla_nombre_join = "";
        }else{
            // el origen de datos es una tabla
            // 19sep2021: Corregido un grave error que tenia la siguiente
            // instrucción (se usaba ->first(), lo cual hacia que siempre 
            // el resultado era invariablemente la tabla 'clientes')
            $tabla_rgto = FormuTabla::find($this->seleccion_tabla);
// dd($tabla_rgto) ;           
            $tabla_nombre_join = $tabla_rgto->nombre;
        } 

        // 19jun2021 
        // Si de pronto el nuevo campo lista desde tablas que se va a agregar es sobre 
        // una tabla formu__.... que ya tiene registros grabados, hay que desactivar los
        // foreing keys de mysql antes de agregarlo:
        Schema::disableForeignKeyConstraints();

        Schema::table($tabla_rel , function (Blueprint $table) use ($slug_cabecera , $elemento_html , $tabla_nombre_join) {
            if($elemento_html == 3){
                // el origen de datos es una lista de valores:
                // 19jun2021: 
                // Se pone default cero en vez de null porque es una lista de valores
                // y cuando el campo sea obligatorio (el campo puede ser obligatorio 
                // o no, lo decide el admin), mysql pondrá automáticamente cero 
                // si este campo es
                // creado cuando la tabla formu__.... ya tenga registros
                $table->integer($slug_cabecera)->nullable()->default(0);
                // $table->integer($slug_cabecera)->nullable()->default(null);
            }else{
                // el origen de datos es una tabla
                // 06oct2021: CORRECCIÓN: Para que no muestre error php cuando 
                // el valor desde tabla opcional no sea escogido por el usuario,
                // se debe predeterminar con null en vez de cero:
                $table->unsignedBigInteger($slug_cabecera)->nullable()->default(null); 
                $table->foreign($slug_cabecera)
                    ->references('id')
                    ->on($tabla_nombre_join)
                    ->onDelete('restrict')
                    ->onUpdate('restrict');  
            }
        });  
        Schema::enableForeignKeyConstraints();                   

        // agrega en formu_campos el campo que acaba de ser creado: 
        // si el campo corresponde a lista desde tabla envia lefjoin_agregar true: 
        if($elemento_html == 10){
            $leftjoin_agregar = true;
        }else{
            $leftjoin_agregar = false;
        }
 
        $obj_formu_campo = new FormuCampo();
        $obj_formu_campo->agregar_campo($this->tipo_producto_recibido_id , $slug_cabecera , $this->seleccion_cabecera , $leftjoin_agregar , $tabla_nombre_join);

        if($elemento_html == 3){
            // el origen de datos es una lista de valores:
            // agrega en la tabla formu_lista_valores las opciones del elemento recién creado: 
            $obj_formu_lista_valores = new FormuListaValore();
            $obj_formu_lista_valores->agregar_campo($this->seleccion_valores , $tabla_rel , $slug_cabecera);
        }else{
            // el origen de datos es una tabla, no tiene que agregar en la tabla
            // formu_lista_valores
        }          
                
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));                

    } 
    
    private function eliminar_fila($arr_ , $col_comparar , $valor_eliminar){
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
}
