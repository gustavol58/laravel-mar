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

class InputEmail extends Component
{
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_email;

    public $email_cabecera;
    public $email_rol_escogido;
    public $email_obligatorio;

    // 05oct2021 para mostrar roles
    public $arr_roles_disponibles = [];      

    // se tuvo que agregar rules() en vez de protected $rules=[], debido a que se debe
    // hacer una regla unique para dos campos de la tabla (cabecera y tipo_producto_id)
    public function rules(){
        return [ 
            'email_cabecera' =>  [
                'required', 
                'max:64',
                'unique:palabras_reservadas,palabra',  // para evitar uso de palabras reservadas mysql
                Rule::unique('formu_detalles' , 'cabecera')
                    ->where('tipo_producto_id', $this->tipo_producto_recibido_id),  // para evitar que en un mismo tipo prodcuto se usen dos cabeceras iguales
            ] ,
            // 'email_rol_escogido' => 'required',           
            'email_obligatorio' => 'required',           
        ];
    }    

    protected $messages = [
        'email_cabecera.required' => 'Debe digitar la cabecera que identificará al nuevo elemento.',
        'email_cabecera.max' => 'La longitud máxima de la cabecera es: 64 caracteres.',
        'email_cabecera.unique' => 'La cabecera ya esta siendo utilizada, o escribió una palabra reservada. Por favor escriba otra.',
        // 'email_rol_escogido.required' => 'Debe asignar un rol.',
        'email_obligatorio.required' => 'Debe escoger si el nuevo elemento se debe suministrar o no de forma obligatoria.',
    ];   

    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_email = true;

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
        //     $this->email_rol_escogido['admin']= 'admin';        
    }    

    public function render()
    {
        return view('livewire.pedidos.config-formu.input-email');
    }

    public function mostrar_modal_email(){
        $this->reset(['email_cabecera' , 'email_obligatorio'  ]);
        $this->resetValidation();        
        $this->modal_visible_email = true;
    }      

    public function cerrar_modal_email(){
        $this->modal_visible_email = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }    

    public function submit_email(){
        // grabará en formu_detalles un registro tipo input email: 
        $this->validate();
        $this->modal_visible_email = false;

        $tipo_producto_id = $this->tipo_producto_recibido_id;
        $elemento_html = 6;
          
        $obj_formu_detalle = new FormuDetalle();
        // para saber el orden en que se va: 
        $orden = $obj_formu_detalle->obtener_siguiente_orden($tipo_producto_id);
        // para armar el slug:
        $slug = $obj_formu_detalle->obtener_slug_formu_detalles($this->email_cabecera , $orden);      
        // 05oct2021: convertir roles en ..._@@@_...... :  
        $roles_grabar = 'admin_@@@_' . $this->email_rol_escogido;        

        // Crea el registro en la tabla formu_detalles 
        $nuevo = FormuDetalle::create([
            'tipo_producto_id' => $tipo_producto_id,
            'html_elemento_id' => $elemento_html,
            'cabecera' => $this->email_cabecera,
            'slug' => $slug,
            'orden' => $orden,
            'roles' => $roles_grabar,
            'obligatorio' => $this->email_obligatorio,
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
            $table->string($slug , 100)->collation('utf8_unicode_ci')->nullable()->default(null);
        });  
        Schema::enableForeignKeyConstraints();          

        // agrega en formu_campos el campo que acaba de ser creado.
        $obj_formu_campo = new FormuCampo();
        $obj_formu_campo->agregar_campo($this->tipo_producto_recibido_id , $slug , $this->email_cabecera);

        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));

    }        
}
