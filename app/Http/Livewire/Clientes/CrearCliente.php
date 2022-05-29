<?php

namespace App\Http\Livewire\Clientes;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CrearCliente extends Component
{
    // ==================================================================================
    // Propiedades para recibir parámetros
    // ==================================================================================
    public $operacion;   // 'crear'  o  'modificar'
    public $modificar_cliente_id;   // 'crear'  o  'modificar'

    // ==================================================================================
    // Propiedades para validación de campos del formulario
    // ==================================================================================
    public $mensaje_correcto;
    public $mensaje_error;
    
    // ==================================================================================
    // Propiedades wire:model
    // ==================================================================================
    public $tipo_documento_id;   
    public $nit;   
    public $div_mostrar = null;
    public $razon_social;   
    public $direccion;   
    public $ciudad_id;   
    public $fijo;   
    public $celular;   
    public $contacto;   
    public $email;   
    public $condiciones_comer; 
    public $comercial_asignado_id;   
    public $user_created_id;   
    public $user_updated_id;   
    public $created_at;   
    public $updated_at;   

    // ==================================================================================
    // Propiedades para llenar selects
    // ================================================================================== 
    public $arr_para_tipo_documento_id;
    public $arr_para_ciudades;
    public $arr_para_comerciales_asignados;
  
    // ==================================================================================
    // Propiedades para modales
    // ==================================================================================    
    public $modal_visible_cancelar = false;

    // ==================================================================================
    // Rules
    // ==================================================================================    
    // Nota: Recordar que las rules para  'nit' y 'razon_social' están puestas
    // antes del validated, en el método submit_cliente(), pues dependen de la operación:
    protected $rules = [
        'tipo_documento_id' => 'required',
        'razon_social' => 'required|max:100',
        'direccion' => 'required|max:200',
        'ciudad_id' => 'required|not_in: 0',
        'fijo' => 'integer|required_without:celular|min:1000000|nullable',
        'celular' => 'integer|required_without:fijo|min:1000000000|nullable',
        'contacto' => 'required|max:80',
        'email' => 'required|max:200|email',
        'condiciones_comer' => 'integer|required|min:0|max:180',
        'comercial_asignado_id' => 'required',
    ];

    protected $messages = [
        'nit.integer' => 'Solo se pueden escribir números.',
        'nit.required' => 'Se debe digitar un  número de documento.',
        'nit.min' => 'La longitud mínima son 6 dígitos.',
        'nit.unique' => 'El nit ya está grabado para otro cliente.',
        'tipo_documento_id.required' => 'Se debe seleccionar un tipo de documento.',
        'razon_social.required' => 'Se debe digitar el nombre del cliente.',
        'razon_social.max' => 'La longitud máxima son 100 caracteres.',
        'razon_social.unique' => 'El nombre ya está asignado a otro cliente.',
        'direccion.required' => 'Se debe digitar la dirección del cliente.',
        'direccion.max' => 'La longitud máxima son 200 caracteres.',
        'ciudad_id.required' => 'Se debe escoger una ciudad.',
        'ciudad_id.not_in' => 'Se debe escoger una ciudad.',
        'fijo.integer' => 'Solo se pueden escribir números.',
        'fijo.required_without' => 'Se debe digitar el teléfono fijo o el celular.',
        'fijo.min' => 'La longitud mínima son 7 dígitos.',
        'celular.integer' => 'Solo se pueden escribir números.',
        'celular.required_without' => 'Se debe digitar el teléfono fijo o el celular.',
        'celular.min' => 'La longitud mínima son 10 dígitos.',
        'contacto.required' => 'Se debe digitar el contacto.',
        'contacto.max' => 'La longitud máxima son 80 caracteres.',        
        'email.required' => 'Se debe digitar el e-mail.',
        'email.max' => 'La longitud máxima son 200 caracteres.',        
        'email.email' => 'Debe digitar un formato e-mail correcto.', 
        'condiciones_comer.integer' => 'Solo se puede escribir un número entero.',
        'condiciones_comer.required' => 'Se deben digitar las condiciones comerciales.',
        'condiciones_comer.min' => 'Mínimo 0 dias (Contado).',               
        'condiciones_comer.max' => 'Máximo 180 dias.', 
        'comercial_asignado_id.required' => 'Se debe escoger un comercial.',
    ];   
    
    // ==================================================================================
    // Matricular eventos javascritp
    // ==================================================================================     
    protected $listeners = ['calcular_div_'];

    public function mount($operacion , $modificar_cliente_id = null){
        $this->operacion = $operacion;
        $this->modificar_cliente_id = $modificar_cliente_id;

        // ==================================================================================
        // Si la operación es 'modificar': Traer info del cliente desde la b.d.
        // ==================================================================================
        if($this->operacion == 'modificar'){
            $arr_params =[];
            $sql1 = "select * 
                from clientes 
                where id = :modificar_cliente_id ";
            $arr_params = [
                ':modificar_cliente_id' => $this->modificar_cliente_id
            ];            
            $coll_modificar_cliente = collect(DB::select($sql1, $arr_params));
            $coll_cliente = $coll_modificar_cliente[0];

            $this->tipo_documento_id = $coll_cliente->tipo_documento_id;      
            $this->nit = trim($coll_cliente->nit);   
            $this->div_mostrar = $coll_cliente->div_;
            $this->razon_social = $coll_cliente->nom_cliente;  
            $this->direccion = $coll_cliente->direccion;   
            $this->ciudad_id = $coll_cliente->ciudad_id;   
            $this->fijo = $coll_cliente->fijo;   
            $this->celular = $coll_cliente->celular;   
            $this->contacto = $coll_cliente->contacto;   
            $this->email = $coll_cliente->email;   
            $this->condiciones_comer = $coll_cliente->condiciones; 
            $this->comercial_asignado_id = $coll_cliente->comercial_id; 
            $this->user_created_id = $coll_cliente->user_created_id; 
            $this->user_updated_id = $coll_cliente->user_updated_id; 
            $this->created_at = $coll_cliente->created_at; 
            $this->updated_at = $coll_cliente->updated_at; 
        }        

    }

    public function render(){

        // Para el select de tipos de documento: 
        $sql1 = "SELECT id,nombre_tipo_doc FROM tipo_documentos order by nombre_tipo_doc";
        $this->arr_para_tipo_documento_id = collect(DB::select($sql1));

        // Para el select de ciudades:
        $sql2 = "SELECT ciu.id,
                        concat(ciu.nombre_ciudad,' - ',dep.nombre_departamento) ciudad
                    FROM ciudades ciu 
                            left join departamentos dep on dep.id=ciu.departamento_id";
        $this->arr_para_ciudades = collect(DB::select($sql2));

        // Para el select de comerciales asignados:
        $sql3 = "SELECT usu.id,
                        usu.name comercial 
                    FROM users usu 
                        left join model_has_roles modrol on modrol.model_id=usu.id 
                        left join roles rol on rol.id=modrol.role_id 
                    where rol.name in ('admin','comer') 
                        and usu.id not in (1,5) 
                    order by usu.name; ";
        $this->arr_para_comerciales_asignados = collect(DB::select($sql3));
        // 22ene2022:
        // OJO: Parece que cuando se deben enviar mas de tres variables, 
        // el compact() falla (nunca llegaron los comerciales asignados
        // al blade): 
        // return view('livewire.clientes.crear-cliente', 
        //     compact('arr_para_tipo_documento_id'),
        //     compact('arr_para_ciudades'),
        //     compact('arr_para_comerciales_asignados')
        // );

        return view('livewire.clientes.crear-cliente');
    }

    public function submit_cliente(){
        // Llega acá cuando se presiona el botón "Crear cliente" en el formulario 
        // de creación de un producto o en el botón "Grabar modificaciones del cliente" 
        // en la modificación de un producto.

        // ==============================================================
        // Para mensajes de proceso correcto, o de errror
        // ==============================================================        
        $this->mensaje_correcto = "";
        $this->mensaje_error = "";   

        // ==============================================================
        // Modificación de rules() inmediatamente antes del validated:
        // ============================================================== 
        if($this->operacion == 'crear'){
            $this->rules['nit'] = 'integer|required|min:100000|unique:clientes,nit';
            $this->rules['razon_social'] = 'required|max:100|unique:clientes,nom_cliente';
        }else{
            $this->rules['nit'] = 'integer|required|min:100000|unique:clientes,nit,' . $this->modificar_cliente_id;
            $this->rules['razon_social'] = 'required|max:100|unique:clientes,nom_cliente,' . $this->modificar_cliente_id;
        }

        $this->validate(); 

        $this->razon_social = strtoupper($this->razon_social);        
        $this->direccion = strtoupper($this->direccion);        
        $this->contacto = strtoupper($this->contacto);  

        $arr_grabar_rgto = [];
        $arr_grabar_rgto['estado'] = 2;   // 2: pendiente por aprobar
        $arr_grabar_rgto['tipo_documento_id'] = $this->tipo_documento_id;
        $arr_grabar_rgto['nit'] = $this->nit;  
        $arr_grabar_rgto['div_'] = $this->div_mostrar;
        $arr_grabar_rgto['nom_cliente'] = $this->razon_social;
        $arr_grabar_rgto['direccion'] = $this->direccion;
        $arr_grabar_rgto['ciudad_id'] = $this->ciudad_id;
        $arr_grabar_rgto['fijo'] = $this->fijo;
        $arr_grabar_rgto['celular'] = $this->celular;
        $arr_grabar_rgto['contacto'] = $this->contacto;
        $arr_grabar_rgto['email'] = $this->email;
        $arr_grabar_rgto['condiciones'] = $this->condiciones_comer; 
        $arr_grabar_rgto['comercial_id'] = $this->comercial_asignado_id;   
        $arr_grabar_rgto['user_created_id'] = $this->user_created_id;   
        $arr_grabar_rgto['user_updated_id'] = $this->user_updated_id;   
        $arr_grabar_rgto['created_at'] = $this->created_at;   
        $arr_grabar_rgto['updated_at'] = $this->updated_at;   
        
        if($this->operacion == 'crear'){
            $arr_grabar_rgto['user_created_id'] = Auth::user()->id;
            $arr_grabar_rgto['created_at'] = date('Y-m-d H:i:s');
    
            // Es recomendable usar siempre query builder, este previene SQL inyección
            // query builder:  DB::tablet(...)->insert(...);  // query builder: this prevents SQL injection.
            // raw builder:    DB::insert(...);    // raw builder: this dont prevent injection            
            $ultimo_cliente_id = DB::table('clientes')->insertGetId($arr_grabar_rgto); 
            $this->mensaje_correcto = "Cliente creado correctamente." ;  
            $this->limpiar();             
        }else{
            $arr_grabar_rgto['user_updated_id'] = Auth::user()->id;
            $arr_grabar_rgto['updated_at'] = date('Y-m-d H:i:s');

            DB::table('clientes')->where('id', $this->modificar_cliente_id)->update($arr_grabar_rgto);
            $this->mensaje_correcto = "Cliente modificado correctamente." ;
            $this->limpiar();
            return redirect(url('ver-clientes')); 
        }

        // ==============================================================
        // Reiniciar formulario:
        // ==============================================================

// echo "<br>".$this->tipo_documento_id;        
// echo "<br>".$this->nit;        
// echo "<br>".$this->div_mostrar;        
// echo "<br>".$this->razon_social;        
// echo "<br>".$this->direccion;        
// echo "<br>".$this->ciudad_id;        
// echo "<br>".$this->fijo;        
// echo "<br>".$this->celular;        
// echo "<br>".$this->contacto;        
// echo "<br>".$this->email;        
// echo "<br>".$this->condiciones_comer;  
// echo "<br>".$this->comercial_asignado_id;        
// dd('validaciones correctas');
    }

    public function btn_limpiar(){
        // llamada desde el blade: 
        $this->mensaje_correcto = "";
        $this->mensaje_error = ""; 
        $this->limpiar();        
    }

    protected function limpiar(){
        // llamada desde otras funciones de esta class:
        $this->reset('tipo_documento_id');
        $this->reset('nit');
        $this->reset('div_mostrar');
        $this->reset('razon_social');
        $this->reset('direccion');
        $this->reset('ciudad_id');
        $this->reset('fijo');
        $this->reset('celular');
        $this->reset('contacto');
        $this->reset('email');
        $this->reset('condiciones_comer');
        $this->reset('comercial_asignado_id');
    }  
    
    public function mostrar_modal_btn_cancelar(){
        // Muestra el modal en donde se le avisará al usuario 
        // que hay info sin grabar.

        // Primero se verifica si hay o no info sin grabar: 
        $campos_vacios = true;
        // 13oct2021: si es una casillas se trata de un 
        // array, por eso este if: 
        // if(is_array($value)){
        //     if(count($value) !== 0){
        //         $vacio_arr_input_campos = false;
        //     }
        // }else{
        //     if(strlen(trim($value)) !== 0){
        //         $vacio_arr_input_campos = false;
        //     }
        // }
        if(strlen(trim($this->nit)) !== 0){
            $campos_vacios = false;
        }            
        if(strlen(trim($this->tipo_documento_id)) !== 0){
            $campos_vacios = false;
        }            
        if(strlen(trim($this->razon_social)) !== 0){
            $campos_vacios = false;
        }            
        if(strlen(trim($this->direccion)) !== 0){
            $campos_vacios = false;
        }            
        if(strlen(trim($this->ciudad_id)) !== 0){
            $campos_vacios = false;
        }            
        if(strlen(trim($this->fijo)) !== 0){
            $campos_vacios = false;
        }            
        if(strlen(trim($this->celular)) !== 0){
            $campos_vacios = false;
        }            
        if(strlen(trim($this->contacto)) !== 0){
            $campos_vacios = false;
        }            
        if(strlen(trim($this->email)) !== 0){
            $campos_vacios = false;
        }            
        if(strlen(trim($this->condiciones_comer)) !== 0){
            $campos_vacios = false;
        }       
        if(strlen(trim($this->comercial_asignado_id)) !== 0){
            $campos_vacios = false;
        }              

        if ($campos_vacios) {
            // No hay info sin grabar, puede regresar:
            $this->btn_cancelar();
        }else{
            // Hay info sin grabar, se debe abrir el modal para advertir, en el 
            // modal el usuario decidirá la acción a ejecutar:
            $this->modal_visible_cancelar = true;
        }
            
    } 
    
    public function btn_cancelar(){
        $this->limpiar();
        return redirect(url('ver-clientes' ));
    }  
    
    public function calcular_div_(){
        // Llega a este método cuando el input text que pide el nit pierde el focus: 
        if($this->tipo_documento_id == 1){
            // el tipo de documento es nit, debe calcular el DIV:
            if(is_numeric($this->nit)){
                $this->div_mostrar = $this->calcular_div_nucleo($this->nit);
            }else{
                $this->div_mostrar = null;
            }
        }else{
            $this->div_mostrar = null;
        }
    }

    private function calcular_div_nucleo($nit){
        // 15ene2022
        // Calcula el DIV de un nit, según la normativa de 
        // la DIAN Orden administrativa 4 de 1989
        $arr_base = [71, 67, 59, 53, 47, 43, 41, 37, 29, 23, 19, 17, 13, 7, 3];
        $cuenta_ele = 15 - strlen($nit);
        $acu =0;
        for ($i=0; $i < strlen($nit); $i++) { 
            $acu = $acu + (substr($nit,$i,1) * $arr_base[$cuenta_ele]);
            $cuenta_ele++;
        }
        $residuo = $acu % 11;
        if($residuo == 0 || $residuo == 1){
            $div_ = $residuo;
        }else{
            $div_ = 11 - $residuo;
        }
        return $div_;
    }      
}
