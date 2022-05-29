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

class InputNumero extends Component
{
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug;

    public $modal_visible_numero;

    public $numero_cabecera;
    public $numero_rol_escogido;    
    public $numero_obligatorio;
    public $numero_tipo;  
    public $numero_min;  
    public $numero_max;  

    // 05oct2021 para mostrar roles
    public $arr_roles_disponibles = [];      

    // se tuvo que agregar rules() en vez de protected $rules=[], debido a que se debe
    // hacer una regla unique para dos campos de la tabla (cabecera y tipo_producto_id)
    public function rules(){
        return [ 
            'numero_cabecera' =>  [
                'required', 
                'max:64',
                'unique:palabras_reservadas,palabra',  // para evitar uso de palabras reservadas mysql
                Rule::unique('formu_detalles' , 'cabecera')
                    ->where('tipo_producto_id', $this->tipo_producto_recibido_id),  // para evitar que en un mismo tipo prodcuto se usen dos cabeceras iguales
            ] , 
            // 'numero_rol_escogido' => 'required',
            'numero_obligatorio' => 'required',
            'numero_tipo' => 'required',
            'numero_min' => ($this->numero_tipo == 1) ? 'nullable|integer' : 'nullable|numeric',
            'numero_max' => ($this->numero_tipo == 1) ? 'nullable|integer' : 'nullable|numeric',           
        ];
    }    

    protected $messages = [
        'numero_cabecera.required' => 'Debe digitar la cabecera que identificará al nuevo elemento.',
        'numero_cabecera.max' => 'La longitud máxima de la cabecera es: 64 caracteres.',
        'numero_cabecera.unique' => 'La cabecera ya esta siendo utilizada, o escribió una palabra reservada. Por favor escriba otra.',
        // 'numero_rol_escogido.required' => 'Debe asignar un rol.',
        'numero_obligatorio.required' => 'Debe escoger si es un número entero o de dos decimales.',
        'numero_min.integer' => 'El número mínimo debe ser un entero.',
        'numero_min.numeric' => 'Debe digitar un número mínimo.',
        'numero_max.integer' => 'El número máximo debe ser un entero o decimal.',
        'numero_max.numeric' => 'Debe digitar un número máximos.',
    ];       

    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_numero = true;

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
        // $this->numero_rol_escogido['admin']= 'admin';        
    }    

    public function render()
    {
        return view('livewire.pedidos.config-formu.input-numero');
    }

    public function mostrar_modal_numero(){
        $this->reset(['numero_cabecera' , 'numero_obligatorio' , 'numero_tipo', 'numero_min', 'numero_max'  ]);
        $this->resetValidation();        
        $this->modal_visible_numero = true;
    }      

    public function cerrar_modal_numero(){
        $this->modal_visible_numero = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function submit_numero(){
        // grabará en formu_detalles un registro tipo input text: 
        $this->validate();
        $this->modal_visible_numero = false;

        $tipo_producto_id = $this->tipo_producto_recibido_id;
        if($this->numero_tipo == 1){
            $elemento_html = 2;  // 1 es input número entero
        }else{
            $elemento_html = 9;  // 1 es input número decimal con 2 decimales
        }

        $obj_formu_detalle = new FormuDetalle();
        // para saber el orden en que se va: 
        $orden = $obj_formu_detalle->obtener_siguiente_orden($tipo_producto_id);
        // para armar el slug:
        $slug = $obj_formu_detalle->obtener_slug_formu_detalles($this->numero_cabecera , $orden);      
       
        $min_num = ($this->numero_min == null) ? $min_num = $this->numero_min : intval($this->numero_min);
        $max_num = ($this->numero_max == null) ? $max_num = $this->numero_max : intval($this->numero_max);

        // 05oct2021: convertir roles en ..._@@@_...... :  
        $roles_grabar = 'admin_@@@_' . $this->numero_rol_escogido;   

        $nuevo = FormuDetalle::create([
            'tipo_producto_id' => $tipo_producto_id,
            'html_elemento_id' => $elemento_html,
            'cabecera' => $this->numero_cabecera,
            'slug' => $slug,
            'orden' => $orden,
            'roles' => $roles_grabar,
            'obligatorio' => $this->numero_obligatorio,
            'min_num' => $min_num,
            'max_num' => $max_num,
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
            if($this->numero_tipo == 1){
                // número entero: 
                $table->integer($slug)->nullable()->default(null);
            }else{
                // decimal 2 decimales: 
                $table->double($slug)->nullable()->default(null);
            }
        });
        Schema::enableForeignKeyConstraints();            

        // agrega en formu_campos el campo que acaba de ser creado.
        $obj_formu_campo = new FormuCampo();
        $obj_formu_campo->agregar_campo($this->tipo_producto_recibido_id , $slug , $this->numero_cabecera);

        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));

    }        
}
