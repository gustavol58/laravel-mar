<?php

namespace App\Http\Livewire\Pedidos\Formu;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

use App\Models\Pedidos\FormuTipoProducto;
use App\Models\Pedidos\FormuDetalle;
use App\Models\Pedidos\FormuDetallesMultivariable;
use App\Models\Pedidos\FormuContenidosMultivariable; 
use App\Models\Pedidos\FormuEstado; 
use App\Models\Pedidos\FormuContenidosEstado; 

class VerFormu extends Component
{
    use WithPagination;
    
    public $tipo_producto_id;

    public $filas_por_pagina;
    public $ordenar_campo;
    public $ordenar_tipo;   
    
    public $arr_filtros_slug;

    public $mostrar_multimodal_visible;
    public $modal_titulo_campo;
    public $modal_arr_multivariable;
    public $modal_arr_multivariable_cabeceras;

    // 22oct2021: Para el manejo de estados:
    public $mostrar_cambio_estados_visible;
    public $modal_estados_codigo_producto;
    public $modal_estados_boton1;
    public $modal_estados_boton2;
    // $modal_estados_boton3 (boton 3) unas veces será usado y otras veces
    // no, es por eso que es el único al que hay que inicializarlo con vacio:
    public $modal_estados_boton3 = "";
    public $modal_estados_formu__id;
    public $modal_estados_nuevo_estado_id_boton1;
    public $modal_estados_nuevo_estado_id_boton2;
    public $modal_estados_nuevo_estado_id_boton3;

    // 22oct2021: Array para determinar el estado_id a partir
    // de un nombre de estado, es llenado en mount()
    public $arr_estados_id;

    public function mount($tipo_producto_id){
        $this->mostrar_multimodal_visible = false; 
        $this->mostrar_cambio_estados_visible = false; 

        $this->tipo_producto_id = $tipo_producto_id;
        $this->filas_por_pagina = 50;  
        $this->ordenar_campo = 'id';
        $this->ordenar_tipo = ' asc';  
        // $this->arr_filtros_slug debe ser inicializado con los slugs de los campos que 
        // tenga formu_detalles (mas los 4 campos fijos: id, codigo, user_name y created_at), de
        // tal manera que este array tendrá una estructura similar a esta: 
        //      $this->arr_filtros_slug = [
        //          'id' => '',
        //          'codigo' => '',
        //          'nombres_y_apellidos' => '',
        //          'edad' => '',
        //          ....
        //          'user_name' => '',
        //          'created_at' => '',
        //      ];   
        $campos_detalle_para_arr_filtros = FormuDetalle::select('slug')->where('tipo_producto_id' , $this->tipo_producto_id)->where('html_elemento_id' , '<>' , 8)->orderBy('orden')->get()->toArray();
        $this->arr_filtros_slug['id'] = '';
        $this->arr_filtros_slug['codigo'] = '';
        $this->arr_filtros_slug['nombre_estado'] = ''; 
        foreach($campos_detalle_para_arr_filtros as $fila){
            $this->arr_filtros_slug[$fila['slug']] = '';
        }
        $this->arr_filtros_slug['user_name'] = '';
        $this->arr_filtros_slug['created_at'] = '';

        // 22oct2021: 
        // array que sirve para determinar el estado_id a partir de un
        // nombre de estado. Es usado en la vista y en el método modificar_estado_producto
        $this->arr_estados_id = [];
        $arr_estados_id_aux = FormuEstado::get()->toArray();
        foreach($arr_estados_id_aux as $fila_){
                $this->arr_estados_id[$fila_['nombre_estado']] = $fila_['id'];
        }        
    }  

    public function render(){
        // Determinar campos de la tabla formu__....... 
        $slug_tabla_formu__ = FormuTipoProducto::select('prefijo' , 'tipo_producto_slug' , 'tipo_producto_nombre')->where('id' , $this->tipo_producto_id)->first();
// dd($slug_tabla_formu__);
        // $campos_detalle = FormuDetalle::select('slug' , 'cabecera' , 'html_elemento_id' , 'lista_datos')->where('tipo_producto_id' , $this->tipo_producto_id)->where('html_elemento_id' , '<>' , 8)->where('html_elemento_id' , '<>' , 12)->orderBy('orden')->get()->toArray();
        $campos_detalle = FormuDetalle::select('slug' , 'cabecera' , 'html_elemento_id' , 'lista_datos')->where('tipo_producto_id' , $this->tipo_producto_id)->where('html_elemento_id' , '<>' , 8)->orderBy('orden')->get()->toArray();
// echo "<pre>";
// print_r($campos_detalle);
// echo "</pre>";
// dd('revisar campos_detalle');

        // Lee los registros de la tabla formu__..... :
        $obj_formu_tipo = new FormuTipoProducto();
        $registros = $obj_formu_tipo->obtener_registros_formu__($this->tipo_producto_id , $slug_tabla_formu__ , $campos_detalle , $this->ordenar_campo , $this->ordenar_tipo , $this->arr_filtros_slug);
// dd($registros);

        // 31jul2021 
        // Si $registros tiene info,
        // Se debe crear un array auxiliar: $registros_arr_html_elementos_id, este array 
        // tendrá tantos elementos como número de campos tenga $registros.
        // En cada elemento su clave será el slug del campo y el valor será el html_elemento_id 
        // que corresponde a ese slug de campo.
        // Este array auxiliar será utilizado en la vista para determinar si el campo 
        // corresponde a un tipo "Subir archivo", caso en el cual debe mostrar en el
        // gridview un hipervínculo que visualice el archivo correspondiente.
        $registros_arr_html_elementos_id = [];  
        if(count($registros) !== 0){
            $fila = $registros[0];
            foreach($fila as $key => $valor){
                $aux_html_elemento_id = $this->obtener_html_elemento_id($this->tipo_producto_id , $key);
                $registros_arr_html_elementos_id[$key] = $aux_html_elemento_id;
            }
        }

// dd($registros_arr_html_elementos_id);

        // Por último, se debe agregar en la 
        // estructura de $campos_detalle, seis campos fijos (que fueron 
        // agregados a su vez en la función desde donde se obtuvieron los 
        // registros), estos campos son: 
        // tip.id (al principio), tip.codigo, estado (dependiendo del prefijo) ,
        // cerrado (también depende del prefijo), usu.user_name 
        // y tip.created_at(al final):
        if($slug_tabla_formu__->prefijo !== null){
            $arr_aux = [
                'slug' => 'cerrado',
                'cabecera' => ' ',
            ];
            array_unshift($campos_detalle , $arr_aux);
        }
        if($slug_tabla_formu__->prefijo !== null){
            $arr_aux = [
                'slug' => 'nombre_estado',
                'cabecera' => 'Estado',
            ];
            array_unshift($campos_detalle , $arr_aux);
        }
        $arr_aux = [
            'slug' => 'codigo',
            'cabecera' => 'Código producto',
        ];
        array_unshift($campos_detalle , $arr_aux);

        $arr_aux = [
            'slug' => 'id',
            'cabecera' => 'Nro',
        ];
        array_unshift($campos_detalle , $arr_aux);

        $arr_aux = [
                'slug' => 'user_name',
                'cabecera' => 'Creado por',
        ];
        array_push($campos_detalle , $arr_aux);

        $arr_aux = [
            'slug' => 'created_at',
            'cabecera' => 'Fecha creación',
        ];
        array_push($campos_detalle , $arr_aux);

        // renderizar la vista (gridview principal):
        // a) variables para cuando haya que regresar:
        $tipo_producto_nombre = $slug_tabla_formu__->tipo_producto_nombre;
        $tipo_producto_slug = $slug_tabla_formu__->tipo_producto_slug;
        $tipo_producto_prefijo = $slug_tabla_formu__->prefijo;
// dd($tipo_producto_prefijo);     
        // b) para la paginación:
        $perPage = $this->filas_por_pagina;
        $collection = $registros;
        $items = $collection->forPage($this->page, $perPage);
        $paginator = new LengthAwarePaginator($items, $collection->count(), $perPage, $this->page);
// dd($registros_arr_html_elementos_id);
        return view('livewire.pedidos.formu.ver-formu' , [
            'registros' => $paginator,
            'registros_arr_html_elementos_id' => $registros_arr_html_elementos_id,
            'campos_detalle' => $campos_detalle,
            'tipo_producto_nombre' => $tipo_producto_nombre,
            'tipo_producto_slug' => $tipo_producto_slug,
            'tipo_producto_prefijo' => $tipo_producto_prefijo,
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
    
    public function mostrar_modal_multivariable($producto_id , $key){
        // $producto_id es el id que está grabado en la tabla formu__.... 
        // $key es el slug del campo de la tabla formu__..... sobre el que se dió click
        // En la tabla formu_contenidos_multivariables se encuentra la info de 
        // multivariables del producto_id recibido, por el cual se debe filtrar
        // Con $key se debe buscar el campo_detalle_id por el cual también se debe filtrar

        
        // Determinar campo_detalle_id: 
        $arr_campo_detalle_id = FormuDetalle::select('id' , 'cabecera')->where('tipo_producto_id',$this->tipo_producto_id)->where('slug' , $key)->get()->toArray();
        $campo_detalle_id = $arr_campo_detalle_id[0]['id'];
        $this->modal_titulo_campo = $arr_campo_detalle_id[0]['cabecera'];
// dd($campo_detalle_id);
        // obtener primer objeto:
        $arr_params = []; 
        $sql = "SELECT fcm.campo_detalle_id,
                    fcm.formu__id,
                    fcm.fila,fcm.col,
                    fcm.valor,
                    ft.nombre tabla_nombre,
                    (case 
                        when locate('_@@@_',fcm.valor)<>0 then substring(fcm.valor,locate('_@@@_',fcm.valor)+5)
                    end) registro_tabla_id,
                    (case 
                        when locate('_@@@_',fcm.valor)<>0 then fdm.origen_datos
                    end) campos_mostrar
                FROM formu_contenidos_multivariables fcm
                    left join formu_tablas ft on ft.id=substring(valor,1,locate('_@@@_',valor)-1)
                    left join formu_detalles_multivariables fdm on (fdm.formu_detalles_id=fcm.campo_detalle_id and fdm.origen_tipo=2)
                where formu__id=:formu__id
                    and campo_detalle_id=:campo_detalle_id
                order by fcm.fila,fcm.col";
        $arr_params = [
            ':formu__id' =>  $producto_id, 
            ':campo_detalle_id' =>  $campo_detalle_id, 
        ];
        $obj_multivariables = collect(DB::select($sql , $arr_params));
// dd($obj_multivariables);

        if($obj_multivariables->count() == 0){
            $this->modal_arr_multivariable_cabeceras = [];
            $this->modal_arr_multivariable = [];
        }else{
            // 22sep2021:
            // Convertir el objeto en el array que será mostrado en el modal: 
            // Inicialmente se obtendrán las cabeceras que deben ser mostradas en la vista: 
            $this->modal_arr_multivariable_cabeceras = FormuDetallesMultivariable::select('cabecera')->where('formu_detalles_id' , $campo_detalle_id)->get()->toArray();
            // En cuanto a la info, primero se obtiene un array con fil y cols:
            $this->modal_arr_multivariable = [];
            foreach($obj_multivariables as $fila_multivariable){
                $aux_arr_multivariable = [];
                $es_tabla = strpos($fila_multivariable->valor , '_@@@_'); 
                if($es_tabla === false){
                    $aux_arr_multivariable = [
                        $fila_multivariable->fila,
                        $fila_multivariable->col,
                        $fila_multivariable->valor,
                    ];
                }else{
                    // obtener la lista de campos:
                    $lista_campos_ids = str_replace('_@@@_' , ' , ' , $fila_multivariable->campos_mostrar);
                    $arr_params2 = []; 
                    $sql2 = "select group_concat(nombre separator ' , ') campos 
                                from formu_campos 
                            where id in (:lista_campos_id) ";
                            $arr_params2 = [
                                ':lista_campos_id' =>  $lista_campos_ids, 
                            ];
                            $obj_lista_campos = collect(DB::select($sql2 , $arr_params2)); 
        
                            $lista_campos_nombres = $obj_lista_campos[0]->campos;         
                            // y ahora hacer el select a la tabla para hallar el valor definitivo:
                            $sql3 = "select " . $lista_campos_nombres . " info from " . $fila_multivariable->tabla_nombre . " where id = " . $fila_multivariable->registro_tabla_id;
                            $obj_valor_en_tabla = collect(DB::select($sql3));
                            $valor_en_tabla =  $obj_valor_en_tabla[0]->info;
        
                            $aux_arr_multivariable = [
                        $fila_multivariable->fila,
                        $fila_multivariable->col,
                        $valor_en_tabla,
                    ];

                }
                array_push($this->modal_arr_multivariable , $aux_arr_multivariable);
            }

            // Segundo: el array con fil y cols, debe ser pasado a la propiedad
            // pública que es mostrada en la vista: 
            $fila_ant =  $this->modal_arr_multivariable[0][0];
            $modal_arr_salida = []; 
            $aux_modal_arr_salida = []; 
            foreach($this->modal_arr_multivariable as $fila_ciclo){
                if($fila_ciclo[0] !== $fila_ant){
                    $fila_ant =  $fila_ciclo[0];
                    array_push($modal_arr_salida , $aux_modal_arr_salida);
                    $aux_modal_arr_salida = []; 
                }
                $aux_modal_arr_salida[$fila_ciclo[1]] = $fila_ciclo[2];
        }
        // 03ene2022:
        // en $aux_modal_arr_salida pueden haber quedado "huecos" en columnas,
        // por ejemplo, si falta que un produ llena info, asi se arreglan esos huecos:
        $aux_modal_arr_salida_completo = [];
        $comienzo_col = 0;
        foreach($aux_modal_arr_salida as $key => $valor){
            for ($i = $comienzo_col ; $i <  $key ; $i++) { 
                $aux_modal_arr_salida_completo[] = null;
            }
            $aux_modal_arr_salida_completo[] =$valor;
            $comienzo_col = $key + 1 ;
        }
        array_push($modal_arr_salida , $aux_modal_arr_salida_completo);
        $this->modal_arr_multivariable = $modal_arr_salida;
// dd($this->modal_arr_multivariable)        ;
    };
    $this->mostrar_multimodal_visible = true;             
}

    public function cerrar_modal_multivariable(){
        $this->mostrar_multimodal_visible = false;
    }  

    public function modificar_estado_producto($formu__id ,  $estado_actual , $codigo_producto){
        // 21oct2021: Llamada cuando un 'admin' dá click en columnAction 
        // 'ESTADO'. Lo que hace es actualizar en la tabla 
        // formu_contenidos_estados un nuevo estado que debe ser calculado 
        // a partir del $estado_actual que se recibe.
        // Eventualmente puede necesitar llamar el método modificar_estado_nucleo
        // Recibe: 
        //      $formu_id:  Es el id de la tabla formu__... (id del producto)
        //                  donde se dio click 
        //      $estado_actual: El estado actual del producto escogido, llega 
        //                  un nombre (el grabado en la tabla formu_estados)
        //      $codigo_producto: Para mostrar el código si se usa un diálogo modal.

        // 1) Determinar el nuevo estado: 
        // Lo primero es obtener el id del estado a partir de $estado_actual(el 
        // cual es el nombre del estado):
        $estado_actual_id = $this->arr_estados_id[$estado_actual];
        
        switch ($estado_actual_id) {
            case 1:
                // Tiene el estado "Por aprobar comercial. Sin produ y sin disen"
                // Automáticamente el estado pasa a Aprobado comercial:
                $this->modificar_estado_nucleo($formu__id , 8);
                break;

            case 2:
                // Tiene el estado "Por aprobar producción"
                // Mostrará al usuario un modal con 2 opciones de estados: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar comercial";
                $this->modal_estados_boton2 = "Aprobar producción";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 9;
                $this->modal_estados_nuevo_estado_id_boton2 = 7;
                $this->mostrar_cambio_estados_visible = true;                
                break;

            case 3:
                // Tiene el estado "Por aprobar diseño"
                // Mostrará al usuario un modal con 2 opciones de estados: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar comercial";
                $this->modal_estados_boton2 = "Aprobar diseño";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 10;
                $this->modal_estados_nuevo_estado_id_boton2 = 6;
                $this->mostrar_cambio_estados_visible = true;                
                break;
                
            case 6: 
                // Tiene el estado "Aprobado diseño", al dar click a este estado 
                // automáticamente debe quedar en "Por aprobar diseño" (3): 
                $this->modificar_estado_nucleo($formu__id , 3);
                break;

            case 7: 
                // El estado automáticamente será puesto en 2: Por aprobar producción
                $this->modificar_estado_nucleo($formu__id , 2);
                break; 

            case 8:
                // Tiene el estado "Aprobado comercial. Sin produ sin disen" al dar click a este estado 
                // automáticamente debe quedar en "Por aprobar comercial. Sin produ sin disen" (1): 
                $this->modificar_estado_nucleo($formu__id , 1);
                break;

            case 9:
                // Tiene el estado "Por aprobar comercial. Por digitar producción", 
                // Cuando un admin da click a este estado, antes de hacer nada hay 
                // que averiguar si el producto ya paso por el estado que indica que 
                // el rol 'produ' ya digitó info(es decir: averiguar históricos): 
                $arr_estados_columna = $this->averiguar_historicos($formu__id);
                if(in_array(2 , $arr_estados_columna)){
                    // Si en los "históricos" hay estado 2 significa que 'produ' ya llenó 
                    // la info, por eso 2 (Por aprobar producción.) debe ser el nuevo estado:
                    $this->modificar_estado_nucleo($formu__id , 2);
                }else{
                    // Si no hay históricos, significa que el rol 'produ' no ha 
                    // digitado info, por lo cual irá al estado 12 (Aprobado comercial. Por 
                    // digitar producción):
                    $this->modificar_estado_nucleo($formu__id , 12);
                }                    
                break;

            case 10:
                // Tiene el estado "Por aprobar comercial. Por digitar diseño", 
                // Cuando un admin da click a este estado, antes de hacer nada hay 
                // que averiguar si el producto ya paso por el estado que indica que 
                // el rol 'disen' ya digitó info (3) (es decir: averiguar históricos): 
                $arr_estados_columna = $this->averiguar_historicos($formu__id);
                if(in_array(3 , $arr_estados_columna)){
                    // Si en los "históricos" hay estado 3 significa que 'disen' ya llenó 
                    // la info, por eso 3 (Por aprobar diseño.) debe ser el nuevo estado:
                    $this->modificar_estado_nucleo($formu__id , 3);
                }else{
                    // Si no hay históricos, significa que el rol 'disen' no ha 
                    // digitado info, por lo cual irá al estado 13 (Aprobado comercial. Por 
                    // digitar diseño):
                    $this->modificar_estado_nucleo($formu__id , 13);
                }                    
                break;

            case 11:
                // Tiene el estado:  "Por aprobar comercial. Por digitar producción. Por digitar diseño"
                // Antes de hacer nada hay que averiguar si el producto ya paso 
                // por alguno de estos estados: 
                // a) El estado que indica que el rol 'produ' ya digitó info (22, )
                // b) El estado que indica que el rol 'disen' ya digitó info (23, )
                // c) El estado que indica que ambos roles ya digitaron info (24, )
                // d) El estado que indica que ambos roles habian sido aprobados (32, )
                // e) Si en históricos no está ninguno de estos estados, irá al estado 21
                $arr_estados_columna = $this->averiguar_historicos($formu__id);
                if(in_array(24 , $arr_estados_columna)){
                    // Si en los "históricos" hay estado 24 significa que tanto 'produ' 
                    // como 'disen' llenaron la info, por eso 24 debe ser el nuevo estado:
                    $this->modificar_estado_nucleo($formu__id , 24);
                }else if(in_array(22 , $arr_estados_columna)){
                    // Si en los "históricos" hay estado 23 significa que 'disen' ya llenó 
                    // la info, por eso 23 debe ser el nuevo estado:
                    $this->modificar_estado_nucleo($formu__id , 22);    
                }else if(in_array(23 , $arr_estados_columna)){
                    // Si en los "históricos" hay estado 22 significa que 'produ' ya llenó 
                    // la info, por eso 22 debe ser el nuevo estado:
                    $this->modificar_estado_nucleo($formu__id , 23);
                }else if(in_array(32 , $arr_estados_columna)){
                    // Si en los "históricos" hay estado 22 significa que 'produ' ya llenó 
                    // la info, por eso 22 debe ser el nuevo estado:
                    $this->modificar_estado_nucleo($formu__id , 32);
                }else{
                    // Si no hay históricos, significa que ni 'produ' ni 'disen' no han 
                    // digitado info, por lo cual irá al estado 21:
                    $this->modificar_estado_nucleo($formu__id , 21);
                }
                break;

            case 12:
                // Tiene el estado "Aprobado comercial. Por digitar producción"
                // Cuando un produ digite información pasará al estado 2.
                // Si es un admin que dá click sobre este estado, automáticamente debe volver
                // al estado 9.
                $this->modificar_estado_nucleo($formu__id , 9);
                break;

            case 13:
                // Tiene el estado "Aprobado comercial. Por digitar diseño"
                // Cuando un disen digite información pasará al estado 3.
                // Si es un admin que dá click sobre este estado, automáticamente debe volver
                // al estado 10.
                $this->modificar_estado_nucleo($formu__id , 10);
                break;
                
            case 21:
                // Tiene el estado "Aprobado comercial. Por digitar producción. Por digitar diseño"
                // Cuando un produ digite información pasará al estado 22.
                // Cuando un disen digite información pasará al estado 23.
                // Si es un admin que dá click sobre este estado, automáticamente debe volver
                // al estado 11.
                $this->modificar_estado_nucleo($formu__id , 11);
                break;

            case 22:
                // Tiene el estado "Por aprobar producción. Por digitar diseño"
                // Cuando un disen digite información pasará al estado 24.
                // Si es un admin que dá click sobre este estado: 
                //      El modal mostrará 2 botones (además del de cancelar): 
                //          Desaprobar comercial 
                //          Aprobar producción
                //      Por si el admin escoge Aprobar producción, hay que averiguar en los 
                //      históricos para determinar si debe ir al estado 25 o al 27
                //      Si según los históricos, disen ya digitó info: 
                //          Irá al estado 27
                //      sino: 
                //          Irá al estado 25
                //      fin-si
                // sino: 
                //     Proceso que se hará en el botón "Modificar" por un usuario disen.
                // fin-si
                
                // Averiguar históricos:
                $arr_estados_columna = $this->averiguar_historicos($formu__id);
                if(in_array(27 , $arr_estados_columna)){
                    // Si en los "históricos" hay estado 27 significa que 'disen' ya llenó 
                    // la info, por eso 27 debe ser el nuevo estado:
                    $nuevo_estado_por_historico = 27;
                }else{
                    // Si no hay históricos, significa que el rol 'disen' no ha 
                    // digitado info, por por eso 25 (Aprobado produ. Por 
                    // digitar diseño), deberá ser el nuevo estado,
                    $nuevo_estado_por_historico = 25;
                }  
                
                // Mostrará al usuario un modal con las opciones de estado detectatas: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar comercial";
                $this->modal_estados_boton2 = "Aprobar producción";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 11;
                $this->modal_estados_nuevo_estado_id_boton2 = $nuevo_estado_por_historico;
                $this->mostrar_cambio_estados_visible = true;                   
                break;

            case 23:
                // Tiene el estado "Por digitar producción. Por aprobar diseño"
                // Cuando un produ digite información pasará al estado 24.
                // Si es un admin que dá click sobre este estado: 
                //      El modal mostrará 2 botones (además del de cancelar): 
                //          Desaprobar comercial 
                //          Aprobar diseño
                //      Por si el admin escoge Aprobar diseño, hay que averiguar en los 
                //      históricos para determinar si debe ir al estado 26 o al 28
                //      Si según los históricos, produ ya digitó info: 
                //          Irá al estado 28
                //      sino: 
                //          Irá al estado 26
                //      fin-si
                // sino: 
                //     Proceso que se hará en el botón "Modificar" por un usuario produ.
                // fin-si
                
                // Averiguar históricos:
                $arr_estados_columna = $this->averiguar_historicos($formu__id);
                if(in_array(28 , $arr_estados_columna)){
                    // Si en los "históricos" hay estado 28 significa que 'produ' ya llenó 
                    // la info, por eso 28 debe ser el nuevo estado:
                    $nuevo_estado_por_historico = 28;
                }else{
                    // Si no hay históricos, significa que el rol 'produ' no ha 
                    // digitado info, por por eso 26 (Por digitar produ. Aprobado 
                    // diseño), deberá ser el nuevo estado,
                    $nuevo_estado_por_historico = 26;
                }  
                
                // Mostrará al usuario un modal con las opciones de estado detectatas: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar comercial";
                $this->modal_estados_boton2 = "Aprobar diseño";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 11;
                $this->modal_estados_nuevo_estado_id_boton2 = $nuevo_estado_por_historico;
                $this->mostrar_cambio_estados_visible = true;                   
                break;

            case 24: 
                // Tiene el estado "Por aprobar producción. Por aprobar diseño (A)"
                // Mostrará al usuario un modal con 3 opciones de estados: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar comercial";
                $this->modal_estados_boton2 = "Aprobar producción";
                $this->modal_estados_boton3 = "Aprobar diseño";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 11;
                $this->modal_estados_nuevo_estado_id_boton2 = 27;
                $this->modal_estados_nuevo_estado_id_boton3 = 28;
                $this->mostrar_cambio_estados_visible = true;                 
                break;                

            case 25:
                // Tiene el estado "Aprobado producción. Por digitar diseño" cuando 'admin' da 
                // click a este estado automáticamente debe devolverse al estado 22: 
                $this->modificar_estado_nucleo($formu__id , 22);
                break;

            case 26:
                // Tiene el estado "Por digitar producción. Aprobado diseño", cuando 'admin' da 
                // click a este estado automáticamente debe devolverse al estado 23: 
                $this->modificar_estado_nucleo($formu__id , 23);
                break;

            case 27:
                // Tiene el estado "Aprobado producción. Por aprobar diseño (A)"
                // Mostrará al usuario un modal con 2 opciones de estados: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar producción";
                $this->modal_estados_boton2 = "Aprobar diseño";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 22;
                $this->modal_estados_nuevo_estado_id_boton2 = 29;
                $this->mostrar_cambio_estados_visible = true;                
                break;                
                
            case 28:
                // Tiene el estado "Por aprobar producción. Aprobado diseño (A)"
                // Mostrará al usuario un modal con 2 opciones de estados: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar diseño";
                $this->modal_estados_boton2 = "Aprobar producción";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 23;
                $this->modal_estados_nuevo_estado_id_boton2 = 29;
                $this->mostrar_cambio_estados_visible = true;                
                break;                
               
            case 29:
                // Tiene el estado "Aprobado producción. Aprobado diseño"
                // Mostrará al usuario un modal con 2 opciones de estados: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar producción";
                $this->modal_estados_boton2 = "Desaprobar diseño";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 30;
                $this->modal_estados_nuevo_estado_id_boton2 = 31;
                $this->mostrar_cambio_estados_visible = true;                
                break;    

            case 30: 
                // Tiene el estado "Por Aprobar producción. Aprobado diseño (B)"
                // Mostrará al usuario un modal con 2 opciones de estados: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar diseño";
                $this->modal_estados_boton2 = "Aprobar producción";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 32;
                $this->modal_estados_nuevo_estado_id_boton2 = 29;
                $this->mostrar_cambio_estados_visible = true;                 
                break;

            case 31: 
                // Tiene el estado "Aprobado producción. Por aprobar diseño (B)"
                // Mostrará al usuario un modal con 2 opciones de estados: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar producción";
                $this->modal_estados_boton2 = "Aprobar diseño";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 32;
                $this->modal_estados_nuevo_estado_id_boton2 = 29;
                $this->mostrar_cambio_estados_visible = true;                 
                break;

            case 32: 
                // Tiene el estado "Por aprobar producción. Por aprobar diseño (B)"
                // Mostrará al usuario un modal con 3 opciones de estados: 
                // (Nota: el modal continuará con el flujo del programa)
                $this->modal_estados_codigo_producto = $codigo_producto;
                $this->modal_estados_boton1 = "Desaprobar comercial";
                $this->modal_estados_boton2 = "Aprobar producción";
                $this->modal_estados_boton3 = "Aprobar diseño";
                $this->modal_estados_formu__id = $formu__id;
                $this->modal_estados_nuevo_estado_id_boton1 = 11;
                $this->modal_estados_nuevo_estado_id_boton2 = 31;
                $this->modal_estados_nuevo_estado_id_boton3 = 30;
                $this->mostrar_cambio_estados_visible = true;                 
                break;

            default:
                # code...
                break;
// ====================================================
// case 24:
//     // Tiene el estado "Por aprobar producción y diseño"
//     // Mostrará al usuario un modal con 2 opciones de estados: 
//     $this->modal_estados_codigo_producto = $codigo_producto;
//     $this->modal_estados_boton1 = "Desaprobar comercial";
//     $this->modal_estados_boton2 = "Aprobar producción y diseño";
//     $this->modal_estados_formu__id = $formu__id;
//     $this->modal_estados_nuevo_estado_id_boton1 = 1;
//     $this->modal_estados_nuevo_estado_id_boton2 = 7;
//     $this->mostrar_cambio_estados_visible = true;                
//     break;
// case 29: 
//     // Tiene el estado "Aprobado producción y diseño", al dar click a este estado 
//     // automáticamente debe quedar en "Por aprobar diseño" (4): 
//     $this->modificar_estado_nucleo($formu__id , 4);
//     break;

// =============================================================



        }
        // por la invocación al método modificar_estado_nucleo(), el flujo del 
        // programa regresará al gridview de productos
    } 

    
    
    public function modificar_estado_nucleo($formu__id , $estado_nuevo_id){
        // llamado desde el método modificar_estado_producto(),o desde el  modal 
        // que muestra opciones antes de cambiar un estado
        // Recibe dos parámetros: 
        //      $formu__id:  Es el id de la tabla formu__... (id del producto)
        //                  al que se le cambiará el estado 
        //      $estado_nuevo_id: El id del nuevo estado que debe ser insertado en 
        //                        la tabla formu_contenidos_estados

        // 1) Poner todos los campos tiempo_estado (históricos) en cero: 
        $obj_contenidos_estados = new FormuContenidosEstado();             
        $obj_contenidos_estados->tiempos_a_cero($this->tipo_producto_id , $formu__id);

        // 2) agregar el nuevo estado a la tabla formu_contenidos_estados
        FormuContenidosEstado::create([
            'formu_tipo_producto_id' => $this->tipo_producto_id,
            'formu__id' => $formu__id,
            'formu_estado_id' => $estado_nuevo_id,    
            'tiempo_estado' => 1,
            'fec_rgto' => date('Y-m-d h:i:s'),
            'user_id' => Auth::user()->id,
        ]);     
        
        // por si de pronto fue llamada desde el modal cambio estados: 
        $this->mostrar_cambio_estados_visible = false;

        // el flujo regresará al gridview de productos
    }

    public function cancelar_modal_estados(){
        $this->mostrar_cambio_estados_visible = false;
        $this->modal_estados_boton3 = "";
    }
    
    public function permisos_editar_anular($rol , $estado_actual){
        // 01nov2021:  
        // Recibe un rol (admin,comer, produ o disen) y estado actual y en base a ellos 
        // retorna si se podrán activar o no en el gridview productos, los botones editar y anular: 

        // admin mostrará los dos botones SIEMPRE.
        // comer solo los mostrará cuando $estado_actual sea 1,9,10,11
        // produ solo los mostrará cuando $estado_actual sea: 
        //      2, 12, 21, 22, 23, 24, 26, 28 ,30 , 32
        // disen solo los mostrará cuando $estado_actual sea: 
        //      3, 13, 21, 22, 23, 24, 25, 27 , 31 , 32
        $resultado = false;
        if($rol == 'admin'){
            $resultado = true;
        }else if ($rol == 'comer' 
                && ($estado_actual == 1
                    || $estado_actual == 9
                    || $estado_actual == 10
                    || $estado_actual == 11)){
            $resultado = true;
        }else if($rol == 'produ' 
                && ($estado_actual == 2
                    || $estado_actual == 12
                    || $estado_actual == 21
                    || $estado_actual == 22
                    || $estado_actual == 23
                    || $estado_actual == 24
                    || $estado_actual == 26
                    || $estado_actual == 28
                    || $estado_actual == 30
                    || $estado_actual == 32)){
            $resultado = true;
        }else if($rol == 'disen' 
                && ($estado_actual == 3
                    || $estado_actual == 13
                    || $estado_actual == 21
                    || $estado_actual == 22
                    || $estado_actual == 23
                    || $estado_actual == 24
                    || $estado_actual == 25
                    || $estado_actual == 27
                    || $estado_actual == 31
                    || $estado_actual == 32)){
            $resultado = true;
        }
        return $resultado;
    }

    private function obtener_html_elemento_id($tipo_producto_id , $key){
        // retorna el tipo html_elemento_id del campo que llega en $key:
        $arr_params = [];
        $sql = "select fd.html_elemento_id 
        from formu_detalles fd 
            left join formu_tipo_productos ftp on ftp.id = fd.tipo_producto_id 
        where fd.tipo_producto_id = :tipo_producto_id and fd.slug = :formu__campo ";
        $arr_params = [
            'tipo_producto_id' =>  $tipo_producto_id, 
            'formu__campo' => $key ,
        ];
        $coll_html_elemento_id = collect(DB::select($sql , $arr_params));
        if(count($coll_html_elemento_id) == 0){
            return 0; 
        }else{
            return $coll_html_elemento_id[0]->html_elemento_id;
        }
    }    
    
    private function averiguar_historicos($formu__id){
        // llamada desde algunos case de modificar_estado_producto:
            $arr_params = []; 
            $sql = "select formu_estado_id 
                        from formu_contenidos_estados 
                        where formu_tipo_producto_id = :tipo_producto_id
                            and formu__id = :formu__id";
            $arr_params = [
                ':tipo_producto_id' =>  $this->tipo_producto_id, 
                ':formu__id' =>  $formu__id, 
            ];
            $arr_estados_historicos = collect(DB::select($sql , $arr_params))->toArray();
            return array_column($arr_estados_historicos , 'formu_estado_id'); 

            // 12nov2021: Comentariado para ver si es la "culpable" de la lentitud
            // al dar click a los botones del modal:
            // $arr_estados_historicos = FormuContenidosEstado::select('formu_estado_id')
            //     ->where('formu_tipo_producto_id' , $this->tipo_producto_id)
            //     ->where('formu__id' , $formu__id)
            //     ->get()->toArray();
    }     
    
    
// dd($obj_multivariables);    

   

}
