<?php

namespace App\Http\Livewire\Usuarios;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ModificarUsuario extends Component
{
    // ==================================================================================
    //  Propiedades para recibir parámetros y modificar lo grabado en la b.d.
    // ==================================================================================    
    public $modificar_usuario_id;

    // ==================================================================================
    //  Propiedades para modificar lo grabado en la b.d.
    // ==================================================================================
    public $nombre_completo;
    public $nombre_acceso;
    public $email;
    public $rol_id;
    public $arr_para_roles = [];

    // ==================================================================================
    // Propiedades para validación de campos del formulario
    // ==================================================================================
    public $mensaje_correcto;
    public $mensaje_error;

    // ==================================================================================
    // Propiedades para modales
    // ==================================================================================    
    public $modal_visible_cancelar = false; 
    
    // ==================================================================================
    // Rules
    // ==================================================================================    
    protected $rules = [
        'nombre_completo' => 'required|max:254',
        'nombre_acceso' => 'required|max:254',
        'email' => 'required|email',
        'rol_id' => 'required',
    ]; 

    protected $messages = [
        'nombre_completo.required' => 'El nombre completo no puede estar vacio.',
        'nombre_completo.max' => 'La longitud máxima del nombre completo es de 254 caracteres.',
        'nombre_acceso.required' => 'El nombre de acceso no puede estar vacio.',
        'nombre_acceso.max' => 'La longitud máxima del nombre de acceso es de 254 caracteres.',
        'email.required' => 'El email no puede estar vacio.',
        'email.email' => 'Debe digitar un email válido.',
        'rol_id.required' => 'Debe seleccionar un rol.',
    ];   

    // ==================================================================================
    //  Mount()
    // ==================================================================================    
    public function mount($modificar_usuario_id){
        $this->modificar_usuario_id = $modificar_usuario_id;
        // ==================================================================================
        //  Traer info del usuario desde la b.d.
        // ==================================================================================        
        $arr_params0 =[];        
        $sql0 = "select name nombre_completo,
                    user_name nombre_acceso,
                    email,
                    (select role_id from model_has_roles where model_id=usu.id) rol_id 
                from users usu
                    where id=:usuario_id ";
        $arr_params0 = [
            ':usuario_id' => $this->modificar_usuario_id
        ];                     
        $coll_modificar_usuario = collect(DB::select($sql0 , $arr_params0));   
        $coll_modificar_usuario_fila0 = $coll_modificar_usuario[0];
        $this->nombre_completo = $coll_modificar_usuario_fila0->nombre_completo;      
        $this->nombre_acceso = $coll_modificar_usuario_fila0->nombre_acceso;      
        $this->email = $coll_modificar_usuario_fila0->email;      
        $this->rol_id = $coll_modificar_usuario_fila0->rol_id;      

        // Para armar el array que será mostrado en el combo de roles:
        $arr_roles_aux = [];
        $arr_roles_largos = config('constantes.roles_nombres_largos'); 
        
        $sql2 = "SELECT id,name FROM roles WHERE 1";
        $coll_para_roles = collect(DB::select($sql2));
        foreach ($coll_para_roles as $un_rol) {
            $arr_roles_aux['id'] = $un_rol->id;
            $arr_roles_aux['rol'] = $arr_roles_largos[$un_rol->name];
            array_push($this->arr_para_roles , $arr_roles_aux);
        }        
    }

    public function render(){
        return view('livewire.usuarios.modificar-usuario');
    }

    public function submit_modificar_usuario(){
        // Llega aqui cuando se presiona Grabar modificaciones, en la ventana de
        // modificación de un usuario.

        // ==============================================================
        // Para mensajes de proceso correcto, o de errror
        // ==============================================================        
        $this->mensaje_correcto = "";
        $this->mensaje_error = ""; 

        $this->validate();

        // Actualizar en la tabla users:
        $arr_modificar_usu['name'] = $this->nombre_completo;
        $arr_modificar_usu['user_name'] = $this->nombre_acceso;
        $arr_modificar_usu['email'] = $this->email;
        $arr_modificar_usu['updated_at'] = date('Y-m-d H:i:s'); 
        DB::table('users')->where('id', $this->modificar_usuario_id)->update($arr_modificar_usu);        

        // Actualizar el rol en la tabla model_has_roles (Notar como se deben
        // escapar los \ en el model_type):
        $arr_params3 = [];
        // $aaa = "App\\\Models\\\User";
        $sql3 = "update model_has_roles  
                    set role_id=:rol_id 
                where model_type='App\\\Models\\\User'
                    and model_id=:usuario_id";

        $arr_params3 = [
            ':rol_id' => $this->rol_id,
            ':usuario_id' => $this->modificar_usuario_id,
        ];
      
        $num_rgtos3 = DB::update($sql3 , $arr_params3);        

        $this->mensaje_correcto = "Usuario modificado correctamente." ;
        return redirect(url('ver-usuarios')); 


    }

    public function mostrar_modal_btn_cancelar(){
        // Muestra el modal para confirmar salir sin grabar
        $this->modal_visible_cancelar = true;
    } 
    
    public function btn_cancelar(){
        return redirect(url('ver-usuarios')); 
    } 

}
