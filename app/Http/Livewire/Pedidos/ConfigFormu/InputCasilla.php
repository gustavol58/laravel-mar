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

class InputCasilla extends Component
{
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_casilla;

    public $casilla_cabecera;
    public $casilla_rol_escogido;
    public $casilla_obligatorio;
    public $casilla_valores;     
    // 05oct2021 para mostrar roles
    public $arr_roles_disponibles = []; 

    // se tuvo que agregar rules() en vez de protected $rules=[], debido a que se debe
    // hacer una regla unique para dos campos de la tabla (cabecera y tipo_producto_id)
    public function rules(){
        return [ 
            'casilla_cabecera' =>  [
                'required', 
                'max:64',
                'unique:palabras_reservadas,palabra',  // para evitar uso de palabras reservadas mysql
                Rule::unique('formu_detalles' , 'cabecera')
                    ->where('tipo_producto_id', $this->tipo_producto_recibido_id),  // para evitar que en un mismo tipo prodcuto se usen dos cabeceras iguales
            ] , 
            // 'casilla_rol_escogido' => 'required',
            'casilla_obligatorio' => 'required',
            'casilla_valores' => 'required',          
        ];
    }

    protected $messages = [
        'casilla_cabecera.required' => 'Debe digitar la cabecera que identificará al nuevo elemento.',
        'casilla_cabecera.max' => 'La longitud máxima de la cabecera es: 64 caracteres.',
        'casilla_cabecera.unique' => 'La cabecera ya esta siendo utilizada, o escribió una palabra reservada. Por favor escriba otra.',
        // 'casilla_rol_escogido.required' => 'Debe asignar un rol.',
        'casilla_obligatorio.required' => 'Debe escoger si el nuevo elemento se debe suministrar o no de forma obligatoria.',
        'casilla_valores.required' => 'Debe escribir el nombre de al menos una casilla.',
    ];     
    
    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_casilla = true;
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
        // $this->casilla_rol_escogido['admin']= 'admin';        
    }     

    public function render()
    {
        return view('livewire.pedidos.config-formu.input-casilla');
    }

    public function mostrar_modal_casilla(){
        $this->reset(['casilla_cabecera' , 'casilla_obligatorio' ,  'casilla_valores'  ]);
        $this->resetValidation();        
        $this->modal_visible_casilla = true;
    }      

    public function cerrar_modal_casilla(){
        $this->modal_visible_casilla = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }  
    
    public function submit_casilla(){
        // grabará en formu_detalles un registro tipo casilla: 
        $this->validate();
        $this->modal_visible_casilla = false;

        $tipo_producto_id = $this->tipo_producto_recibido_id;

        $obj_formu_detalle = new FormuDetalle();
        // para saber el orden en que se va: 
        $orden = $obj_formu_detalle->obtener_siguiente_orden($tipo_producto_id);
        // para armar el slug:
        $slug_cabecera = $obj_formu_detalle->obtener_slug_formu_detalles($this->casilla_cabecera , $orden);      

        $elemento_html = 4; 

// dd($this->casilla_valores);
// 27jun2021 este campo no se usará mas, se usará la tabla formu_lista_valores
        // para obtener la cadena de valores, en formato ...._@@@_.... :
        // $aux_valores = str_replace('\"' , '', $this->casilla_valores);
        // $aux_valores = str_replace(chr(10) , '_@@@_', $aux_valores);
        // $arr_aux_valores = explode('_@@@_' , $aux_valores);
        // $arr_valores = [];
        // foreach($arr_aux_valores as $ele){
        //     if($ele !== ""){
        //         array_push($arr_valores , $ele);
        //     }
        // }
        // $origen_casilla_radio = implode('_@@@_' , $arr_valores);

        // 05oct2021: convertir roles en ..._@@@_...... :  
        $roles_grabar = 'admin_@@@_' . $this->casilla_rol_escogido;        

        // Crea el registro en la tabla formu_detalles 
        $nuevo = FormuDetalle::create([
            'tipo_producto_id' => $tipo_producto_id,
            'html_elemento_id' => $elemento_html,
            'cabecera' => $this->casilla_cabecera,
            'slug' => $slug_cabecera,
            'orden' => $orden,
            'roles' => $roles_grabar,
            'obligatorio' => $this->casilla_obligatorio,
            'user_id' => Auth::user()->id
        ]);

        // Crea el campo en la tabla formu__...... correspondiente: 
        // 07oct2021: 
        // Debido al manejo de roles: A partir de hoy se ha decidido
        // que TODOS los campos de formu__.... sean NULLABLES:        
        $tabla_rel = "formu__" . $this->tipo_producto_recibido_slug;

// 27jun2021 este campo no se usará mas, se usará la tabla formu_lista_valores
// armar la correspondencia números - texto casillas: 
// $origen_casilla_radio_comentario = "";
// foreach($arr_valores as $key => $value){
//     $origen_casilla_radio_comentario = $origen_casilla_radio_comentario . $key . ": " . $value . ", ";
// }    

        // 19jun2021 
        // Si de pronto el nuevo campo lista desde tablas que se va a agregar es sobre 
        // una tabla formu__.... que ya tiene registros grabados, hay que desactivar los
        // foreing keys de mysql antes de agregarlo:
        Schema::disableForeignKeyConstraints();        

        Schema::table($tabla_rel , function (Blueprint $table) use ($slug_cabecera) {
            // no es default cero sino default cero, porque las casillas empiezan en cero:
            $table->integer($slug_cabecera)->nullable()->default(0);                
        });  

        Schema::enableForeignKeyConstraints();                   

        // agrega en formu_campos el campo que acaba de ser creado.
        $obj_formu_campo = new FormuCampo();
        $obj_formu_campo->agregar_campo($this->tipo_producto_recibido_id , $slug_cabecera , $this->casilla_cabecera);

        // agrega en la tabla formu_lista_valores las casillas del elemento recién creado: 
        $obj_formu_lista_valores = new FormuListaValore();
        $obj_formu_lista_valores->agregar_campo($this->casilla_valores , $tabla_rel , $slug_cabecera);
        
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));

    }     
}
