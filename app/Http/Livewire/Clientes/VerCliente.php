<?php

namespace App\Http\Livewire\Clientes;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

use App\Models\Cliente; 
use App\Models\Clientes\TipoDocumento; 

class VerCliente extends Component
{
    use WithPagination;
    
    public $filas_por_pagina;
    public $ordenar_campo;
    public $ordenar_tipo;  
    
    // Para filtros:
    public $cliente_id;
    public $estado;
    public $tipo_documento;
    public $nit;
    public $div_;
    public $nom_cliente;
    public $direccion;
    public $nombre_ciudad;
    public $fijo;
    public $celular;
    public $contacto;
    public $email;
    public $condiciones;
    public $comercial_asignado;
    public $usuario_creo;
    public $fecha_creo;
    public $usuario_modifico;
    public $fecha_modifico;

    public function mount(){
        $this->filas_por_pagina = 50; 
        $this->ordenar_campo = 'cli.id';
        $this->ordenar_tipo = ' asc';   
        $this->estado = ''; 
    }

    public function render(){

// 15ene2022: (NO BORRAR)
// Fragmento de código para ARREGLAR LOS CAMPOS NIT y DIV_ de los tipo
// documento 1-nit
// $arr_clientes = Cliente::all()->toArray();
// foreach($arr_clientes as $un_cliente){
//     if($un_cliente['tipo_documento_id'] == 1){
//         echo $un_cliente['id']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//         echo $un_cliente['nit']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

//         if(strpos($un_cliente['nit'] , ' ') !== false){
//             $solo_nit = substr($un_cliente['nit'],0,strpos($un_cliente['nit'] , ' '));
//         }else if(strpos($un_cliente['nit'] , '-') !== false){
//             $solo_nit = substr($un_cliente['nit'],0,strpos($un_cliente['nit'] , '-'));
//         }else{
//             $solo_nit = $un_cliente['nit'];
//         }
//         echo $solo_nit."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
//         $div_ =  $this->calcular_div($solo_nit);          
//         echo $div_."<br>"; 

//         $modificar_div_ = Cliente::find($un_cliente['id']);
//         $modificar_div_->div_ = $div_;
//         $modificar_div_->save();
        
//     }
//     // $div_ = $this->calcular_div($un_cliente[nit]);
// }
// dd('fin proceso');
// Fin fragmento de código para arreglar el campo NIT y DIV_ de los tipo .....

        $arr_params1 =[];

        // el estado del cliente es una columna con tres opciones 1,2,3:
        if($this->estado == ''){
            $where_estado = "";
        }else{
            $pos_nuevo = strpos('incompleto', strtolower($this->estado));
            $pos_aprobado = strpos('pendiente por aprobar', strtolower($this->estado));
            $pos_asentado = strpos('aprobado', strtolower($this->estado));

            $cad = "";
            if($pos_nuevo !== false){
                $cad = $cad . "1, ";
            }
            if($pos_aprobado !== FALSE){
                $cad = $cad . "2, ";
            }
            if($pos_asentado !== FALSE){
                $cad = $cad . "3, ";
            }

            if($cad == ""){
                $where_estado = " and cli.estado = 9 ";   // para que no muestre nada
            }else{
                $cad = trim($cad , ', ');
                $where_estado = " and cli.estado in (" . $cad . ") ";
            }
        } 
        
        // Para campos que pueden tener valores null, es necesario el siguiente if (también
        // son necesarias las dos condiciones: '' y null):
        if($this->tipo_documento !== '' && $this->tipo_documento !== null){
            $where_tipo_documento = " and tipdoc.nombre_tipo_doc like :tipo_documento ";
        }else{
            $where_tipo_documento = "";
        }    
        if($this->div_ !== '' && $this->div_ !== null){
            $where_div_ = " and cli.div_ like :div_ ";
        }else{
            $where_div_ = "";
        }
        if($this->nombre_ciudad !== '' && $this->nombre_ciudad !== null){
            $where_nombre_ciudad = " and ciu.nombre_ciudad like :nombre_ciudad ";
        }else{
            $where_nombre_ciudad = "";
        }               
        if($this->fijo !== '' && $this->fijo !== null){
            $where_fijo = " and cli.fijo like :fijo ";
        }else{
            $where_fijo = "";
        }               
        if($this->celular !== '' && $this->celular !== null){
            $where_celular = " and cli.celular like :celular ";
        }else{
            $where_celular = "";
        }               
        if($this->contacto !== '' && $this->contacto !== null){
            $where_contacto = " and cli.contacto like :contacto ";
        }else{
            $where_contacto = "";
        }               
        if($this->email !== '' && $this->email !== null){
            $where_email = " and cli.email like :email ";
        }else{
            $where_email = "";
        }               
        if($this->condiciones !== '' && $this->condiciones !== null){
            $where_condiciones = " and cli.condiciones like :condiciones ";
        }else{
            $where_condiciones = "";
        }               
        if($this->usuario_modifico !== '' && $this->usuario_modifico !== null){
            $where_usuario_modifico = " and usu3.name like :usuario_modifico ";
        }else{
            $where_usuario_modifico = "";
        }              
        if($this->fecha_modifico !== '' && $this->fecha_modifico !== null){
            $where_fecha_modifico = " and cli.updated_at like :fecha_modifico ";
        }else{
            $where_fecha_modifico = "";
        }               

        $sql1 = "select cli.id cliente_id,
                cli.estado,
                tipdoc.nombre_tipo_doc tipo_documento,
                cli.nit,
                cli.div_,
                cli.nom_cliente,
                cli.direccion,
                ciu.nombre_ciudad nombre_ciudad,
                cli.fijo,
                cli.celular,
                cli.contacto,
                cli.email,
                cli.condiciones, 
                usu.name comercial_asignado,
                usu2.name usuario_creo,
                cli.created_at fecha_creo,
                usu3.name usuario_modifico,
                cli.updated_at fecha_modifico
            from clientes cli
                left join users usu on usu.id=cli.comercial_id
                left join users usu2 on usu2.id=cli.user_created_id
                left join users usu3 on usu3.id=cli.user_updated_id
                left join tipo_documentos tipdoc on tipdoc.id=cli.tipo_documento_id
                left join ciudades ciu on ciu.id=cli.ciudad_id 
            where cli.id like :cliente_id 
                " . $where_estado . " 
                " . $where_tipo_documento . " 
                " . $where_div_ . " 
                " . $where_nombre_ciudad . " 
                " . $where_fijo . " 
                " . $where_celular . " 
                " . $where_contacto . " 
                " . $where_email . " 
                " . $where_condiciones . " 
                " . $where_usuario_modifico . " 
                " . $where_fecha_modifico . " 
                and cli.nit like :nit
                and cli.nom_cliente like :nom_cliente
                and cli.direccion like :direccion
                and usu.name like :comercial_asignado 
                and usu2.name like :usuario_creo 
                and cli.created_at like :fecha_creo 
            ";
            
        if(Auth::user()->hasRole(['comer'])){
            $sql1 = $sql1 . " and cli.comercial_id = " . Auth::user()->id;
        }                

        $sql1 = $sql1 . " order by " . $this->ordenar_campo . $this->ordenar_tipo;  

        $arr_params1 = [
            ':cliente_id' => '%' . $this->cliente_id . '%',
            ':nit' => '%' . $this->nit . '%',
            ':nom_cliente' => '%' . $this->nom_cliente . '%',
            ':direccion' => '%' . $this->direccion . '%',
            ':comercial_asignado' => '%' . $this->comercial_asignado . '%',
            ':usuario_creo' => '%' . $this->usuario_creo . '%',
            ':fecha_creo' => '%' . $this->fecha_creo . '%',
        ];   
        // Para campos que pueden tener valores null, es necesario el siguiente if (también
        // son necesarias las dos condiciones: '' y null):
        if($this->tipo_documento !== '' && $this->tipo_documento !== null){
            $arr_params1[':tipo_documento'] = '%' .$this->tipo_documento . '%';
        } 
        if($this->div_ !== '' && $this->div_ !== null){
            $arr_params1[':div_'] = '%' . $this->div_ . '%';
        } 
        if($this->nombre_ciudad !== '' && $this->nombre_ciudad !== null){
            $arr_params1[':nombre_ciudad'] = '%' .$this->nombre_ciudad . '%';
        }          
        if($this->fijo !== '' && $this->fijo !== null){
            $arr_params1[':fijo'] = '%' .$this->fijo . '%';
        }          
        if($this->celular !== '' && $this->celular !== null){
            $arr_params1[':celular'] = '%' .$this->celular . '%';
        }          
        if($this->contacto !== '' && $this->contacto !== null){
            $arr_params1[':contacto'] = '%' .$this->contacto . '%';
        }          
        if($this->email !== '' && $this->email !== null){
            $arr_params1[':email'] = '%' .$this->email . '%';
        }          
        if($this->condiciones !== '' && $this->condiciones !== null){
            $arr_params1[':condiciones'] = '%' .$this->condiciones . '%';
        }          
        if($this->usuario_modifico !== '' && $this->usuario_modifico !== null){
            $arr_params1[':usuario_modifico'] = '%' .$this->usuario_modifico . '%';
        }          
        if($this->fecha_modifico !== '' && $this->fecha_modifico !== null){
            $arr_params1[':fecha_modifico'] = '%' .$this->fecha_modifico . '%';
        }          

// echo "<pre>";
// print_r($arr_params1);
// dd($sql1);

        $registros = collect(DB::select($sql1  , $arr_params1));  
// dd($registros);
        $perPage = $this->filas_por_pagina;
        $collection = $registros;
        $items = $collection->forPage($this->page, $perPage);
        $paginator = new LengthAwarePaginator($items, $collection->count(), $perPage, $this->page);

        // Se hace un recorrido a $paginator para "traducir" los estados
        // a sus cadenas de texto correspondientes, de modo que en el 
        // blade no se tengan que hacer ifs:
        foreach($paginator as $un_cliente){
            switch ($un_cliente->estado) {
                case 1:
                    $un_cliente->estado_texto = 'Incompleto';
                    break;
                case 2:
                    $un_cliente->estado_texto = 'Pendiente por aprobar';
                    break;
                case 3:
                    $un_cliente->estado_texto = 'Aprobado';
                    break;
                default:
                    $un_cliente->estado_texto = '';
                    break;
            }   
        }

        return view('livewire.clientes.ver-cliente' , [
            'registros' => $paginator,
        ]);
    }

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

    public function aprobar_cliente($cliente_id){
        $cliente_aprobar = Cliente::find($cliente_id);
        $cliente_aprobar->estado = 3;
        $cliente_aprobar->save();
    }

    public function desaprobar_cliente($cliente_id){
        $cliente_aprobar = Cliente::find($cliente_id);
        $cliente_aprobar->estado = 2;
        $cliente_aprobar->save();
    }
    
    private function calcular_div($nit){
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
