<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Pedidos\FormuDetalle;
use App\Models\Pedidos\FormuCampo;
use App\Models\Pedidos\FormuListaValore;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InputRadio extends Component
{
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_radio;

    public $radio_cabecera;
    public $radio_rol_escogido;
    public $radio_obligatorio;
    public $radio_valores;    
    
        // 05oct2021 para mostrar roles
        public $arr_roles_disponibles = [];  

    // se tuvo que agregar rules() en vez de protected $rules=[], debido a que se debe
    // hacer una regla unique para dos campos de la tabla (cabecera y tipo_producto_id)
    public function rules(){
        return [ 
            'radio_cabecera' =>  [
                'required', 
                'max:64',
                'unique:palabras_reservadas,palabra',  // para evitar uso de palabras reservadas mysql
                Rule::unique('formu_detalles' , 'cabecera')
                    ->where('tipo_producto_id', $this->tipo_producto_recibido_id),  // para evitar que en un mismo tipo prodcuto se usen dos cabeceras iguales
            ] ,
            // 'radio_rol_escogido' => 'required',
            'radio_obligatorio' => 'required',
            'radio_valores' => 'required',          
        ];
    }    

    protected $messages = [
        'radio_cabecera.required' => 'Debe digitar la cabecera que identificará al nuevo elemento.',
        'radio_cabecera.max' => 'La longitud máxima de la cabecera es: 64 caracteres.',
        'radio_cabecera.unique' => 'La cabecera ya esta siendo utilizada, o escribió una palabra reservada. Por favor escriba otra.',
        // 'radio_rol_escogido.required' => 'Debe asignar un rol.',
        'radio_obligatorio.required' => 'Debe escoger si el nuevo elemento se debe suministrar o no de forma obligatoria.',
        'radio_valores.required' => 'Debe escribir las opciones radio.',
    ];   

    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_radio = true;
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
        // $this->radio_rol_escodigo['admin']= 'admin';        
    }    

    public function render()
    {
        return view('livewire.pedidos.config-formu.input-radio');
    }

    public function mostrar_modal_radio(){
        $this->reset(['radio_cabecera' , 'radio_obligatorio' ,  'radio_valores'  ]);
        $this->resetValidation();        
        $this->modal_visible_radio = true;
    }      

    public function cerrar_modal_radio(){
        $this->modal_visible_radio = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }  
    
    public function submit_radio(){
        // grabará en formu_detalles un registro tipo radio: 
        $this->validate();
        $this->modal_visible_radio = false;

        $tipo_producto_id = $this->tipo_producto_recibido_id;
        
        $obj_formu_detalle = new FormuDetalle();
        // para saber el orden en que se va: 
        $orden = $obj_formu_detalle->obtener_siguiente_orden($tipo_producto_id);
        // para armar el slug:
        $slug_cabecera = $obj_formu_detalle->obtener_slug_formu_detalles($this->radio_cabecera , $orden);      

        $elemento_html = 5;  
        $lista_datos_origen = null;

        // 05oct2021: convertir roles en ..._@@@_...... :  
        $roles_grabar = 'admin_@@@_' . $this->radio_rol_escogido;        

        // Crea el registro en la tabla formu_detalles 
        $nuevo = FormuDetalle::create([
            'tipo_producto_id' => $tipo_producto_id,
            'html_elemento_id' => $elemento_html,
            'cabecera' => $this->radio_cabecera,
            'slug' => $slug_cabecera,
            'orden' => $orden,
            'roles' => $roles_grabar,
            'obligatorio' => $this->radio_obligatorio,
            // 'origen_casilla_radio' => $origen_casilla_radio,
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

        Schema::table($tabla_rel , function (Blueprint $table) use ($slug_cabecera , $elemento_html) {
            // no es default cero sino default cero, porque los radio button empiezan en cero:
            $table->integer($slug_cabecera)->nullable()->default(0);
        });  
        Schema::enableForeignKeyConstraints();                   

        // agrega en formu_campos el campo que acaba de ser creado.
        $obj_formu_campo = new FormuCampo();
        $obj_formu_campo->agregar_campo($this->tipo_producto_recibido_id , $slug_cabecera , $this->radio_cabecera);

        // agrega en la tabla formu_lista_valores las opciones del elemento recién creado: 
        $obj_formu_lista_valores = new FormuListaValore();
        $obj_formu_lista_valores->agregar_campo($this->radio_valores , $tabla_rel , $slug_cabecera);

        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));

    }       
}
