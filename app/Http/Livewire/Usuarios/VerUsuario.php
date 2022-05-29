<?php

namespace App\Http\Livewire\Usuarios;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class VerUsuario extends Component
{
    use WithPagination;
    
    public $filas_por_pagina;
    public $ordenar_campo;
    public $ordenar_tipo; 

    // ==================================================================================
    // Propiedades para filtros
    // ==================================================================================
    public $estado_filtro;    // 21may2022
    public $nombre_completo_filtro;
    public $nombre_acceso_filtro;
    public $email_filtro;
    public $rol_completo_filtro;    
    
    public function mount(){
        $this->filas_por_pagina = 50; 
        $this->ordenar_campo = "usu.name";
        $this->ordenar_tipo = ' asc';   
        // $this->ver_inventario_modal_visible = false;        
        // $this->modificar_produccion_facturacion_modal_visible = false;        
    }    

    // ==================================================================================
    //      Renderización de la vista principal
    // ==================================================================================      
    public function render()
    {
        $arr_params1 =[];

        // 21may2022 el estado_filtro (en la tabla se llama 'state') es una columna 
        // con dos opciones 0 (Inactivo) o 1(Activo):
        if($this->estado_filtro == ''){
            $where_estado = "";
        }else{
            // si no se verifica que es numérico, entrará siempre asi se digiten cadenas
            if(is_numeric($this->estado_filtro) && ($this->estado_filtro == 0 || $this->estado_filtro == 1)){
                $where_estado = " and usu.state =" . $this->estado_filtro ;
            }else{
                $where_estado = " and usu.state = 3" ;   // para que no muestre  nada
            }
        }

        $sql1 = "SELECT usu.id,
                usu.state estado_filtro,
                usu.name nombre_completo,
        		usu.user_name nombre_acceso,
                usu.email,
                rol.name rol
            FROM users usu
                left join model_has_roles mhr on mhr.model_id=usu.id
                left join roles rol on rol.id=mhr.role_id
            WHERE usu.name like :nombre_completo
                " . $where_estado . "             
                and usu.user_name like :nombre_acceso            
                and usu.email like :email            
                and rol.name like :rol            
            ";  

        $arr_params1 = [
            ':nombre_completo' => '%' . $this->nombre_completo_filtro . '%',            
            ':nombre_acceso' => '%' . $this->nombre_acceso_filtro . '%',            
            ':email' => '%' . $this->email_filtro . '%',            
            ':rol' => '%' . $this->rol_completo_filtro . '%',            
        ];

        $sql1 = $sql1 . " order by " . $this->ordenar_campo . $this->ordenar_tipo;              
// dd($sql1);
        
        $registros = collect(DB::select($sql1 , $arr_params1)); 
// echo $this->rol_completo_filtro;
        
        $perPage = $this->filas_por_pagina;
        $collection = $registros;
        $items = $collection->forPage($this->page, $perPage);
        $paginator = new LengthAwarePaginator($items, $collection->count(), $perPage, $this->page);
// dd($paginator);
        // Se hace un recorrido a $paginator para "traducir" los roles
        // de modo que en el  blade no se tengan que hacer ifs:
        // Leer el array que tiene los nombres cortos y largos de los roles:
        $arr_roles_aux = config('constantes.roles_nombres_largos');            
        foreach($paginator as $un_usuario){
            $un_usuario->rol_completo = $arr_roles_aux[$un_usuario->rol];
        }

        return view('livewire.usuarios.ver-usuario' , [
            'registros' => $paginator,
        ]);        
    }

    // ==================================================================================
    //      Otros métodos
    // ==================================================================================    
    public function ordenar($campo){
        if($this->ordenar_campo == $campo){
            if($this->ordenar_tipo == ' asc'){
                $this->ordenar_tipo = ' desc';
            }else{
                $this->ordenar_tipo = ' asc';
            }
        }else{
            $this->ordenar_campo = $campo;  
            $this->ordenar_tipo = ' asc'; 
        }
    }    

    public function cambiar_estado_usuario($usuario_id , $nuevo_estado){
        // 22may2022:
        // Coloca en la tabla users el nuevo estado recibido, para el 
        // usuario_id recibido
        DB::table('users')
            ->where('id', $usuario_id)
            ->update([
                'state' => $nuevo_estado,                
            ]);
    }
}
