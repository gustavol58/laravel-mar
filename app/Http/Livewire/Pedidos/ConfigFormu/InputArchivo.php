<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Pedidos\FormuDetalle;
use App\Models\Pedidos\FormuCampo;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InputArchivo extends Component
{
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_archivo;

    public $archivo_cabecera;
    public $archivo_rol_escogido;
    public $archivo_obligatorio;
    public $archivo_tipos_escogidos;
    public $archivo_tipos_disponibles;
    public $archivo_megas;  
    
    // 05oct2021 para mostrar roles
    public $arr_roles_disponibles = [];    
    
    // se tuvo que agregar rules() en vez de protected $rules=[], debido a que se debe
    // hacer una regla unique para dos campos de la tabla (cabecera y tipo_producto_id)
    public function rules(){
        return [ 
            'archivo_cabecera' =>  [
                'required', 
                'max:64',
                'unique:palabras_reservadas,palabra',  // para evitar uso de palabras reservadas mysql
                Rule::unique('formu_detalles' , 'cabecera')
                    ->where('tipo_producto_id', $this->tipo_producto_recibido_id),  // para evitar que en un mismo tipo prodcuto se usen dos cabeceras iguales
            ] ,  
            // 'archivo_rol_escogido' => 'required',
            'archivo_obligatorio' => 'required',
            'archivo_tipos_escogidos' => 'required',          
            'archivo_megas' => 'required|numeric|min:0.1|max:8',         
        ];
    }

    protected $messages = [
        'archivo_cabecera.required' => 'Debe digitar la cabecera que identificará al nuevo elemento.',
        'archivo_cabecera.max' => 'La longitud máxima de la cabecera es: 64 caracteres.',
        'archivo_cabecera.unique' => 'La cabecera ya esta siendo utilizada, o escribió una palabra reservada. Por favor escriba otra.',
        // 'archivo_rol_escogido.required' => 'Debe asignar un rol.',
        'archivo_obligatorio.required' => 'Debe escoger si el nuevo elemento se debe suministrar o no de forma obligatoria.',
        'archivo_tipos_escogidos.required' => 'Debe escoger al menos un tipo de archivo.',
        'archivo_megas.required' => 'Debe escribir el tamaño máximo del archivo.',
        'archivo_megas.numeric' => 'El tamaño máximo del archivo debe ser numérico.',
        'archivo_megas.min' => 'El tamaño mínimo del archivo a subir es de 0.1 megas.',
        'archivo_megas.max' => 'El tamaño máximo del archivo a subir es de 8 megas.',
    ];     
    
    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_archivo = true;
        $this->archivo_tipos_escogidos = [];
        $this->archivo_tipos_disponibles = [
            [
                'id' => 'img',
                'titulo' => 'Imágenes',
            ],
            [
                'id' => 'pdf',
                'titulo' => 'Pdf',
            ],
            [
                'id' => 'zip',
                'titulo' => 'Zip',
            ],
            [
                'id' => 'ods',
                'titulo' => 'Office',
            ],
            [
                'id' => 'med',
                'titulo' => 'Multimedia',
            ],
            [
                'id' => 'txt',
                'titulo' => 'Texto',
            ],                        
        ];

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
        // $this->archivo_rol_escogido['admin']= 'admin';        
    }     

    public function render()
    {
        return view('livewire.pedidos.config-formu.input-archivo');
    }

    public function mostrar_modal_archivo(){
        $this->reset(['archivo_cabecera' , 'archivo_obligatorio' ,  'archivo_tipos' , 'archivo_megas'  ]);
        $this->resetValidation();        
        $this->modal_visible_archivo = true;
    }      

    public function cerrar_modal_archivo(){
        $this->modal_visible_archivo = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }     
    
    public function submit_archivo(){
        // grabará en formu_detalles un registro tipo subir archivo: 
        $this->validate();
        $this->modal_visible_archivo = false;

        $tipo_producto_id = $this->tipo_producto_recibido_id;
        
        $obj_formu_detalle = new FormuDetalle();
        // para saber el orden en que se va: 
        $orden = $obj_formu_detalle->obtener_siguiente_orden($tipo_producto_id);
        // para armar el slug:
        $slug = $obj_formu_detalle->obtener_slug_formu_detalles($this->archivo_cabecera , $orden);      

        $elemento_html = 11;  
        // 05oct2021: convertir roles en ..._@@@_...... :  
        $roles_grabar = 'admin_@@@_' . $this->archivo_rol_escogido;        

        // para obtener la cadena tipos de archivo  en formato ...._@@@_.... :
        // $aux_tipos = str_replace('\"' , '', $this->archivo_tipos_escogidos);
        // $aux_valores = str_replace(chr(10) , '_@@@_', $aux_valores);
        // $arr_aux_valores = explode('_@@@_' , $aux_valores);
        // $arr_valores = [];
        // foreach($arr_aux_valores as $ele){
        //     if($ele !== ""){
        //         array_push($arr_valores , $ele);
        //     }
        // }
        // $origen_casilla_radio = implode('_@@@_' , $arr_valores);
        $lista_tipos_archivos = implode('_@@@_' , $this->archivo_tipos_escogidos);

        // Crea el registro en la tabla formu_detalles 
        $nuevo = FormuDetalle::create([
            'tipo_producto_id' => $tipo_producto_id,
            'html_elemento_id' => $elemento_html,
            'cabecera' => $this->archivo_cabecera,
            'slug' => $slug,
            'orden' => $orden,
            'roles' => $roles_grabar,
            'obligatorio' => $this->archivo_obligatorio,
            'max_largo' => $this->archivo_megas,
            'lista_tipos_archivos' => $lista_tipos_archivos,
            'user_id' => Auth::user()->id
        ]);

        // Crea el campo en la tabla formu__...... correspondiente: 
        // 07oct2021: 
        // Debido al manejo de roles: A partir de hoy se ha decidido
        // que TODOS los campos de formu__.... sean NULLABLES:        
        $tabla_rel = "formu__" . $this->tipo_producto_recibido_slug;

        // 19jun2021 
        // Si de pronto el nuevo campo lista desde tablas que se va a agregar es sobre 
        // una tabla formu__.... que ya tiene registros grabados, hay que desactivar los
        // foreing keys de mysql antes de agregarlo:
        Schema::disableForeignKeyConstraints(); 

        Schema::table($tabla_rel , function (Blueprint $table) use ($slug) {
            $table->string($slug , 254)->collation('utf8_unicode_ci')->nullable()->default(null);
        }); 
        Schema::enableForeignKeyConstraints();          

        // agrega en formu_campos el campo que acaba de ser creado.
        $obj_formu_campo = new FormuCampo();
        $obj_formu_campo->agregar_campo($this->tipo_producto_recibido_id , $slug , $this->archivo_cabecera);

        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }
}
