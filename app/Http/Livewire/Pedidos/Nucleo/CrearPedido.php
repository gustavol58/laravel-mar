<?php

namespace App\Http\Livewire\Pedidos\Nucleo;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Cliente; 
use App\Models\Pedidos\FormuTipoProducto; 

class CrearPedido extends Component
{
    // ==================================================================================
    // Propiedades para recibir parámetros
    // ==================================================================================
    public $operacion;   // 'crear'  o  'modificar'
    public $modificar_pedido_encab_id;   // id del pedido a modificar

    // ==================================================================================
    // Propiedades para validación de campos del formulario
    // ==================================================================================
    public $mensaje_correcto;
    public $mensaje_error;

    // ==================================================================================
    // Propiedades wire:model
    // ==================================================================================
    public $cliente_id;      
    public $dir_entrega;
    public $obs;
    public $creado_por_id;
    public $modificado_por_id;
    public $creado_el;
    public $modificado_el;
    public $arr_detalle_productos;
    // 12mar2022: lleva la cuenta de productos del detalle a medida que se
    // agrean (o eliminan) en el detalle de productos.
    public $filas_detalles;  
    // 12mar2022: Solo usada en 'modificar': fija el número de productos que
    // originalmente tiene el pedido escogido para ser modificado:
    public $filas_detalles_original;   
  
    // ==================================================================================
    // Propiedades para llenar selects
    // ==================================================================================    
    public $arr_para_clientes;
    public $arr_para_tipos_producto;
    // debe ser multidimensional, porque cada fila de producto en detalle tendrá 
    // un arr_para_productos distinto
    public $arr_para_productos = [[]];

    // ==================================================================================
    // Propiedades para modales
    // ==================================================================================    
    public $modal_visible_cancelar = false;

    // ==================================================================================
    // Rules
    // ==================================================================================    
    protected $rules = [
        'cliente_id' => 'required',
        'dir_entrega' => 'required|max:200',
        'arr_detalle_productos.*.tipo_producto_id' => 'required',
        'arr_detalle_productos.*.producto_id' => 'required',
        'arr_detalle_productos.*.categoria' => 'required',
        'arr_detalle_productos.*.canti' => 'required|integer|min:1',
        'arr_detalle_productos.*.precio' => 'required|numeric|min:1',
    ];

    protected $messages = [
        'cliente_id.required' => 'Se debe escoger un cliente.',
        'dir_entrega.required' => 'Se debe digitar una dirección.',
        'dir_entrega.max' => 'La longitud máxima son 200 caracteres.',
        'arr_detalle_productos.*.tipo_producto_id.required' => 'Se debe escoger un tipo de producto.',
        'arr_detalle_productos.*.producto_id.required' => 'Se debe escoger un de producto.',
        'arr_detalle_productos.*.categoria.required' => 'Se debe escoger una categoria.',
        'arr_detalle_productos.*.canti.required' => 'Se debe digitar la cantidad.',
        'arr_detalle_productos.*.canti.integer' => 'La cantidad debe ser un número entero.',
        'arr_detalle_productos.*.canti.min' => 'Mínimo: 1.',
        'arr_detalle_productos.*.precio.required' => 'Se debe digitar el precio.',
        'arr_detalle_productos.*.precio.numeric' => 'El precio debe ser numérico.',
        'arr_detalle_productos.*.precio.min' => 'Mínimo: 1.',
    ];   
    
    // // ==================================================================================
    // // Matricular eventos javascritp
    // // ==================================================================================     
    // protected $listeners = ['calcular_div_'];

    public function mount($operacion , $modificar_pedido_encab_id = null){
        $this->operacion = $operacion;
        $this->modificar_pedido_encab_id = $modificar_pedido_encab_id;

        // ==================================================================================
        // Si la operación es 'modificar': Traer info del pedido desde la b.d.
        // ==================================================================================
        if($this->operacion == 'crear'){
            // Inicializaciones para la petición del detalle de productos,
            // obligatoriamente debe haber 1 producto:
            $this->arr_detalle_productos[] = [];
            $this->filas_detalles = 1;
        }elseif($this->operacion == 'modificar'){   
            $this->arr_detalle_productos = [];
            $arr_params1 =[];
            $sql1 = "SELECT pedenc.id pedido_encab_id, peddet.id pedidos_detalle_id, 
                        concat(lpad(peddet.pedido_encab_id , 5 , '0'),'_',peddet.conse) pedido_conse,
                        peddet.estado_id,
                        peddet.estado_anterior_id,
                        pedest.nombre_estado estado_nombre,
                        cli.id cliente_id,
                        cli.nom_cliente cliente_nombre,
                        pedenc.dir_entrega,
                        pedenc.obs,
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
                        pedenc.creado_por_id,
                        pedenc.creado_el,
                        usu3.name usuario_modifico,
                        pedenc.modificado_por_id,
                        pedenc.modificado_el        
                FROM pedidos_detalles peddet
                    left join pedidos_encabs pedenc on pedenc.id=peddet.pedido_encab_id
                    left join pedidos_estados pedest on pedest.id=peddet.estado_id
                    left join clientes cli on cli.id=pedenc.cliente_id
                    left join formu_tipo_productos fti on fti.id=peddet.tipo_producto_id
                    left join users usu2 on usu2.id=pedenc.creado_por_id
                    left join users usu3 on usu3.id=pedenc.modificado_por_id
                where pedenc.id = :modificar_pedido_encab_id ";
            $arr_params1 = [
                ':modificar_pedido_encab_id' => $this->modificar_pedido_encab_id
            ];            
            $coll_modificar_pedido = collect(DB::select($sql1, $arr_params1));
// dd($coll_modificar_pedido);

            $coll_modificar_pedido_fila0 = $coll_modificar_pedido[0];
// dd($coll_modificar_pedido_fila0);
            $this->cliente_id = $coll_modificar_pedido_fila0->cliente_id;      
            $this->dir_entrega = $coll_modificar_pedido_fila0->dir_entrega;      
            $this->obs = $coll_modificar_pedido_fila0->obs;      
            $this->creado_por_id = $coll_modificar_pedido_fila0->creado_por_id; 
            $this->modificado_por_id = $coll_modificar_pedido_fila0->modificado_por_id; 
            $this->creado_el = $coll_modificar_pedido_fila0->creado_el; 
            $this->modificado_el = $coll_modificar_pedido_fila0->modificado_el; 
            foreach ($coll_modificar_pedido as $key => $un_coll) {
                $arr_aux = [
                    'tipo_producto_id' => $un_coll->tipo_producto_id,
                    'producto_id' => $un_coll->producto_id,
                    'categoria' => $un_coll->categoria,
                    'canti' => $un_coll->canti,
                    'precio' => $un_coll->precio,
                    'obs_producto' => $un_coll->obs_producto,
                ];
                array_push($this->arr_detalle_productos , $arr_aux);
                // llena el combo productos para esta fila del detalle productos: 
                $this->arr_para_productos[$key] =   $this->llenar_select_productos_nucleo($un_coll->tipo_producto_id);
            }
            $this->filas_detalles = count($this->arr_detalle_productos);
            $this->filas_detalles_original = count($this->arr_detalle_productos);
// dd($this->arr_detalle_productos);
        }        
    }

    public function render(){
        // Para el select de clientes: 
        if(Auth::user()->roles->pluck('name')->first() == "admin"){
            $sql1 = "select id, nom_cliente
                from clientes
                where estado = 3
                order by nom_cliente";
            $this->arr_para_clientes = collect(DB::select($sql1));
        }else{
            $arr_params =[];
            $sql1 = "select id, nom_cliente 
                from clientes 
                where comercial_id = :comercial_id 
                    and estado = 3
                order by nom_cliente";
            $arr_params = [
                'comercial_id' => Auth::user()->id
            ];            
            $this->arr_para_clientes = collect(DB::select($sql1, $arr_params));
        }

        // Para el select de Tipos de producto:
        $sql1 = "select id, tipo_producto_nombre
            from formu_tipo_productos
            where prefijo is not null
            order by tipo_producto_nombre";
        $this->arr_para_tipos_producto = collect(DB::select($sql1));   

        // Nota: Para el select de productos, según el tipo producto escogido,
        // el array solo podrá ser llenado cuando el usuario escoja un tipo de
        // producto, por lo tanto esta tarea se hace en el método llenar_select_productos()

        return view('livewire.pedidos.nucleo.crear-pedido');
    }

    public function submit_pedido(){
        // Llega acá cuando se presiona el botón "Crear pedido" en el formulario 
        // de creación de un pedido O en el botón "Grabar modificaciones del pedido" 
        // en la modificación de un pedido.

        // ==============================================================
        // Para mensajes de proceso correcto, o de errror
        // ==============================================================        
        $this->mensaje_correcto = "";
        $this->mensaje_error = "";   
        
        $this->validate(); 

// echo "<br>cliente_id: ".$this->cliente_id;        
// echo "<br>dir_entrega: ".$this->dir_entrega;        
// echo "<br>obs: ".$this->obs; 
// echo "<pre>";
// print_r($this->arr_detalle_productos);      
// dd('fin mostrar recibidos....');           
// dd($this->arr_detalle_productos);      
        
        // Poner en mayúsculas los input text:
        $this->dir_entrega = strtoupper($this->dir_entrega);        
        $this->obs = strtoupper($this->obs); 
        foreach($this->arr_detalle_productos as $key => $un_arr_producto){
            if(isset($un_arr_producto['obs_producto'])){
                $this->arr_detalle_productos[$key]['obs_producto'] = strtoupper($un_arr_producto['obs_producto']);
            }else{
                $this->arr_detalle_productos[$key]['obs_producto'] = null;
            }
        } 

        $arr_grabar_encab = [];
        $arr_grabar_encab['cliente_id'] = $this->cliente_id;
        $arr_grabar_encab['dir_entrega'] = $this->dir_entrega;
        $arr_grabar_encab['obs'] = $this->obs;
        
        if($this->operacion == 'crear'){
            DB::beginTransaction();
            try {
                // Grabación en pedidos_encab:
                $arr_grabar_encab['creado_por_id'] = Auth::user()->id;
                $arr_grabar_encab['creado_el'] = date('Y-m-d H:i:s');

                // Es recomendable usar siempre query builder, este previene SQL inyección
                // query builder:  DB::tablet(...)->insert(...);  // query builder: this prevents SQL injection.
                // raw builder:    DB::insert(...);    // raw builder: this dont prevent injection            
                $grabado_pedido_id = DB::table('pedidos_encabs')->insertGetId($arr_grabar_encab); 

                // Grabación en pedidos_detalles:
                $conse = 1;
                foreach ($this->arr_detalle_productos as $key => $arr_un_producto_grabar) {
                    $arr_grabar_detalle = [];
                    $arr_grabar_detalle['pedido_encab_id'] = $grabado_pedido_id;
                    $arr_grabar_detalle['conse'] = ($conse <= 9) ? "0" . $conse : $conse ;
                    $arr_grabar_detalle['estado_id'] = 1;
                    $arr_grabar_detalle['tipo_producto_id'] = $arr_un_producto_grabar['tipo_producto_id'];
                    $arr_grabar_detalle['producto_id'] = $arr_un_producto_grabar['producto_id'];
                    $arr_grabar_detalle['categoria'] = $arr_un_producto_grabar['categoria'];
                    $arr_grabar_detalle['canti'] = $arr_un_producto_grabar['canti'];
                    $arr_grabar_detalle['precio'] = $arr_un_producto_grabar['precio'];
                    $arr_grabar_detalle['obs_producto'] = $arr_un_producto_grabar['obs_producto'];
                    $arr_grabar_detalle['creado_por_id'] = Auth::user()->id;
                    $arr_grabar_detalle['creado_el'] = date('Y-m-d H:i:s');                    
                    $conse ++;
                    DB::table('pedidos_detalles')->insert($arr_grabar_detalle); 
                }

                DB::commit();

                $this->mensaje_correcto = "Pedido creado correctamente." ;
                $this->limpiar();
            }catch (\Throwable $th) {
                // devolver las transacciones:
                DB::rollback();  
                $this->mensaje_error = "Error b.d. " . $th->getMessage() ;  

// 20feb2022 ensayos por error que se presentó domingo 16feb:                
// $para_auto_inc = DB::table('pedidos_detalles')->max('id') + 1;
// dd($ultimo_id);
// config()->set('database.connections.u306294386_mark_pruebas22.strict', false);
// config(['database.connections.u306294386_mark_pruebas22.strict' => false]);
        // DB::reconnect();
// DB::unprepared('alter table pedidos_detalles auto_increment = :conse' , [':conse' => $para_auto_inc]);
// DB::unprepared('alter table pedidos_detalles auto_increment = :aaa' , [':aaa' => 300]);

// este fue el único que funcionó:
// DB::update("ALTER TABLE pedidos_detalles AUTO_INCREMENT = 200;");
// DB::update("ALTER TABLE pedidos_detalles AUTO_INCREMENT = ?;" , [400]);

// config()->set('database.connections.u306294386_mark_pruebas22.strict', true);
// config(['database.connections.u306294386_mark_pruebas22.strict' => true]);
// DB::reconnect();
                
            }    
        }elseif($this->operacion == 'modificar'){
// dd($arr_grabar_encab);            
            // Modificación del encabezado:
            $arr_grabar_encab['modificado_por_id'] = Auth::user()->id;
            $arr_grabar_encab['modificado_el'] = date('Y-m-d H:i:s');
            DB::table('pedidos_encabs')->where('id', $this->modificar_pedido_encab_id)->update($arr_grabar_encab);


// 08mar2022: ensayo de: Actualizar con RAW SQL:            
// $arr_params = [];
// $sql = "update pedidos_encabs  
//     set cliente_id=:cliente_id , 
//     dir_entrega=:dir_entrega , 
//     obs=:obs , 
//     modificado_por_id=:modificado_por_id , 
//     modificado_el=:modificado_el";
// $arr_params = [
//     ':cliente_id' => $this->cliente_id,
//     ':dir_entrega' => $this->dir_entrega,
//     ':obs' => $this->obs,
//     ':modificado_por_id' => Auth::user()->id,
//     ':modificado_el' => date('Y-m-d H:i:s'),
// ];
// $num_rgtos = DB::update($sql , $arr_params); 

            // Modificación en pedidos_detalles:

// dd($this->arr_detalle_productos);   

            // 12mar2022: Modificación del detalle (productos):
            foreach($this->arr_detalle_productos as $key => $arr_un_producto_grabar){
                $conse_modif_num = $key + 1; 
                $conse_modif_texto = ($conse_modif_num <= 9) ? "0" . $conse_modif_num : $conse_modif_num ;
                $arr_grabar_detalle = [];
                $arr_grabar_detalle['tipo_producto_id'] = $arr_un_producto_grabar['tipo_producto_id'];
                $arr_grabar_detalle['producto_id'] = $arr_un_producto_grabar['producto_id'];
                $arr_grabar_detalle['categoria'] = $arr_un_producto_grabar['categoria'];
                $arr_grabar_detalle['canti'] = $arr_un_producto_grabar['canti'];
                $arr_grabar_detalle['precio'] = $arr_un_producto_grabar['precio'];
                $arr_grabar_detalle['obs_producto'] = $arr_un_producto_grabar['obs_producto'];

                if($key < $this->filas_detalles_original){
                    // el producto actual ya existia en pedidos_detalles, debe hacer un UPDATE
                    // a la tabla pedidos_detalles:

                    // Lo primero es leer de la b.d. el estado y las cantidades
                    // producidas y facturadas del pedido_conse existente:
                    $arr_params3 =[];
                    $sql3 = "select estado_id , 
                            (select sum(produ_movi) 
                                from pedidos_inventarios 
                                where pedidos_detalle_id=peddet.id) produ_canti, 
                            (select sum(factu_movi) 
                                from pedidos_inventarios 
                                where pedidos_detalle_id=peddet.id) factu_canti
                        from pedidos_detalles peddet 
                        where pedido_encab_id=:pedido_encab_id and conse=:conse_texto";
                    $arr_params3= [
                        ':pedido_encab_id' => $this->modificar_pedido_encab_id,
                        ':conse_texto' => $conse_modif_texto
                    ];            
                    $coll_factu_pedido_conse = collect(DB::select($sql3, $arr_params3));        
                    $coll_factu_pedido_conse_fila0 = $coll_factu_pedido_conse[0];
                    $estado_actual = $coll_factu_pedido_conse_fila0->estado_id;
                    $produ_hoy = $coll_factu_pedido_conse_fila0->produ_canti;
                    $factu_hoy = $coll_factu_pedido_conse_fila0->factu_canti;
                    
                    // Ahora determinar si, por cambio de la cantidad pedida, el estado
                    // del pedido_conse debe ser actualizado:
                    $canti_nueva = $arr_un_producto_grabar['canti'];
                    $estado_nuevo = $estado_actual;
                    if($estado_actual == 3){
                        if($canti_nueva > $produ_hoy){
                            $estado_nuevo = 3;
                        }else{
                            if($produ_hoy == $factu_hoy){
                                $estado_nuevo = 7;
                            }else{
                                $estado_nuevo = 4;
                            }
                        }
                    }elseif($estado_actual == 4){
                        if($canti_nueva > $produ_hoy){
                            $estado_nuevo = 3;
                        }else{
                            $estado_nuevo = 4;
                        }
                    }elseif($estado_actual == 7){
                        if($canti_nueva > $produ_hoy){
                            $estado_nuevo = 3;
                        }else{
                            $estado_nuevo = 7;
                        }
                    }
                    $arr_grabar_detalle['estado_id'] = $estado_nuevo;
                    $arr_grabar_detalle['modificado_por_id'] = Auth::user()->id;
                    $arr_grabar_detalle['modificado_el'] = date('Y-m-d H:i:s'); 
                    DB::table('pedidos_detalles')->where('pedido_encab_id', $this->modificar_pedido_encab_id)
                        ->where('conse', $conse_modif_texto)
                        ->update($arr_grabar_detalle);
                }else{
                    // el producto actual fue agregado como nuevo por el usuario, se debe hacer
                    // un insert into:
                    $arr_grabar_detalle['pedido_encab_id'] = $this->modificar_pedido_encab_id;
                    $arr_grabar_detalle['conse'] = $conse_modif_texto ;
                    $arr_grabar_detalle['estado_id'] = 1;        
                    $arr_grabar_detalle['creado_por_id'] = Auth::user()->id;
                    $arr_grabar_detalle['creado_el'] = date('Y-m-d H:i:s'); 
                    DB::table('pedidos_detalles')->insert($arr_grabar_detalle);
                }
            }    

            $this->mensaje_correcto = "Pedido modificado correctamente." ;
            $this->limpiar();            
            return redirect(url('ver-pedidos')); 
        }
    }

    public function btn_limpiar(){
        // llamada desde el blade: 
        $this->mensaje_correcto = "";
        $this->mensaje_error = ""; 
        $this->limpiar();        
    }

    protected function limpiar(){
        // llamada desde otras funciones de esta class:
        $this->reset('cliente_id');
        $this->reset('dir_entrega');
        $this->reset('arr_detalle_productos');
        $this->reset('obs');
    }  
    
    public function mostrar_modal_btn_cancelar(){
        // Muestra el modal en donde se le avisará al usuario 
        // que hay info sin llenar.

        // Primero se verifica si hay o no info sin grabar: 
        $campos_vacios = true;

        if(strlen(trim($this->cliente_id)) !== 0){
            $campos_vacios = false;
        }            
        if(strlen(trim($this->dir_entrega)) !== 0){
            $campos_vacios = false;
        } 
        if(strlen(trim($this->obs)) !== 0){
            $campos_vacios = false;
        }
        if($this->arr_detalle_productos !== null){
            foreach ($this->arr_detalle_productos as $un_producto_revisar) {
                foreach ($un_producto_revisar as $elemento) {
                    if(strlen(trim($elemento)) !== 0){
                        $campos_vacios = false;
                    }    
                }
            }                  
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
        return redirect(url('ver-pedidos' ));
    }  

    public function llenar_dir_entrega($escogido_cliente_id){
        // Llega aquí en el EVENTO change() del select "Clientes".
        // Llena el input text dir_entrega con la que esté grabada
        // en la tabla clientes:
        if($escogido_cliente_id == ""){
            $this->dir_entrega = ''; 
        }else{
            $cliente_rgto = Cliente::find($escogido_cliente_id); 
            $this->dir_entrega = $cliente_rgto->direccion; 
        }
    }

    public function llenar_select_productos($escogido_tipo_producto_id , $fila_producto){
        // Llega aquí en el EVENTO change() del select "Tipo de producto".
        // Parámetros:
        //      el primero es el id del tipo de producto que se escogió en el select
        //      el segundo indica el producto (fila) del detalle de productos
        // Obtiene los productos de acuerdo al tipo producto escogido y los lleva
        // a la fila del arr_para_productos que corresponda a la fila del detalle, 
        // siempre y cuando los productos tengan estado APROBADO 
        // echo $escogido_tipo_producto_id;
// dd($fila_producto); 
        if($escogido_tipo_producto_id == ""){
            $this->arr_para_productos[$fila_producto] = []; 
        }else{
            $this->arr_para_productos[$fila_producto] = $this->llenar_select_productos_nucleo($escogido_tipo_producto_id);
        }
        // para que en el select aparezca la opción "Seleccione...":
        $this->arr_detalle_productos[$fila_producto]['producto_id'] = "";
    }

    private function llenar_select_productos_nucleo($escogido_tipo_producto_id){
        // para obtener el slug del tipo de producto:
        $tipo_producto_rgto = FormuTipoProducto::find($escogido_tipo_producto_id); 

        // lee los productos del tipo de producto escogido que tengan estado APROBADO:
        $formu__tabla = 'formu__' . $tipo_producto_rgto->tipo_producto_slug;
        $arr_params2 =[];

        // Los estados 1,9,10 y 11 son los que corresponden a 'No aprobado 
        // aun por comercial':
        $sql2 = "select f.id, 
                f.codigo 
            from " . $formu__tabla . " f 
                left join formu_contenidos_estados fce 
                    on (fce.formu_tipo_producto_id=:tipo_producto_id
                        and fce.formu__id=f.id 
                        and fce.tiempo_estado=1) 
                left join formu_estados fe 
                    on fe.id=fce.formu_estado_id 
            where fe.id not in (1,9,10,11);";
        $arr_params2 = [
            ':tipo_producto_id' => $escogido_tipo_producto_id
        ];                     
        return collect(DB::select($sql2 , $arr_params2));
    }

    public function agregar_detalle_producto(){
        $this->arr_detalle_productos[] = [];
        $this->filas_detalles ++;
// dd($this->arr_detalle_productos);        
    }

    public function eliminar_detalle_producto($producto_conse){
        $this->filas_detalles --;
        // Para borrar la fila(unset) y además la posición(array_values) 
        // en el arr_detalle_productos:
        unset($this->arr_detalle_productos[$producto_conse]);
        $this->arr_detalle_productos = array_values($this->arr_detalle_productos);

        // También se debe borrar la fila correspondiente del $arr_para_productos:
        unset($this->arr_para_productos[$producto_conse]);
        $this->arr_para_productos = array_values($this->arr_para_productos);        
    }      

}
