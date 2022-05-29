<?php

namespace App\Http\Livewire\Pedidos\Nucleo;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

use App\Models\Pedidos\FormuTipoProducto;
use App\Models\Pedidos\PedidosDetalle;
use App\Models\Pedidos\PedidosInventario;

class VerPedido extends Component
{
    use WithPagination;
    
    public $filas_por_pagina;
    public $ordenar_campo;
    public $ordenar_tipo;  

    // ==================================================================================
    // Propiedades para validación de campos del formulario
    // ==================================================================================
    public $mensaje_correcto;
    public $mensaje_error;    
    
    // ==================================================================================
    // Propiedades para filtros
    // ==================================================================================
    public $pedido_conse;
    public $estado_id;
    public $estado_nombre;
    public $cliente_nombre;
    public $dir_entrega;
    public $tipo_producto_nombre;
    // El siguiente filtro no se usará hasta que no se implementen las 
    // funcionalidades ordenar-filtrar para este campo
    // public $codigo_producto;    
    public $categoria;
    public $canti;
    // El siguiente filtro no se usará hasta que no se implementen las 
    // funcionalidades ordenar-filtrar para este campo
    // public $produ_canti;
    public $precio;
    public $obs_producto;
    public $usuario_creo;
    public $fecha_creo;
    public $usuario_modifico;
    public $fecha_modifico;

    // ==================================================================================
    // Propiedades para el modal registrar producción
    // ==================================================================================     
    // Propiedades para mostrar info: 
    public $registrar_produccion_modal_visible;
    public $modal_produccion_pedido_detalle_id;
    public $modal_produccion_pedido_conse;
    public $modal_produccion_estado_nombre;
    public $modal_produccion_estado_id;
    public $modal_produccion_cliente_nombre;
    public $modal_produccion_dir_entrega;
    public $modal_produccion_tipo_producto_nombre;
    public $modal_produccion_codigo_producto;
    public $modal_produccion_pedida_canti;
    public $modal_produccion_produ_canti;
    public $modal_produccion_factu_canti;
    // Propiedades wire:model (input): 
    public $modal_produccion_input_fecha;
    public $modal_produccion_input_canti;

    // ==================================================================================
    // Propiedades para el modal registrar facturación
    // ==================================================================================     
    // Propiedades para mostrar info: 
    public $registrar_facturacion_modal_visible;
    public $modal_facturacion_pedido_detalle_id;
    public $modal_facturacion_pedido_conse;
    public $modal_facturacion_estado_nombre;
    public $modal_facturacion_estado_id;
    public $modal_facturacion_cliente_nombre;
    public $modal_facturacion_dir_entrega;
    public $modal_facturacion_tipo_producto_nombre;
    public $modal_facturacion_codigo_producto;
    public $modal_facturacion_pedida_canti;
    public $modal_facturacion_produ_canti;
    public $modal_facturacion_factu_canti;
    // Propiedades wire:model (input): 
    public $modal_facturacion_input_fecha;
    public $modal_facturacion_input_numfactu;
    public $modal_facturacion_input_canti;

    // ==================================================================================
    // Propiedades para el modal ver inventario
    // ==================================================================================     
    // Propiedades para mostrar info: 
    public $ver_inventario_modal_visible;   
    public $modal_inventario_pedido_conse;
    public $modal_inventario_estado_nombre; 
    public $modal_inventario_canti_pedida; 
    public $modal_inventario_pedido_detalle_id; 
    public $modal_inventario_total_produ; 
    public $modal_inventario_total_factu; 
    public $modal_arr_inventario_historial_detalle; 

    // ==================================================================================
    // Propiedades para el modal modificar produccion y facturación
    // ==================================================================================     
    // Propiedades para mostrar info: 
    public $modificar_produccion_facturacion_modal_visible;   
    public $modal_modificar_pedido_detalle_id;
    public $modal_modificar_pedido_conse;
    public $modal_modificar_estado_id; 
    public $modal_modificar_estado_nombre; 
    public $modal_modificar_canti_pedida;     
    // Para almacenar lo leido de la tabla pedidos_inventarios:
    public $arr_modificar_inventarios ;
    // Para almacenar la info (modificaciones) escritas por el usuario:
    public $arr_input_modificar_inventarios = [];
    // Para totalizar producción y facturación:
    public $modal_modificar_total_produ;
    public $modal_modificar_total_factu;
    // Para mostrar los errores de validación del submit____()
    public $modal_modificar_errores = "";

    // ==================================================================================
    // Propiedades para el modal anular pedido_conse
    // ==================================================================================     
    // Propiedades para mostrar info: 
    public $anular_modal_visible;
    public $modal_anular_pedido_detalle_id;
    public $modal_anular_pedido_conse;
    public $modal_anular_estado_nombre;
    public $modal_anular_estado_id;
    public $modal_anular_cliente_nombre;
    public $modal_anular_dir_entrega;
    public $modal_anular_tipo_producto_nombre;
    public $modal_anular_codigo_producto;
    public $modal_anular_pedida_canti;
    // Propiedades wire:model (input): 
    public $modal_anular_input_causa;    

    // ==================================================================================
    // Rules y messages, para los input del formulario
    // ==================================================================================    
    public $arr_rules = [];
    protected function rules(){
        return $this->arr_rules;
    } 
    public $arr_messages ;
    protected function messages(){
        return $this->arr_messages;
    }      

    // protected $rules = [
    //     'modal_facturacion_input_fecha' => 'required|date',
    //     'modal_facturacion_input_numfactu' => 'required|integer|min:1',
    //     'modal_facturacion_input_canti' => 'required|integer|min:1|lte:modal_facturacion_produ_canti',
    // ];    

    // protected $messages = [

    //     'modal_facturacion_input_canti.required' => 'Debe escribir una cantidad mayor que cero.',
    //     'modal_facturacion_input_canti.integer' => 'La cantidad facturada debe ser un número entero mayor que 0.',
    //     'modal_facturacion_input_canti.min' => 'La cantidad facturada debe ser mayor que 0.',
    //     'modal_facturacion_input_canti.lte' => 'No puede facturar más de lo producido.',
    // ];     

    public function mount(){
        $this->filas_por_pagina = 50; 
        $this->ordenar_campo = "concat(lpad(peddet.pedido_encab_id , 5 , '0'),'_',peddet.conse)";
        $this->ordenar_tipo = ' asc';   
        $this->estado_id = ''; 
        $this->categoria = ''; 
        $this->registrar_produccion_modal_visible = false;
        $this->registrar_facturacion_modal_visible = false;
        $this->ver_inventario_modal_visible = false;        
        $this->modificar_produccion_facturacion_modal_visible = false;        
    }

    // ==================================================================================
    //      Renderización de la vista principal
    // ==================================================================================    
    public function render(){

        $arr_params1 =[];

        // la categoria es una columna con dos opciones 1,2:
        if($this->categoria == ''){
            $where_categoria = "";
        }else{
            $pos_nuevo = strpos('nuevo', strtolower($this->categoria));
            $pos_reprogramacion = strpos('reprogramacion', strtolower($this->categoria));

            $cad = "";
            if($pos_nuevo !== FALSE){
                $cad = $cad . "1, ";
            }
            if($pos_reprogramacion !== FALSE){
                $cad = $cad . "2, ";
            }

            if($cad == ""){
                $where_categoria = " and peddet.categoria = 9 ";   // para que no muestre nada
            }else{
                $cad = trim($cad , ', ');
                $where_categoria = " and peddet.categoria in (" . $cad . ") ";
            }
        }  

        // Para el filtro de pedido_conse, que es la combinación de dos campos:
        if($this->pedido_conse !== '' && $this->pedido_conse !== null){
            $where_pedido_conse = " and concat(peddet.pedido_encab_id,'_',peddet.conse) like :pedido_conse ";
        }else{
            $where_pedido_conse = "";
        }         
        
        // Para campos que pueden tener valores null, es necesario el siguiente if (también
        // son necesarias las dos condiciones: '' y null):
        if($this->obs_producto !== '' && $this->obs_producto !== null){
            $where_obs_producto = " and peddet.obs_producto like :obs_producto ";
        }else{
            $where_obs_producto = "";
        }               
        if($this->usuario_modifico !== '' && $this->usuario_modifico !== null){
            $where_usuario_modifico = " and usu3.name like :usuario_modifico ";
        }else{
            $where_usuario_modifico = "";
        }              
        if($this->fecha_modifico !== '' && $this->fecha_modifico !== null){
            $where_fecha_modifico = " and ped.modificado_el like :fecha_modifico ";
        }else{
            $where_fecha_modifico = "";
        } 

        // El campo vacio codigo_producto, será llenado mas adelante una vez se
        // obtengan los registros de la base de datos:
        // 04mar2022: el campo pedenc.id solo será usado para cuando se modifique 
        // un pedido, no será mostrado en el gridview de pedidos:
        $sql1 = "SELECT pedenc.id pedido_encab_id,
                    peddet.id pedidos_detalle_id, 
                    concat(lpad(peddet.pedido_encab_id , 5 , '0'),'_',peddet.conse) pedido_conse,
                    peddet.estado_id,
                    peddet.estado_anterior_id,
                    pedest.nombre_estado estado_nombre,
                    cli.nom_cliente cliente_nombre,
                    pedenc.dir_entrega,
                    peddet.tipo_producto_id,
                    fti.tipo_producto_nombre tipo_producto_nombre,
                    fti.tipo_producto_slug tipo_producto_slug,
                    fti.prefijo tipo_producto_prefijo,
                    peddet.producto_id,
                    '' codigo_producto,
                    peddet.categoria,
                    peddet.canti,
                    (select sum(produ_movi) from pedidos_inventarios where pedidos_detalle_id=peddet.id) produ_canti,
                    (select sum(factu_movi) from pedidos_inventarios where pedidos_detalle_id=peddet.id) factu_canti,
                    peddet.precio,
                    peddet.obs_producto,
                    usu2.name usuario_creo,
                    peddet.creado_el fecha_creo,
                    usu3.name usuario_modifico,
                    peddet.modificado_el fecha_modifico        
            FROM pedidos_detalles peddet
                left join pedidos_encabs pedenc on pedenc.id=peddet.pedido_encab_id
                left join pedidos_estados pedest on pedest.id=peddet.estado_id
                left join clientes cli on cli.id=pedenc.cliente_id
                left join formu_tipo_productos fti on fti.id=peddet.tipo_producto_id
                left join users usu2 on usu2.id=peddet.creado_por_id
                left join users usu3 on usu3.id=peddet.modificado_por_id
            where cli.nom_cliente like :cliente_nombre 
                " . $where_pedido_conse . " 
                " . $where_categoria . " 
                " . $where_obs_producto . " 
                " . $where_usuario_modifico . " 
                " . $where_fecha_modifico . " 
                and pedest.nombre_estado like :estado_nombre
                and fti.tipo_producto_nombre like :tipo_producto_nombre
                and peddet.canti like :canti
                and peddet.precio like :precio
                and pedenc.dir_entrega like :dir_entrega
                and usu2.name like :usuario_creo 
                and peddet.creado_el like :fecha_creo 
            ";

        // Filtros que dependen del rol del usuario: 
        // Si es admin: Mostrará todos los pedidos.
        // Si es comer: Los pedidos que hayan sido creados por el usuario comercial 
        // que esté logueado (sin importar el estado del pedido)
        // Si es produ: 
        //  Puede ver todos los pedidos que tengan estado <> 1
        //  Puede registrar producción para los pedidos que tengan estados 2,3
        // Si es disen: 
        //  Puede ver (solo ver) todos los pedidos que tengan estado <> 1
        // Si es contab: 
        //  Puede ver todos los pedidos que tengan estado <> 1
        //  Puede registrar facturación para los pedidos que tengan estados 3,4
        if(Auth::user()->hasRole(['comer'])){
            $sql1 = $sql1 . " and peddet.creado_por_id = " . Auth::user()->id;
        }elseif(Auth::user()->hasRole(['produ'])){
            $sql1 = $sql1 . " and peddet.estado_id <> 1";
        }elseif(Auth::user()->hasRole(['disen'])){
            $sql1 = $sql1 . " and peddet.estado_id <> 1";
        }elseif(Auth::user()->hasRole(['contab'])){
            $sql1 = $sql1 . " and peddet.estado_id <> 1";
        }

        $sql1 = $sql1 . " order by " . $this->ordenar_campo . $this->ordenar_tipo;  

        $arr_params1 = [
            ':estado_nombre' => '%' . $this->estado_nombre . '%',
            ':cliente_nombre' => '%' . $this->cliente_nombre . '%',
            ':tipo_producto_nombre' => '%' . $this->tipo_producto_nombre . '%',
            ':canti' => '%' . $this->canti . '%',
            ':precio' => '%' . $this->precio . '%',
            ':dir_entrega' => '%' . $this->dir_entrega . '%',
            ':usuario_creo' => '%' . $this->usuario_creo . '%',
            ':fecha_creo' => '%' . $this->fecha_creo . '%',
        ];   
        // Para campos que pueden tener valores null, es necesario el siguiente if (también
        // son necesarias las dos condiciones: '' y null):
        if($this->obs_producto !== '' && $this->obs_producto !== null){
            $arr_params1[':obs_producto'] = '%' .$this->obs_producto . '%';
        }          
        if($this->usuario_modifico !== '' && $this->usuario_modifico !== null){
            $arr_params1[':usuario_modifico'] = '%' .$this->usuario_modifico . '%';
        }          
        if($this->fecha_modifico !== '' && $this->fecha_modifico !== null){
            $arr_params1[':fecha_modifico'] = '%' .$this->fecha_modifico . '%';
        }       
        
        // Para el filtro de pedido_conse, que es la combinación de dos campos:
        if($this->pedido_conse !== '' && $this->pedido_conse !== null){
            $arr_params1[':pedido_conse'] = '%' .$this->pedido_conse . '%';
        }        

// echo "<pre>";
// print_r($arr_params1);
// dd($sql1);

        $registros = collect(DB::select($sql1  , $arr_params1)); 
        // Para obtener CÓDIGO DE PRODUCTO: 
        // Recorrer el resultado y colocar en cada fila el código de producto:
        foreach ($registros as $key => $un_registro) {
            // obtener nombre de la tabla formu__ :
            $rgto_tipo_producto = FormuTipoProducto::find($un_registro->tipo_producto_id);
            $formu__tabla = "formu__" . $rgto_tipo_producto->tipo_producto_slug;

            // leer desde la tabla formu__ el código del producto y guardarlo en la
            // columna para luego mostrarlo en el gridview:
            $sql2 = "select codigo from " . $formu__tabla . " where id=:producto_id ";
            $arr_params2 = [
                ':producto_id' => $un_registro->producto_id,
            ];
            $registros2 = collect(DB::select($sql2  , $arr_params2)); 
            // Cualquiera de estas instrucciones funciona correctamente:
            // $un_registro->codigo_producto = $registros2[0]->codigo;
            $registros[$key]->codigo_producto = $registros2[0]->codigo;
        }
// dd($registros);
        // Una vez listo $registros, hacer la llamada al blade:
        $perPage = $this->filas_por_pagina;
        $collection = $registros;
        $items = $collection->forPage($this->page, $perPage);
        $paginator = new LengthAwarePaginator($items, $collection->count(), $perPage, $this->page);

        // Se hace un recorrido a $paginator para "traducir" las categorias
        // a sus cadenas de texto correspondientes, de modo que en el 
        // blade no se tengan que hacer ifs:
        foreach($paginator as $un_pedido){
            switch ($un_pedido->categoria) {
                case 1:
                    $un_pedido->categoria_texto = 'Nuevo';
                    break;
                case 2:
                    $un_pedido->categoria_texto = 'Reprogramacion';
                    break;
                default:
                    $un_pedido->categoria_texto = '';
                    break;
            };   
        }

        return view('livewire.pedidos.nucleo.ver-pedido' , [
            'registros' => $paginator,
        ]);
    }

    // ==================================================================================
    //      Métodos de apoyo para la vista principal
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

    // ==================================================================================
    //      Métodos para cambios de estados
    // ==================================================================================     
    public function modificar_estado_admin_aprobar_desaprobar($pedido_conse_recibido , $estado_recibido){
        // llamada si un admin dá click en un estado 'Pendiente por aprobar' o 'Aprobado'
        // Intercambia el estado del pedido entre esos dos estados:
        $arr_pedido_conse = explode('_' , $pedido_conse_recibido);
        if($estado_recibido == 1){
            $nuevo_estado = 2;
        }elseif($estado_recibido == 2){
            $nuevo_estado = 1;
        }
        DB::table('pedidos_detalles')
            ->where('pedido_encab_id', $arr_pedido_conse[0])
            ->where('conse', $arr_pedido_conse[1])
            ->update([
                'estado_id' => $nuevo_estado,                
                'modificado_por_id' => Auth::user()->id,
                'modificado_el' => date('Y-m-d H:i:s'),
            ]);   
    }

    public function modificar_estado_admin_cierre_forzado($pedido_conse_recibido , $estado_recibido){
        // llamada si un admin dá click al column action 'Cierre forzado'
        // Debe grabar el estado actual ($estado_recibido) en el campo: anterior_estado_id 
        // y actualizar el campo estado_id a 9 (Cierre forzado)
        $arr_pedido_conse = explode('_' , $pedido_conse_recibido);
        DB::table('pedidos_detalles')
            ->where('pedido_encab_id', $arr_pedido_conse[0])
            ->where('conse', $arr_pedido_conse[1])
            ->update([
                'estado_anterior_id' => $estado_recibido,                
                'estado_id' => 9,                
                'modificado_por_id' => Auth::user()->id,
                'modificado_el' => date('Y-m-d H:i:s'),
            ]);        
    }

    public function modificar_estado_admin_quitar_forzado($pedido_conse_recibido , $estado_anterior_id_recibido){
        // llamada si un admin dá click en un estado 'Cierre forzado'
        // Debe colocar en el estado actual el estado que esté grabado en el campo 
        // estado_anterior_id, y luego llevar null a este campo:
        $arr_pedido_conse = explode('_' , $pedido_conse_recibido);
        DB::table('pedidos_detalles')
            ->where('pedido_encab_id', $arr_pedido_conse[0])
            ->where('conse', $arr_pedido_conse[1])
            ->update([
                'estado_anterior_id' => null,                
                'estado_id' => $estado_anterior_id_recibido,                
                'modificado_por_id' => Auth::user()->id,
                'modificado_el' => date('Y-m-d H:i:s'),                
            ]);        
    }
    
    // ==================================================================================
    //      Métodos para modales que registran producción y facturación
    // ================================================================================== 
    public function mostrar_modal_registrar_produccion($pedidos_detalle_id , $pedido_conse , $estado_id , $estado_nombre , $cliente_nombre , $dir_entrega , $tipo_producto_nombre , $codigo_producto , $canti , $produ_canti , $factu_canti){
        $this->registrar_produccion_modal_visible = true;
        $this->modal_produccion_pedido_detalle_id = $pedidos_detalle_id;
        $this->modal_produccion_pedido_conse = $pedido_conse;
        $this->modal_produccion_estado_nombre = $estado_nombre;
        $this->modal_produccion_estado_id = $estado_id;
        $this->modal_produccion_cliente_nombre = $cliente_nombre;
        $this->modal_produccion_dir_entrega = $dir_entrega;
        $this->modal_produccion_tipo_producto_nombre = $tipo_producto_nombre;
        $this->modal_produccion_codigo_producto = $codigo_producto;
        $this->modal_produccion_pedida_canti = $canti;
        $this->modal_produccion_produ_canti = $produ_canti;
        $this->modal_produccion_factu_canti = $factu_canti;
    } 

    public function cerrar_modal_registrar_produccion(){
        $this->reset(['modal_produccion_input_fecha']);
        $this->reset(['modal_produccion_input_canti']);
        $this->resetValidation();
        $this->registrar_produccion_modal_visible = false;
    }
    
    public function mostrar_modal_registrar_facturacion($pedidos_detalle_id , $pedido_conse , $estado_id , $estado_nombre , $cliente_nombre , $dir_entrega , $tipo_producto_nombre , $codigo_producto , $canti , $produ_canti , $factu_canti){
        $this->registrar_facturacion_modal_visible = true;
        $this->modal_facturacion_pedido_detalle_id = $pedidos_detalle_id;
        $this->modal_facturacion_pedido_conse = $pedido_conse;
        $this->modal_facturacion_estado_nombre = $estado_nombre;
        $this->modal_facturacion_estado_id = $estado_id;
        $this->modal_facturacion_cliente_nombre = $cliente_nombre;
        $this->modal_facturacion_dir_entrega = $dir_entrega;
        $this->modal_facturacion_tipo_producto_nombre = $tipo_producto_nombre;
        $this->modal_facturacion_codigo_producto = $codigo_producto;
        $this->modal_facturacion_pedida_canti = $canti;
        $this->modal_facturacion_produ_canti = $produ_canti;
        $this->modal_facturacion_factu_canti = $factu_canti;
    }

    public function cerrar_modal_registrar_facturacion(){
        $this->reset(['modal_facturacion_input_fecha']);
        $this->reset(['modal_facturacion_input_numfactu']);
        $this->reset(['modal_facturacion_input_canti']);
        $this->resetValidation();
        $this->registrar_facturacion_modal_visible = false;
    }


    public function submit_grabar_produccion(){
        // Llega aqui cuando el usuario presiona GRABAR en el modal que pide 
        // el registro de producción para un pedido:

        // Como producción  y facturación "comparten" el blade que aloja sus 
        // correspondientes modales, las propidades rules - messages son llenadas
        // justo antes de llamar el $this->validate() y antes de ser llenadas se 
        // les debe borrar lo que tengan (por si vienen del modal de facturación):

        $this->reset('arr_rules'); 
        $this->reset('arr_messages');         

        // campo fecha:
        $this->arr_rules['modal_produccion_input_fecha'] = 'required|date';
        $this->arr_messages['modal_produccion_input_fecha.required'] = 'Es obligatorio suministrar la fecha de producción.';
        $this->arr_messages['modal_produccion_input_fecha.date'] = 'Debe suministrar un formato de fecha correcto.';

        // campo cantidad:
        $this->arr_rules['modal_produccion_input_canti'] = 'required|integer|min:1';
        $this->arr_messages['modal_produccion_input_canti.required'] = 'Debe escribir una cantidad mayor que cero.';
        $this->arr_messages['modal_produccion_input_canti.integer'] = 'La cantidad producida debe ser un número entero mayor que 0.';
        $this->arr_messages['modal_produccion_input_canti.min'] = 'La cantidad producida debe ser mayor que 0.';

        $this->validate(); 

        // determinar el estado_id que se debe actualizar en pedidos_detalles:
        if($this->modal_produccion_produ_canti == null){
            $this->modal_produccion_produ_canti = 0;
        }
        if($this->modal_produccion_produ_canti + $this->modal_produccion_input_canti >= $this->modal_produccion_pedida_canti){
            // El usuario digitó producción total:
            // Se puede digitar cualquier cantidad en producción, pero si la cantidad 
            // producida a hoy más la cantidad digitada es mayor que la cantidad 
            // pedida, el estado pasará a 'Fin producción':
            if($this->modal_produccion_estado_id == 2
                    || $this->modal_produccion_estado_id == 3){
                $estado_nuevo = 4;
            }
        }else{
            // El usuario digitó producción parcial:
            if($this->modal_produccion_estado_id == 2){
                $estado_nuevo = 3;
            }else{
                $estado_nuevo = $this->modal_produccion_estado_id;
            }
        }
        DB::beginTransaction();
        try { 
            // 1) actualizar el estado en pedidos_detalles: 
            $rgto_modificar = PedidosDetalle::find($this->modal_produccion_pedido_detalle_id);
            $rgto_modificar->estado_id = $estado_nuevo;
            $rgto_modificar->modificado_por_id = Auth::user()->id;
            $rgto_modificar->modificado_el = date('Y-m-d H:i:s'); 
            $rgto_modificar->save();

            // 2) insertar registro en pedidos_inventarios: 
            $arr_grabar_inventario = [];
            $arr_grabar_inventario['pedidos_detalle_id'] = $this->modal_produccion_pedido_detalle_id;
            $arr_grabar_inventario['fec_movi'] = $this->modal_produccion_input_fecha;
            $arr_grabar_inventario['produ_movi'] = $this->modal_produccion_input_canti;
            $arr_grabar_inventario['factu_movi'] = 0;
            $arr_grabar_inventario['creado_por_id'] = Auth::user()->id;
            $arr_grabar_inventario['creado_el'] = date('Y-m-d H:i:s');
            DB::table('pedidos_inventarios')->insert($arr_grabar_inventario);             

            DB::commit();
            $this->mensaje_correcto = "Producción registrada correctamente." ;
            $this->cerrar_modal_registrar_produccion();
        }catch (\Throwable $th) {
            // devolver las transacciones:
            DB::rollback();   
            $this->mensaje_error = "Error b.d. " . $th->getMessage() ;  
        }    

    }

    public function submit_grabar_facturacion(){
        // Llega aqui cuando el usuario presiona GRABAR en el modal que pide 
        // el registro de facturación para un pedido:

        // Como producción  y facturación "comparten" el blade que aloja sus 
        // correspondientes modales, las propidades rules - messages son llenadas
        // justo antes de llamar el $this->validate() se
        // les debe borrar lo que tengan (por si vienen del modal de facturación):

        $this->reset('arr_rules'); 
        $this->reset('arr_messages');         

        // campo fecha:
        $this->arr_rules['modal_facturacion_input_fecha'] = 'required|date';
        $this->arr_messages['modal_facturacion_input_fecha.required'] = 'Es obligatorio suministrar la fecha de facturación.';
        $this->arr_messages['modal_facturacion_input_fecha.date'] = 'Debe suministrar un formato de fecha correcto.';

        // campo número factura:
        $this->arr_rules['modal_facturacion_input_numfactu'] = 'required|integer|min:1';
        $this->arr_messages['modal_facturacion_input_numfactu.required'] = 'Debe escribir el número de factura.';
        $this->arr_messages['modal_facturacion_input_numfactu.integer'] = 'El número de factura debe ser un número entero mayor que 0.';
        $this->arr_messages['modal_facturacion_input_numfactu.min'] = 'El número de factura debe ser mayor que 0.';

        // campo cantidad:
        // $this->arr_rules['modal_facturacion_input_canti'] = 'required|integer|min:1|lte:(modal_facturacion_produ_canti-modal_facturacion_factu_canti)';
        $canti_validar = $this->modal_facturacion_produ_canti - $this->modal_facturacion_factu_canti;
        $this->arr_rules['modal_facturacion_input_canti'] = 'required|integer|min:1|lte:' . $canti_validar;
        $this->arr_messages['modal_facturacion_input_canti.required'] = 'Debe escribir una cantidad mayor que cero.';
        $this->arr_messages['modal_facturacion_input_canti.integer'] = 'La cantidad facturada debe ser un número entero mayor que 0.';
        $this->arr_messages['modal_facturacion_input_canti.min'] = 'La cantidad facturada debe ser mayor que 0.';
        $this->arr_messages['modal_facturacion_input_canti.lte'] = 'No puede facturar más de lo producido.';

        $this->validate();
        // determinar el estado_id que se debe actualizar en pedidos_detalles:
        if($this->modal_facturacion_input_canti == ( $this->modal_facturacion_produ_canti  - $this->modal_facturacion_factu_canti )){
            // El usuario digitó todas las unidades que faltaban por facturar:
            if($this->modal_facturacion_estado_id == 4){
                $estado_nuevo = 7;
            }else{
                $estado_nuevo = $this->modal_facturacion_estado_id;
            }
        }else{
            // el usuario digitó una facturación parcial:
            $estado_nuevo = $this->modal_facturacion_estado_id;
        }
// echo "<br>estado nuevo: ".$estado_nuevo ;
// dd('revisar....');        

        DB::beginTransaction();
        try { 
            // 1) actualizar el estado en pedidos_detalles: 
            $rgto_modificar = PedidosDetalle::find($this->modal_facturacion_pedido_detalle_id);
            $rgto_modificar->estado_id = $estado_nuevo;
            $rgto_modificar->modificado_por_id = Auth::user()->id;
            $rgto_modificar->modificado_el = date('Y-m-d H:i:s');             
            $rgto_modificar->save();

            // 2) insertar registro en pedidos_inventarios: 
            $arr_grabar_inventario = [];
            $arr_grabar_inventario['pedidos_detalle_id'] = $this->modal_facturacion_pedido_detalle_id;
            $arr_grabar_inventario['fec_movi'] = $this->modal_facturacion_input_fecha;
            $arr_grabar_inventario['produ_movi'] = 0;
            $arr_grabar_inventario['factu_movi'] = $this->modal_facturacion_input_canti;
            $arr_grabar_inventario['factu_num'] = $this->modal_facturacion_input_numfactu;
            $arr_grabar_inventario['creado_por_id'] = Auth::user()->id;
            $arr_grabar_inventario['creado_el'] = date('Y-m-d H:i:s');
            DB::table('pedidos_inventarios')->insert($arr_grabar_inventario);             

            DB::commit();
            $this->mensaje_correcto = "Facturación registrada correctamente." ;
            $this->cerrar_modal_registrar_facturacion();
        }catch (\Throwable $th) {
            // devolver las transacciones:
            DB::rollback();   
            $this->mensaje_error = "Error b.d. " . $th->getMessage() ;  
        }    
    }

    // ==================================================================================
    //      Método para el modal Ver inventario
    // ================================================================================== 
    public function mostrar_modal_ver_inventario($pedido_conse , $estado_nombre ,  $canti_pedida , $pedidos_detalle_id , $produ_canti , $factu_canti){
        // mostrar el modal:
        $this->ver_inventario_modal_visible = true;
        // recibir info que será mostrada en el modal:
        $this->modal_inventario_pedido_conse = $pedido_conse;
        $this->modal_inventario_estado_nombre = $estado_nombre;
        $this->modal_inventario_canti_pedida = $canti_pedida;
        $this->modal_inventario_pedido_detalle_id = $pedidos_detalle_id;
        $this->modal_inventario_total_produ = $produ_canti;
        $this->modal_inventario_total_factu = $factu_canti;
        
        // leer el historial desde la base de datos:
        $sql3 = "SELECT pin.fec_movi,
                (case 
                    when pin.produ_movi=0 then 0
                    else pin.produ_movi
                end) produ,
                (case 
                    when pin.factu_movi=0 then 0
                    else pin.factu_movi
                end) factu,
                (case 
                    when pin.factu_num is null then ''
                    else pin.factu_num
                end) factu_num,
                usu.name creado_por,
                pin.creado_el,
                usu2.name modificado_por,
                pin.modificado_el
            FROM pedidos_inventarios pin
                left join users usu on usu.id=pin.creado_por_id
                left join users usu2 on usu2.id=pin.modificado_por_id
            where pin.pedidos_detalle_id=:pedidos_detalle_id
            order by pin.creado_el;";
        $arr_params3 = [
            ':pedidos_detalle_id' => $this->modal_inventario_pedido_detalle_id,
        ];

        // 27feb2022:
        // Revisar VerFormu.php (modales para campos multivariable), donde se puede ver que 
        // llenar la propiedad que será mostrada en el blade no es tan sencillo como ésta 
        // única instrucción que se tuvo que comentariar:
        // $this->modal_inventario_historial_detalle = collect(DB::select($sql3  , $arr_params3))->toArray(); 
        // sino que hay que hacer todo esto:
        $obj_inventario_historial_detalle = collect(DB::select($sql3  , $arr_params3)); 

        if($obj_inventario_historial_detalle->count() == 0){
            $this->modal_arr_inventario_historial_detalle = [];
        }else{
            // Convertir el objeto en el array no asociativo que será mostrado en el modal: 
            $this->modal_arr_inventario_historial_detalle = [];    
            foreach ($obj_inventario_historial_detalle as $fila) {
                $arr_aux_ = [];
                $arr_aux_ = [
                    0 => $fila->fec_movi, 
                    1 => $fila->produ, 
                    2 => $fila->factu, 
                    3 => $fila->factu_num, 
                    4 => $fila->creado_por, 
                    5 => $fila->creado_el, 
                    6 => $fila->modificado_por, 
                    7 => $fila->modificado_el, 
                ];
                array_push($this->modal_arr_inventario_historial_detalle , $arr_aux_);
            }
        }
    } 

    // ==================================================================================
    //      Métodos para el modal MODIFICAR producción y facturación
    // ==================================================================================     
    public function mostrar_modal_modificar_produ_factu($pedidos_detalle_id , $pedido_conse , $estado_id , $estado_nombre , $canti_pedida){
        // 23mar2022
        // Llamado cuando el usuario da click en uno de los íconos que están junto a las
        // cantidades producidas y facturadas.
        // Recibe:
        //       el id correspondiente al pedido_conse al cual se le dió click, con este dato
        //          se leerá la b.d. para obtener todos los registros de producción y facturación
        //       Los demás datos son para mostrar en el encabezado del modal: pedido_conse,
        //          estado y cantidad pedida
        // Debe leer la información de producción y facturación del pedido escogido y
        // hacer visible el modal que permite modificar producción y facturación.

        // obtener el detalle de producción y facturación:
        $sql4 = "SELECT id,
                    fec_movi,
                    produ_movi,
                    factu_movi,
                    factu_num 
                FROM `pedidos_inventarios` 
                WHERE pedidos_detalle_id=:pedidos_detalle_id";
        $arr_params4 = [
            ':pedidos_detalle_id' => $pedidos_detalle_id,
        ];
        $this->arr_modificar_inventarios = collect(DB::select($sql4  , $arr_params4)); 
        $this->arr_input_modificar_inventarios = $this->arr_modificar_inventarios;
        $this->totalizar_produ_factu();

// echo "<pre>";        
// print_r($this->arr_modificar_inventarios);         
// echo "total produ: ".$total_produ."<br>";     
// echo "total factu: ".$total_factu."<br>";     
// dd('revisar...');        

        // obtener los totales de producción y facturación: comentariado 06abr2022:
        // $sql5 = "SELECT sum(produ_movi) total_produ,
        //             sum(factu_movi) total_factu
        //         FROM `pedidos_inventarios` 
        //         WHERE pedidos_detalle_id=:pedidos_detalle_id2";
        // $arr_params5 = [
        //     ':pedidos_detalle_id2' => $pedidos_detalle_id,
        // ];
        // $registros5 = collect(DB::select($sql5  , $arr_params5)); 
        // $total_produ = $registros5[0]->total_produ;
        // $total_factu = $registros5[0]->total_factu;

        // 30mar2022
        // hacer visible el modal para modificar:
        $this->modificar_produccion_facturacion_modal_visible = true;            
        $this->modal_modificar_pedido_detalle_id = $pedidos_detalle_id;            
        $this->modal_modificar_pedido_conse = $pedido_conse;            
        $this->modal_modificar_estado_id = $estado_id;            
        $this->modal_modificar_estado_nombre = $estado_nombre;            
        $this->modal_modificar_canti_pedida = $canti_pedida;            
    }

    public function cerrar_modal_modificar_produccion_facturacion(){
        // $this->reset(['modal_produccion_input_fecha']);
        // $this->reset(['modal_produccion_input_canti']);
        // $this->resetValidation();
        $this->modificar_produccion_facturacion_modal_visible = false;
        $this->modal_modificar_errores = "";
    } 

    public function totalizar_produ_factu(){
// dd("en total.......")        ;
        $this->modal_modificar_total_produ = 0;
        $this->modal_modificar_total_factu = 0;
        foreach ($this->arr_input_modificar_inventarios as $key => $arr_contenido){
            if(gettype($arr_contenido) == "object"){
                $total_produ_aux = $arr_contenido->produ_movi;
                $total_factu_aux = $arr_contenido->factu_movi;
            }elseif(gettype($arr_contenido) == "array"){
                $total_produ_aux = $arr_contenido['produ_movi'];
                $total_factu_aux = $arr_contenido['factu_movi'];
            }
            // validar que produ-factu sean números, si no lo son simplemente no acumula en
            // los totales (posteriormente se le mostrará el mensaje al usuario):
            if(is_numeric($total_produ_aux)){
                $this->modal_modificar_total_produ = $this->modal_modificar_total_produ + $total_produ_aux;    
            }
            if(is_numeric($total_factu_aux)){
                $this->modal_modificar_total_factu = $this->modal_modificar_total_factu + $total_factu_aux; 
            }
        }
    }
    
    public function submit_actualizar_produccion_facturacion(){
        // 07abr2022:
        // Valida la info digitada por el usuario en el modal de
        // modificación, y si está correcta graba los cambios tanto 
        // actualizando registros en pedidos_inventarios como 
        // actualizando el estado del pedido en pedidos_detalles

// echo "pedido conse: ".$this->modal_modificar_pedido_conse;
// echo "estado id: ".$this->modal_modificar_estado_id; 
// echo "estado nombre: ".$this->modal_modificar_estado_nombre; 
// echo "canti pedida: ".$this->modal_modificar_canti_pedida; 
// echo "<pre>arr_modi... y arr_input_modi...";
// print_r($this->arr_modificar_inventarios);
// print_r($this->arr_input_modificar_inventarios);
// dd('revisar...');

        // 1) Validación de lo digitado por el usuario:
        $arr_errores = [];
        $this->modal_modificar_errores = "";
        // Recorrer las filas digitadas por el usuario:
        foreach ($this->arr_input_modificar_inventarios as $key => $un_arr) {
            // validar fecha:
            $resul_fecha = $this->validar_fecha($un_arr['fec_movi']);
            if($resul_fecha != "bien"){
                array_push($arr_errores , [
                    'id' => $un_arr['id'],
                    'campo' => 'fecha',
                    'mensaje_error' => $resul_fecha,
                ]);
            }

            // validar producción y facturación:
            $resul_produ_factu = $this->validar_produ_factu($un_arr['produ_movi'] , $un_arr['factu_movi']);
            if($resul_produ_factu != "bien"){
                array_push($arr_errores , [
                    'id' => $un_arr['id'],
                    'campo' => 'produ_factu',
                    'mensaje_error' => $resul_produ_factu,
                ]);
            }

            // Validar número de factura:
            $resul_numero_factu = $this->validar_numero_factu($un_arr['factu_movi'] , $un_arr['factu_num']);
            if($resul_numero_factu != "bien"){
                array_push($arr_errores , [
                    'id' => $un_arr['id'],
                    'campo' => 'numero_factu',
                    'mensaje_error' => $resul_numero_factu,
                ]);
            }
        }

        // 2) Validación de los totales produ factu:
        if($this->modal_modificar_total_produ >= $this->modal_modificar_total_factu){
            // pasó la verificación.
        }else{
            array_push($arr_errores , [
                'id' => 'TODOS',
                'campo' => 'produ_factu',
                'mensaje_error' => 'El total de facturación no puede ser mayor al total de producción',
            ]);
        }

        // 3) Al llegar aqui:
        //      Si el $arr_errores está vacio ([]): NO HAY ERRORES
        //      en caso contrario: hay errores  
        // Se llena la variable (mensajes error) que será mostrada en el modal de modificar:
        if($arr_errores !== []){
            $this->modal_modificar_errores = "<br><center>No se pudieron grabar los cambios, verificar:</center><br>";
            foreach ($arr_errores as $key => $un_error) {
                $this->modal_modificar_errores = $this->modal_modificar_errores . $un_error['id'] . ": " . $un_error['mensaje_error'] . "<br>";
            }
        }else{
            // 4) Procede a actualizar en la base de datos:
            DB::beginTransaction();
            try { 
                // 5) Actualizar los campos en la tabla pedidos_inventarios:
                foreach($this->arr_input_modificar_inventarios as $key3 => $un_arr3){
                    $rgto_modificar = PedidosInventario::find($un_arr3['id']);
                    if($rgto_modificar->fec_movi == $un_arr3['fec_movi']
                            && $rgto_modificar->produ_movi == $un_arr3['produ_movi']
                            && $rgto_modificar->factu_movi == $un_arr3['factu_movi']
                            && $rgto_modificar->factu_num == $un_arr3['factu_num']   ){
                        // no debe hacer update pues el usuario no cambió ningun dato.
                    }else{
                        // Si el usuario dejo en blanco el número de factura, se debe
                        // traducir a null:
                        if($un_arr3['factu_num'] == ""){
                            $factu_num_aux = null;
                        }else{
                            $factu_num_aux = $un_arr3['factu_num'];
                        }
                        $rgto_modificar->fec_movi = $un_arr3['fec_movi'];
                        $rgto_modificar->produ_movi = $un_arr3['produ_movi'];
                        $rgto_modificar->factu_movi = $un_arr3['factu_movi'];
                        $rgto_modificar->factu_num = $factu_num_aux;
                        $rgto_modificar->modificado_por_id = Auth::user()->id;
                        $rgto_modificar->modificado_el = date('Y-m-d H:i:s');                         
                        $rgto_modificar->save();                    
                    }                    
                }

                // 6) Actualizar el estado del pedido en la tabla pedidos_detalles, 
                // en caso de que aplique.
                // 6a) Obtener el nuevo estado:
                $nuevo_estado = $this->determinar_nuevo_estado_por_modi_produ_factu(
                    $this->modal_modificar_estado_id,
                    $this->modal_modificar_canti_pedida,
                    $this->modal_modificar_total_produ,
                    $this->modal_modificar_total_factu
                );
                if($nuevo_estado == 0){
                    // no hay que cambiar el estado
                }else{
                    // 6b) actualizar el campo estado_id en la tabla pedidos_detalles:
                    $rgto_modificar_det = PedidosDetalle::find($this->modal_modificar_pedido_detalle_id);
// dd($rgto_modificar_det);
                    $rgto_modificar_det->estado_id = $nuevo_estado;
                    $rgto_modificar_det->modificado_por_id = Auth::user()->id;
                    $rgto_modificar_det->modificado_el = date('Y-m-d H:i:s');                     
                    $rgto_modificar_det->save();
                }

                DB::commit();
                $this->cerrar_modal_modificar_produccion_facturacion();;
            }catch (\Throwable $th) {
                // devolver las transacciones:
                DB::rollback();   
                $this->modal_modificar_errores = "Error b.d. " . $th->getMessage() ;  
            }
        }
    }

    // ==================================================================================
    //      Métodos para el modal ANULAR pedido_conse
    // ==================================================================================     
    public function mostrar_modal_anular($pedidos_detalle_id , $pedido_conse , $estado_id , $estado_nombre , $cliente_nombre , $dir_entrega , $tipo_producto_nombre , $codigo_producto , $canti){
        $this->anular_modal_visible = true;
        $this->modal_anular_pedido_detalle_id = $pedidos_detalle_id;
        $this->modal_anular_pedido_conse = $pedido_conse;
        $this->modal_anular_estado_nombre = $estado_nombre;
        $this->modal_anular_estado_id = $estado_id;
        $this->modal_anular_cliente_nombre = $cliente_nombre;
        $this->modal_anular_dir_entrega = $dir_entrega;
        $this->modal_anular_tipo_producto_nombre = $tipo_producto_nombre;
        $this->modal_anular_codigo_producto = $codigo_producto;
        $this->modal_anular_pedida_canti = $canti;
    }

    public function cerrar_modal_anular(){
        $this->reset(['modal_anular_input_causa']);
        $this->resetValidation();
        $this->anular_modal_visible = false;
    }    

    public function submit_anular_pedido_conse(){
        // Llega aqui cuando el usuario presiona ANULAR en el modal para anular un pedido

        $this->reset('arr_rules');  
        $this->reset('arr_messages');  
                
        // validar la causa de la anlación (campo obligatorio):
        $this->arr_rules['modal_anular_input_causa'] = 'required';
        $this->arr_messages['modal_anular_input_causa.required'] = 'Es obligatorio suministrar la causa de la anulación.';
        $this->validate(); 

        DB::beginTransaction();
        try { 
            // 1) actualizar el estado en pedidos_detalles: 
            $rgto_modificar = PedidosDetalle::find($this->modal_anular_pedido_detalle_id);
            $rgto_modificar->estado_id = 8;
            $rgto_modificar->causa_anulacion = $this->modal_anular_input_causa;
            $rgto_modificar->modificado_por_id = Auth::user()->id;
            $rgto_modificar->modificado_el = date('Y-m-d H:i:s'); 
            $rgto_modificar->save();

            DB::commit();
            $this->mensaje_correcto = "Proceso de anulación ejecutado correctamente." ;
            $this->cerrar_modal_anular();
        }catch (\Throwable $th) {
            // devolver las transacciones:
            DB::rollback();   
            $this->mensaje_error = "Error b.d. " . $th->getMessage() ;  
        }    
    }    

    // ==================================================================================
    //      Métodos privados
    // ==================================================================================     
    private function validar_fecha($fecha_val){
        // Validar que la fecha: 
        //     No esté vacia 
        //     Sea formato aaaa-mm-dd 
        $resul = "bien";
        if($fecha_val !== ""){
            if(strlen($fecha_val) == 10
                && substr($fecha_val,4,1) == "-"
                && substr($fecha_val,7,1) == "-")
            {
                $an = substr($fecha_val,0,4);
                $mes = substr($fecha_val,5,2);
                $dia = substr($fecha_val,8,2);
// dd(is_numeric($dia) . is_numeric($mes) . is_numeric($an));                
// dd(is_int($dia) . is_int($mes) . is_int($an));                
                // if(is_numeric($an) && is_int($an) && is_numeric($mes) && is_int($mes) && is_numeric($dia) && is_int($dia)){
                if(is_numeric($an) && is_numeric($mes) && is_numeric($dia)){
                    // if(is_int($an) && is_int($mes) && is_int($dia)){
                        if($dia >= 1 && $dia <= 31 && $mes >=1 and $mes <=12 && $an>=1900 && $an<=2100){
                            // fecha correcta, devolverá "bien"
    // dd($fecha_val);                        
                        }else{
                            $resul = "La fecha debe tener el formato: aaaa-mm-dd (Verificar dia, mes y año digitados)";
                        }
                    // }else{
                    //     $resul = "La fecha no debe tener puntos.";
                    // }
                }else{
                    $resul = "La fecha solo puede contener números y guiones ( - )";
                }                
            }else{
                $resul = "La fecha debe tener el formato: aaaa-mm-dd (Verificar que la fecha sea de 10 caracteres y que se usen los dos guiones como debe ser.)";
            }
        }else{
            $resul = "Es obligatorio digitar la fecha";
        }
        return $resul;
    }

    private function validar_produ_factu($produ_val , $factu_val){
        // Validación de producción y facturación.
        // Recibe 2 parámetros:
        //      $produ_val: Producción digitada por el usuario 
        //      $factu_val: Facturación digitada por el usuario
        // lo digitado:
        //       debe ser un número entero mayor o igual a cero.
        //       ambos (produ y factu) no pueden ser diferentes de cero al mismo tiempo.

        $resul = "bien";
        // Lo digitado debe ser entero
        if(is_numeric($produ_val)  && is_numeric($factu_val)){
            if((int)$produ_val == $produ_val && (int)$factu_val == $factu_val ){
                // debe ser mayor que cero:
                if($produ_val >= 0 && $factu_val >=0){
                    // NO puede ser que los dos sean diferentes de cero al mismo tiempo:
                    if($produ_val >= 1 && $factu_val >= 1){
                        $resul = "Cada fila debe tener o producción o facturación (Pero no ambos).";
                    }
                }else{
                    $resul = "Producción y facturación no pueden ser negativos.";
                }
            }else{
                $resul = "En producción y facturación se deben digitar números enteros.";
            }
        }else{
            $resul = "En producción y facturación solo se pueden digitar números mayores o iguales a cero.";
        }
        return $resul;
    }

    private function validar_numero_factu($factu_val , $factu_numero){
        // Validación del número de factura.
        // Recibe 2 parámetros:
        //      $factu_val: Facturación digitada por el usuario
        //      $factu_numero: Número de factura digitada por el usuario
        // El número de factura:
        //       Es obligatorio si factu_val es un número diferente de cero
        //       Debe ser un número entero mayor o igual a 1

        $resul = "bien";
        if(is_numeric($factu_val)  && $factu_val >= 1){
            // Debe existir el número de factura:
            if( !($factu_numero == null || $factu_numero == "") ){
                if(is_numeric($factu_numero)){
                    if($factu_numero >= 1){
                        // el número de factura es correcto.
                    }else{
                        $resul = "El número de factura debe ser un entero mayor o igual a 1.";
                    }
                }else{
                    $resul = "Solamente puede escribir números en el Nro de factura.";
                }
            }else{
                $resul = "Cuando hay facturación, se debe digitar el número de factura.";
            }
        }else{
            if($factu_numero == null || $factu_numero == ""){
                // el número de factura es correcto.
            }else{
                $resul = "Cuando no hay facturación, no se debe digitar número de factura.";
            }
        }
        return $resul;
    }

    private function determinar_nuevo_estado_por_modi_produ_factu($ea  , $c , $p , $f){
        // 13abr2022: Determina si por las modificaciones que se hicieron en el 
        // modal modificar producción y facturación, el pedido_conse debe cambiar de 
        // estado o no.
        // Si no debe cambiar de estado devolverá el número cero.
        // Si debe cambiar de estado, devolverá el id del nuevo estado que
        // tendrá el pedido_conse.
        // Parámetros recibidos:
        //      $ea: El id del estado actual del pedido
        //      $c:  Cantidad pedida que tiene grabado el pedido_conse
        //      $p:  Total de producción después de las modificaciones del usuario
        //      $f:  Total de facturación después de las modificaciones del usuario
        $nuevo_estado = -1;
        if($c > $p && $p >= $f){
            $nuevo_estado = 3;
        }else if($c == $p && $p < $f){
            $nuevo_estado = 4;
        }else if($c <= $p && $p > $f){
            $nuevo_estado = 4;
        }else if($c <= $p && $p == $f){
            $nuevo_estado = 7;
        }
        if($nuevo_estado == -1){
            // no entró a ninguno de los anteriores if, no cambio el estado:
            $resul = 0;
        }else if($nuevo_estado == $ea){
            // entro a alguno de los anteriores if, pero el estado no cambió
            // respecto al grabado en la b.d.:
            $resul = 0;
        }else{
            // sí hubo cambio de estado:
            $resul = $nuevo_estado;
        }
        return $resul;
    }
}
