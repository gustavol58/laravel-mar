<?php

namespace App\Http\Livewire\Pedidos\Formu;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

use App\Http\Livewire\Pedidos\ConfigFormu\ConfigIndex;
use App\Models\Pedidos\FormuTipoProducto;
use App\Models\Pedidos\FormuDetalle;
use App\Models\Pedidos\FormuListaValore;
use App\Models\Pedidos\FormuCasillasEscogida;
// 10sep2021:  Unificación de CrearFormu y CrearMultivariale
use App\Models\Pedidos\FormuDetallesMultivariable;
use App\Models\Pedidos\FormuContenidosMultivariable;
use App\Models\Pedidos\FormuContenidosEstado;
use App\Models\Pedidos\FormuCampo;

class ModificarFormu extends Component
{
    use WithFileUploads;

    public $formu__id;
    public $tipo_producto_recibido_id;
    public $tipo_producto_recibido_nombre;
    public $tipo_producto_recibido_slug;
    public $tipo_producto_recibido_prefijo;

    public $mensaje_correcto;
    public $mensaje_error;

    // array  que tiene los valores ACTUALES del registro que se va a editar:
    public $arr_input_campos = [];

    // 25oct2021: Array para determinar el conse de una lista de valores,a partir
    // del id que esté grabado en formu__..... (llenado en mount())
    public $arr_lista_valores_conse;    

    // =======================================================================
    //      Propiedades para los campos subir archivo
    // =======================================================================    
    // Para validar los 
    // formatos de archivos permitidos para subir:
    public $arr_validaciones_subir_archivos = [];
    // Para cuando el usuario elimina un archivo (input type file)
    public $contador_eliminar_adjunto = 1;
    // Visibilidad de modal para cuando el usuario decida cambiar un archivo:
    public $modal_visible_cambiar_archivo = false;
    // Para mostrar la cabecera del campo a cambiar en el diálogo modal:
    public $cabecera_campo_para_modal_cambiar_archivo;
    // Para guardar el slug del campo a cambiar en el diálogo modal:
    public $slug_campo_para_modal_cambiar_archivo;
// nombre del input type file que será pedido en el diálogo modal:
// public $archivo_nuevo;

    // =======================================================================
    //      Propiedades para los campos multivariables
    // =======================================================================
    public $modal_visible_info_multivariable;
    public $modal_visible_cancelar;    
    // En el siguiente array se guardan los datos de cada campo multivariable, 
    // adicionalmente, también se graba la info que actualice el usuario,
    // cada fila contiene 4 columnas: campo_id , fila, col, valor
    public $arr_multivbles_input_todos = [];  

    // En este array se lleva la cuenta de cuantas filas con info tiene cada campo multivariable,
    // cada fila tiene 2 columnas: campo_id , canti_filas
    public $arr_multivbles_canti_filas = []; 

    // Con los siguientes objeto y array, se podrá guardar lo que digite el usuario para 
    // un campo dado en el diálogo modal de un campo multivariable. La info digitada
    // será guardada en el array.
    // Cuando el usuario dé click al botón para grabar el 
    // diálogo modal, se harán varia cosas: Una es pasar la info de este array  
    // a $arr_multivbles_input_todos (que es el que interactua con la b.d.), y 
    // la otra es borrar la info de estos objetos y array, al cerrar el modal:
    public $obj_multivbles_input_1campo = []; 
    public $arr_multivbles_input_1campo = []; 

    // Para guardar min filas, max filas del campo escogido:
    public $filas_min_1campo;
    public $filas_max_1campo;

    // Para guardar la configuracion de las columnas del multivariable:
    public $obj_formu_detalles_multivble_1campo;

    // para guardar la cantidad de filas del campo escogido:
    public $cantidad_filas_1campo;

    // para guardar la info de columnas del multivariable que sean 
    // del tipo: Lista desde tablas:
    public $arr_lista_tabla_multivariable = [];

    // =======================================================================
    //      Fin de propiedades para los campos multivariables
    // =======================================================================

    // rules para los input del formulario crear producto
    public $arr_rules = [];
    protected function rules(){
        return $this->arr_rules;
    } 

    public $arr_messages ;
    protected function messages(){
        return $this->arr_messages;
    }  

    public function mount($formu__id , $tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug , $tipo_producto_recibido_prefijo = null){
        // =======================================================================================
        // Se harán estas definiciones de variables que tienen que ver con subir archivos, 
        // se ponen acá soloa para que queden juntas al principio del método
        // =======================================================================================
        $arr_mimetypes_img =  [
            'image/jpeg' , 'image/png' , 'image/gif' , 'image/bmp' , 'image/tiff' , 
            'image/webp' , 'image/x-icon' , 'image/x-pict' , 'image/x-portable-anymap' , 
            'image/x-portable-bitmap' , 'image/x-rgb' , 'image/x-tga' , 'image/svg+xml'
        ];

        $arr_mimetypes_pdf = [
            'application/pdf'
        ];  

        $arr_mimetypes_zip = [
            'application/x-bzip' , 'application/x-bzip2' , 'application/zip' , 
            'application/epub+zip' , 'application/x-rar-compressed' , 
            'application/vnd.ms-cab-compressed' , 'application/x-7z-compressed' 
        ];  

        $arr_mimetypes_ods = [
            'application/vnd.oasis.opendocument.chart' , 
            'application/vnd.oasis.opendocument.chart-template' ,  
            'application/vnd.oasis.opendocument.database' , 
            'application/vnd.oasis.opendocument.formula' , 
            'application/vnd.oasis.opendocument.formula-template' , 
            'application/vnd.oasis.opendocument.graphics' , 
            'application/vnd.oasis.opendocument.graphics-template' ,  
            'application/vnd.oasis.opendocument.image' , 
            'application/vnd.oasis.opendocument.image-template' ,
            'application/vnd.oasis.opendocument.presentation' ,
            'application/vnd.oasis.opendocument.presentation-template' ,
            'application/vnd.oasis.opendocument.spreadsheet' ,
            'application/vnd.oasis.opendocument.spreadsheet-template' ,
            'application/vnd.oasis.opendocument.text' ,
            'application/vnd.oasis.opendocument.text-master' ,
            'application/vnd.oasis.opendocument.text-template' ,
            'application/vnd.oasis.opendocument.text-web' ,

            'application/vnd.openxmlformats-officedocument.presentationml.presentation' ,
            'application/vnd.openxmlformats-officedocument.presentationml.slide' ,
            'application/vnd.openxmlformats-officedocument.presentationml.slideshow' ,
            'application/vnd.openxmlformats-officedocument.presentationml.template' ,
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ,
            'application/vnd.openxmlformats-officedocument.spreadsheetml.template' ,
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ,
            'application/vnd.openxmlformats-officedocument.wordprocessingml.template' ,

            'application/vnd.ms-excel' ,
            'application/vnd.ms-powerpoint' ,
            'application/msword' ,
            
        ];  

        $arr_mimetypes_med = [
            'audio/adpcm' , 'audio/basic' , 'audio/midi' , 'audio/mp4' ,
            'audio/mpeg' , 'audio/ogg' , 'audio/s3m' , 'audio/silk' ,
            'audio/vnd.dece.audio' , 'audio/vnd.digital-winds' , 
            'audio/vnd.dra' , 'audio/vnd.dts' ,
            'audio/vnd.dts.hd' , 'audio/vnd.lucent.voice' , 
            'audio/vnd.ms-playready.media.pya' , 'audio/vnd.nuera.ecelp4800' ,
            'audio/vnd.nuera.ecelp7470' , 'audio/vnd.nuera.ecelp9600' , 'audio/vnd.rip' , 
            'audio/webm' , 'audio/x-aac' , 'audio/x-aiff' , 'audio/x-caf' ,
            'audio/x-flac' , 'audio/x-matroska' , 'audio/x-mpegurl' , 'audio/x-ms-wax' ,
            'audio/x-ms-wma' , 'audio/x-pn-realaudio' , 'audio/x-pn-realaudio-plugin' , 
            'audio/x-wav' , 'audio/xm' , 

            'video/3gpp' , 'video/3gpp2' , 'video/h261' , 'video/h263' , 
            'video/h264' , 'video/jpeg' , 'video/jpm' , 'video/mj2' , 
            'video/mp4' , 'video/mpeg' , 'video/ogg' , 'video/quicktime' , 
            'video/vnd.dece.hd' , 'video/vnd.dece.mobile' , 
            'video/vnd.dece.pd' , 'video/vnd.dece.sd' , 
            'video/vnd.dece.video' , 'video/vnd.dvb.file' , 
            'video/vnd.fvt' , 'video/vnd.mpegurl' , 
            'video/vnd.ms-playready.media.pyv' , 'video/vnd.uvvu.mp4' , 
            'video/vnd.vivo' , 'video/webm' , 'video/x-f4v' , 'video/x-fli' ,
            'video/x-flv' , 'video/x-m4v' , 'video/x-matroska' , 'video/x-mng' ,
            'video/x-ms-asf' , 'video/x-ms-vob' , 'video/x-ms-wm' , 'video/x-ms-wmv' ,
            'video/x-ms-wmx' , 'video/x-ms-wvx' , 'video/x-msvideo' , 
            'video/x-sgi-movie' , 'video/x-smv' ,
        ];    

        $arr_mimetypes_txt = [
            'text/cache-manifest' , 'text/calendar' , 'text/css' , 'text/csv' ,
            'text/html' , 'text/n3' , 'text/plain' , 'text/prs.lines.tag' ,
            'text/richtext' , 'text/sgml' , 'text/tab-separated-values' , 'text/troff' ,
            'text/turtle' , 'text/uri-list' , 'text/vcard' , 'text/vnd.curl' ,
            'text/vnd.curl.dcurl' , 'text/vnd.curl.mcurl' , 
            'text/vnd.curl.scurl' , 'text/vnd.dvb.subtitle' ,
            'text/vnd.fly' , 'text/vnd.fmi.flexstor' , 
            'text/vnd.graphviz' , 'text/vnd.in3d.3dml' ,
            'text/vnd.in3d.spot' , 'text/vnd.sun.j2me.app-descriptor' , 
            'text/vnd.wap.wml' , 'text/vnd.wap.wmlscript' ,
            'text/x-asm' , 'text/x-c' , 'text/x-fortran' , 'text/x-java-source' ,
            'text/x-nfo' , 'text/x-opml' , 'text/x-pascal' , 'text/x-setext' ,
            'text/x-sfv' , 'text/x-uuencode' , 'text/x-vcalendar' , 'text/x-vcard' ,

        ];    

        $this->arr_validaciones_subir_archivos = [
            'img' => $arr_mimetypes_img,
            'pdf' => $arr_mimetypes_pdf,
            'zip' => $arr_mimetypes_zip,
            'ods' => $arr_mimetypes_ods,
            'med' => $arr_mimetypes_med,
            'txt' => $arr_mimetypes_txt,
        ];  
        // =======================================================================================
        // Fin de variables que tienen que ver con subir archivos 
        // =======================================================================================        

        // =======================================================================================
        // Recibir los parámetros
        // =======================================================================================        
        $this->formu__id = $formu__id;  // el id del producto a modificar.
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->tipo_producto_recibido_prefijo = $tipo_producto_recibido_prefijo; 

        // tabla de productos:
        $tabla_formu = "formu__" . $this->tipo_producto_recibido_slug;

        // =======================================================================================
        // Obtener toda la info del producto que va a modificar:
        // =======================================================================================        
        $arr_params = [];
        $sql = "select * 
            from " . $tabla_formu . " 
            where id = :formu__id";
        $arr_params = [
            ':formu__id' =>  $formu__id,
        ];
        $obj_registro_formu__original_aux = (DB::select($sql , $arr_params));
        $this->arr_input_campos = (array)$obj_registro_formu__original_aux[0];
        // 21nov2021: Mas adelante, al fin de este método se tendrá que hacer esto:
        // a) Cambiar en $this->arr_input_campos los id de los campos: lista desde valores, 
        //    radio button y casillas, por sus CONSE correspondientes
        // b) Llevar el archivo (si existe elemento tipo subir archivo) a 
        //    la propiedad $this->arr_input_campos.

        // =======================================================================================
        // Llenar los arrays para validación del lado cliente: arr_rules y arr_messages:
        // =======================================================================================        
        $campos = FormuDetalle::select('slug' , 
                'id' ,
                'html_elemento_id' , 
                'roles' , 
                'obligatorio' , 
                'max_largo' ,
                'min_num' ,
                'max_num' ,
                'lista_tipos_archivos' ,
            )
            ->where('tipo_producto_id', $this->tipo_producto_recibido_id )
            ->where('html_elemento_id', '<>' , 8 )
            ->orderBy('orden')->get();  

        foreach($campos as $fila){
            $this->arr_rules['arr_input_campos.' . $fila->slug] = [];

            // 06oct2021: obligatoriedad (Que también depende del rol):
            $es_rol_permitido = false;
            $arr_aux_roles = explode('_@@@_' , $fila->roles);
            if(Auth::user()->hasRole($arr_aux_roles)){
                $es_rol_permitido = true;
            }            
            if($fila->obligatorio && $es_rol_permitido){
                // 16oct2021: Una última verificación antes de agregar el 'required' y 
                // solo tiene que ver con los campos multivariable:
                // Recordar que al formulario para crear productos solamente 
                // acceden dos roles: admin y comer
                // si el usuario es comer y en el multivariable correspondiente al 
                // detalle_id que se está recorriendo en esta iteración del ciclo,
                // no existe al menos una columna con rol 'comer' permitido, dicho 
                // multivariable no debe tener la RULE 'required', 
                // para que no salga el error descrito en la bitácora 26sep2021.

                if(Auth::user()->hasRole('comer')){
                    if($fila->html_elemento_id == 12){
                        // verificar que el campo multivariable tenga columnas 'comer': 
                        $arr_para_nro_rgtos = FormuDetallesMultivariable::where('formu_detalles_id' , $fila->id)->where('roles' , 'like' , '%comer%' )->get()->toArray();
                        if(count($arr_para_nro_rgtos) == 0){
                            // es un usuario comer y el multivariable no 
                            // tiene ninguna columna comer, NO se puede
                            // agregar la RULE 'required'
                        }else{
                            // es un usuario comer y el multivariable tiene al menos 
                            // una columna comer, puede agregar la RULE:
                            array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'required');
                            $this->arr_messages['arr_input_campos.' . $fila->slug . '.required'] = 'Este campo no puede estar en blanco.';
                        }
                    }else{
                        // es un elemento diferente a campo multivariable, puede agregar la RULE:
                        array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'required');
                        $this->arr_messages['arr_input_campos.' . $fila->slug . '.required'] = 'Este campo no puede estar en blanco.';
                    }
                }else{
                    // es un usuario admin, puede agregar la RULE:
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'required');
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.required'] = 'Este campo no puede estar en blanco.';
                }
            }

            // Las demás validaciones dependen del tipo de html_elemento_id, hay algunos 
            // (como las casillas y radio button), que 
            // la única validación que tienen es que sea obligatorio/opcional.
            switch ($fila->html_elemento_id) {
                case 1:
                    // input texto 
                    if($fila->max_largo !== null){
                        array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'max:' . $fila->max_largo);
                        $this->arr_messages['arr_input_campos.' . $fila->slug . '.max'] = 'La longitud máxima es de ' . $fila->max_largo . ' caracteres.';
                    }
                    break; 
                case 2: 
                    // número entero 
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'integer');
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.integer'] = 'Debe digitar un número entero';

                    if($fila->min_num !== null){
                        array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'min:' . $fila->min_num);
                        $this->arr_messages['arr_input_campos.' . $fila->slug . '.min'] = 'Debe digitar un número mayor o igual a  ' . $fila->min_num;
                    }
                    if($fila->max_num !== null){
                        array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'max:' . $fila->max_num);
                        $this->arr_messages['arr_input_campos.' . $fila->slug . '.max'] = 'Debe digitar un número menor o igual a  ' . $fila->max_num;
                    }
                    break;
                case 6: 
                    // email
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'email');
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.email'] = 'Debe digitar un formato de email correcto';
                    break;
                case 7: 
                    // fecha en formato aaaa-mm-dd
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'date');
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.date'] = 'Debe suministrar un formato de fecha correcto';
                    break;
                case 9: 
                    // número decimal
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'numeric');
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.numeric'] = 'Debe digitar un número entero o decimal';

                    if($fila->min_num !== null){
                        array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'min:' . $fila->min_num);
                        $this->arr_messages['arr_input_campos.' . $fila->slug . '.min'] = 'Debe digitar un número mayor o igual a  ' . $fila->min_num;
                    }
                    if($fila->max_num !== null){
                        array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'max:' . $fila->max_num);
                        $this->arr_messages['arr_input_campos.' . $fila->slug . '.max'] = 'Debe digitar un número menor o igual a  ' . $fila->max_num;
                    }
                    break;   
                case 11: 
                    // subir archivo:
                    if(!$fila->obligatorio){
                        array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'nullable');
                        $this->arr_messages['arr_input_campos.' . $fila->slug . '.nullable'] = 'Este campo no puede estar en blanco.';
                    }
           
                    // validación formato archivos: 
                    // Este es un funcionamiente CORRECTO usando mimes:
                            // array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'mimes:jpg,png'); 
                            // $this->arr_messages['arr_input_campos.' . $fila->slug . '.mimes'] = 'Solo se permiten imágenes png....';
                    // los formatos vienen especificado desde la tabla formu_detalles, en el 
                    // campo lista_tipos_archivo separados por _@@@_ (ejemplo: 
                    // ods_@@@_zip_@@@_txt)  
                    $cad_mimetypes = "mimetypes:";
                    $arr_formatos_archivo = explode('_@@@_' , $fila->lista_tipos_archivos);
                    foreach($arr_formatos_archivo as $ele){
                        foreach($this->arr_validaciones_subir_archivos[$ele] as $mimetype){
                            $cad_mimetypes = $cad_mimetypes . $mimetype . ',';
                        }
                    }
                    $cad_mimetypes = trim($cad_mimetypes , ',');
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , $cad_mimetypes); 
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.mimetypes'] = 'No se permite este formato de archivo...';

                    // validación size (megas): 
                    $max_kb = $fila->max_largo * 1024;
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'max:' . $max_kb);
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.max'] = 'El tamaño máximo del archivo debe ser: ' . $fila->max_largo . ' megas.';
                    break;
                default:
                    break;
            }
        }
        // fin del foreach para Llenar los arrays para validación 

        // =======================================================================================
        // Listas - Casillas - Radio button:
        // =======================================================================================        


// dd($this->arr_lista_valores_conse);
// dd($this->arr_input_campos);  

        // 25oct2021: 
        // a) Que en Listas desde valores aparezca seleccionado el valor 
        // actualmente grabado para el producto.
        // b) Que en las casillas aparezcan chequeadas las que el usuario
        // escogió al grabar el producto.
        // c) Que en los radio button, aparezca seleccionado el botón
        // que el usuario grabó al crear el producto.

        // Para lista desde valores y radio buttons: Crear array para determinar el conse de 
        // una lista de valores (sirve también para radio buttons),a partir 
        // del id que esté grabado en formu__..... :
        $this->arr_lista_valores_conse = [];
        $arr_lista_valores_conse_aux = FormuListaValore::get()->toArray();
        foreach($arr_lista_valores_conse_aux as $fila_){
            $this->arr_lista_valores_conse[$fila_['id']] = $fila_['conse'];
        }
        // Con el array que se acaba de obtener, se podrá indicar enseguida, en 
        // el $arr_input_campos,  cuál es la opción que se debe mostrar por defecto.

        // 21nov2021
        // Recorrer nuevamente $campos,esta vez para poder mostrar en la vista 
        // los valores actualmente grabados en lista desde valores, radio button 
        // y casillas, y ADICIONALMENTE para llevar el tipo subir archivo (si existe) a
        // $this->arr_input_campos:
// dd($this->arr_input_campos);        
        foreach($campos as $fila2){
            // La condición !== 0 es para no procesar los registros que tengan el
            // campo vacio (si se hace sale error runtime)
            if($fila2->html_elemento_id == 3 
                && $this->arr_input_campos[$fila2->slug] !== 0){
                // para obtener los valores actuales de un campo lista desde valores:
                $this->arr_input_campos[$fila2->slug] = $this->arr_lista_valores_conse[$this->arr_input_campos[$fila2->slug]];
            }
            if($fila2->html_elemento_id == 5
                && $this->arr_input_campos[$fila2->slug] !== 0){
                // para obtener el botón que se debe mostrar seleccionado en un radio button:
                $this->arr_input_campos[$fila2->slug] = $this->arr_lista_valores_conse[$this->arr_input_campos[$fila2->slug]];
            }
            if($fila2->html_elemento_id == 4){
                // 21nov2021: casillas
                // Lo que debe haber en $this->arr_input_campos, para campos tipo 'casillas' 
                // debe ser un array en el que estarán todas las casillas, las que fueron
                // marcadas por el usuario tendrán true, las demás false (NOTA: el conse
                // 0 siempre tendrá null en este nuevo array)
                // Por ejemplo: arr_input_campos.casillas_produ_8.1 true, 
                // arr_input_campos.casillas_produ_8.2 false,
                // arr_input_campos.casillas_produ_8.3 true,
                // arr_input_campos.casillas_produ_8.4 true 
                // generará este array:  [null,true,false,true,true]
                // En este ejemplo significa que el campo tenia 4 casilla y que 
                // habia marcado las casillas 1, 3 y 4 (dicha info está en 
                // la tabla formu_casillas_escogidas) 
                // Lo primero para obtener este array es esta mysql (para 
                // obtener TODAS las casillas del campo):
                $arr_params = [];
                $sql_casillas = "select id
                    from formu_lista_valores 
                    where formu__tabla=:formu__tabla
                        and formu__campo=:formu__campo 
                    order by id";
                $arr_params = [
                    ':formu__tabla' =>  $tabla_formu,
                    ':formu__campo' =>  $fila2->slug,
                ];
                $obj_casillas = DB::select($sql_casillas , $arr_params);

                // luego esta otra mysql (Para obtener las casillas que el 
                // usuario activó cuando creó el producto): 
                $arr_params_escogidas = [];
                $sql_casillas_escogidas = "SELECT formu_lista_valores_id 
                    FROM formu_casillas_escogidas 
                    where formu__campo_casilla=:escogidas_id ";
                $arr_params_escogidas = [
                    ':escogidas_id' =>  $this->arr_input_campos[$fila2->slug],
                ];
                $obj_casillas_escogidas = DB::select($sql_casillas_escogidas , $arr_params_escogidas);
                // $obj_casillas_escogidas es un array de objetos, hay que convertirlo 
                // en un array "array" (para luego poder usar in_array()):
                $arr_casillas_escogidas = [];
                foreach($obj_casillas_escogidas as $valor){
                    array_push($arr_casillas_escogidas , $valor->formu_lista_valores_id);
                }

                // y por último, armar el array y llevarlo a $this->arr_input_campos: 
                $arr_casillas_escogidas_aux = [];
                foreach($obj_casillas as $key => $valor){
                    if($key == 0){
                        array_push($arr_casillas_escogidas_aux , null);
                    }else{
                        if(in_array($valor->id , $arr_casillas_escogidas)){
                            array_push($arr_casillas_escogidas_aux , true);

                        }else{
                            array_push($arr_casillas_escogidas_aux , false);

                        }
                    }
                }
                $this->arr_input_campos[$fila2->slug] = $arr_casillas_escogidas_aux;
            }
            if($fila2->html_elemento_id == 11){
                // elemento tipo subir archivo: 
                // verificar si el producto tiene archivo asignado en la carpeta storage,
                // nombre original (grabado en la b.d., solo es el nombre del archivo),
                // si es null, el producto no tiene archivo en el campo actual: 
                if($this->arr_input_campos[$fila2->slug] !== null){
                    $public_path = config('constantes.path_foto_compte').'/storage/';
                    $nombre_archivo = 'subidos_desde_formus/' . $this->tipo_producto_recibido_slug . '/' . $fila2->slug . '/' . $this->arr_input_campos['id'] . '_' . $this->arr_input_campos[$fila2->slug];
                    if (Storage::disk('public')->exists($nombre_archivo)){
                        // 07mar2021
                        // Las constantes laravel sirven para evitar enredos con los nombres 
                        // de carpeta en localhost o en hosting compartido:
                        // $public_path = config('constantes.path_foto_compte');
                        // $ruta = $public_path . '/' . $nombre_archivo;
                        $this->arr_input_campos[$fila2->slug] = $public_path . $nombre_archivo;

                // dd($this->arr_input_campos[$fila2->slug]) ;       
                    } 
                }
            }            
        }

        // =======================================================================================
        // Manejo de campos multivariables grabados en la b.d.:
        // =======================================================================================        
        // 11dic2021: 
        $this->modal_visible_info_multivariable = false;
        $this->modal_visible_cancelar = false;
        // Obtener la info de todos los campos multivariables del producto:
        $arr_params2 = [];
        $sql2 = "SELECT fcm.campo_detalle_id,
                fcm.fila,
                fcm.col,
                fcm.valor 
            FROM formu_contenidos_multivariables fcm
                left join formu_detalles fd on fd.id=fcm.campo_detalle_id
            where  fcm.formu__id=:formu__id 
                and fd.tipo_producto_id=:tipo_producto_id";
        $arr_params2 = [
            ':formu__id' =>  $formu__id,
            ':tipo_producto_id' =>  $this->tipo_producto_recibido_id,
        ];
        $obj_contenidos_multivariables = (DB::select($sql2 , $arr_params2));
        // en $obj_contenidos_multivariables se encuentran TODOS los campos 
        // multivariables que tenga el producto, y por cada campo, su información 
        // de contenido. Es un objeto de 4 columnas: 
        //      campo_detalle_id        el id del campo que encadena a formu_detalles
        //      fila                    matriz que tiene la info del multivariable
        //      col                     matriz que tiene la info del multivariable
        //      valor                   matriz que tiene la info del multivariable
// dd($obj_contenidos_multivariables);
        $this->arr_multivbles_input_todos = (array)$obj_contenidos_multivariables;
// dd($this->arr_multivbles_input_todos);

        // Para mostrar en los botones multivariables el número filas que tienen info: 
        $arr_params3 = [];
        $sql3 = "SELECT fcm.campo_detalle_id,
                max(fcm.fila)+1 canti_filas
            FROM formu_contenidos_multivariables fcm
                left join formu_detalles fd on fd.id=fcm.campo_detalle_id
            where  fcm.formu__id=:formu__id 
                and fd.tipo_producto_id=:tipo_producto_id 
            group by fcm.campo_detalle_id";
        $arr_params3 = [
            ':formu__id' =>  $formu__id,
            ':tipo_producto_id' =>  $this->tipo_producto_recibido_id,
        ];
        $obj_canti_filas = (DB::select($sql3 , $arr_params3));

        $this->arr_multivbles_canti_filas = [];
        foreach($obj_canti_filas as $un_obj_canti_filas){
            $aux_arr_ = [];
            $aux_arr_['campo_detalle_id'] = $un_obj_canti_filas->campo_detalle_id;
            $aux_arr_['canti_filas'] = $un_obj_canti_filas->canti_filas;
            array_push($this->arr_multivbles_canti_filas , $aux_arr_);
        }        
// echo "<pre>"; 
// print_r($this->arr_multivbles_input_todos);        
// dd($this->arr_multivbles_canti_filas);  
    }    
    
    public function render(){
// 18dic2021        
// echo "esto al ppio del render MODIFICAR:"        ;
// dd($this->arr_input_campos);        
        // Determinar los datos generales del tipo producto escogido, para mostrar en la vista: 
        $tipo_productos = FormuTipoProducto::select('titulo' , 'subtitulo' , 'columnas' , 'tipo_producto_slug')->where('id', $this->tipo_producto_recibido_id )->get();
        $tipo_producto = $tipo_productos[0]; 

        // Obtener desde formu_detalles los campos que hay que pedir al usuario:
        $elementos_html = FormuDetalle::select('id' , 'html_elemento_id' , 'cabecera' , 
            'slug' , 'roles' , 'obligatorio' , 'max_largo' , 'lista_datos' , 
            DB::raw("' ' as arr_para_combobox") ,
            DB::raw("' ' as arr_para_casillas") , 
            DB::raw("' ' as arr_para_radio"),  
            DB::raw("' ' as multi_sin_col_comer")  
        )->where('tipo_producto_id', $this->tipo_producto_recibido_id )->orderBy('orden')->get();
    
        // para llenar los combobox, casillas y radios que estén presentes en el formulario, 
        // se van a recorrer las filas de $elementos_html (que es una colección) y se
        // procesarán aquellas que correspondan a html_elemento_id iguales a 3, 10, 4 y 5: 
        
        // el siguiente obj es para llamar el método obtener_lista_desde_tabla
        $obj_config_index = new ConfigIndex();  
        
        $elementos_html->map(function($fila , $key) use($obj_config_index , $tipo_producto){
            if($fila->html_elemento_id == 3){
                // el origen es una lista de valores: 
                $fila->arr_para_combobox = FormuListaValore::select('conse' , 'texto')->where('formu__tabla', 'formu__'.$tipo_producto->tipo_producto_slug)->where('formu__campo' , $fila->slug)->where('conse' , '<>' , 0)->orderBy('conse')->get()->toArray();
            }else if($fila->html_elemento_id == 10){
                // el origen es una tabla: 
                $fila->arr_para_combobox = $obj_config_index->obtener_lista_desde_tabla($fila->lista_datos)->toArray();
            }else if($fila->html_elemento_id == 4){
                // origen: casillas: 
                $fila->arr_para_casillas = FormuListaValore::select('conse' , 'texto')->where('formu__tabla', 'formu__'.$tipo_producto->tipo_producto_slug)->where('formu__campo' , $fila->slug)->where('conse' , '<>' , 0)->orderBy('conse')->get()->toArray();
            }else if($fila->html_elemento_id == 5){
                // origen: radio button: 
                $fila->arr_para_radios = FormuListaValore::select('conse' , 'texto')->where('formu__tabla', 'formu__'.$tipo_producto->tipo_producto_slug)->where('formu__campo' , $fila->slug)->where('conse' , '<>' , 0)->orderBy('conse')->get()->toArray();
            }
        }); // fin de $elementos_html->map() para crear arrays para comboboxes

// dd($elementos_html);


// dd($this->arr_input_campos);


// echo \View::make('livewire.pedidos.formu.modificar-formu')->with(compact('tipo_producto' , 'elementos_html'))->__toString();

        // para llenar el combo de lista (propiedad pública) desde tablas del modal
        // multivariable que esté abierto:
        if($this->modal_visible_info_multivariable){
            $this->llenar_combos_listas_tablas_multivariable();
        }

        // recordar que además de los dos parámetros que se envian 
        // aquí, hay otros que llegan a la vista como propiedades públicas:
        return view(
            'livewire.pedidos.formu.modificar-formu' , 
            compact('tipo_producto' , 'elementos_html')
        );        
    }

    public function submit_formu_modificar(){
        dd('en submit_formu_modificar....');
    }
   
        
    // ====================================================================================
    //      Métodos para los campos tipo Subir archivos
    // ====================================================================================     
    public function cambiar_archivo($slug_campo , $cabecera_campo){
        // 19dic2021 
        // Hace visible el modal que pide un input type "file": ese modal 
        // llamará el método submit_cambiar_archivo si se da aceptar en él
        $this->cabecera_campo_para_modal_cambiar_archivo = $cabecera_campo;
        $this->slug_campo_para_modal_cambiar_archivo = $slug_campo;
        $this->modal_visible_cambiar_archivo = true;
    }
    
    public $cambiar_un_solo_archivo;
    public function submit_cambiar_archivo(){ 
        if($this->cambiar_un_solo_archivo == null){
            session()->flash('mensaje_cambiar_archivo', 'Por favor vuelva a dar click, o verifique que haya escogido un archivo.');
        }else{
            // graba en $this->arr_input_campos el objeto seleccionado por el usuario:
            $this->arr_input_campos[$this->slug_campo_para_modal_cambiar_archivo] = $this->cambiar_un_solo_archivo;
            $this->modal_visible_cambiar_archivo = false;            
        }     
    }

    public function cancelar_cambiar_archivo(){
        $this->modal_visible_cambiar_archivo = false;            
    }



    public function reset_archivo($slug_campo){
        // Llega aqui cuando el usuario presiona el botón X (eliminar) de un elemento
        // de subir archivo.
        // Para limpiar un input type file lo que se hace al llevarle null al input es 
        // que ese input se borra del DOM y se vuelve a crear, es por eso que hay que 
        // usar la propiedad $this->contador_eliminar_adjunto para que no hayan 
        // problemas de ids repetidos cuando se vuelva a crear el nuevo input type file.

        $this->arr_input_campos[$slug_campo] = null;
        $this->contador_eliminar_adjunto++;
    }
    // ====================================================================================
    //      FIN de los métodos para los campos tipo Subir archivos
    // ====================================================================================     

    // ====================================================================================
    //      Métodos para los campos multivariables
    // ====================================================================================     
    public $campo_detalle_id_un_multivble;
    public function llenar_multivbles($campo_detalle_id){
        // 13dic2021 
        // Llega aqui cuando el usuario, desde la modificación de un 
        // producto, dá click en el botón que corresponde 
        // a un campo multivariable.
        // Recibe el id del campo sobre el que se dió click.
        // Esto es lo que hace este método:
        // a) Convierte en propieda pública el id delcampo que es multivariable
        // b) llamar el método privado que llenará propiedades (ver su docu.)
        // c) Hace visible el modal multivariable para mostrar alli la
        // info obtenida en el punto a) 

        // a)
        $this->campo_detalle_id_un_multivble = $campo_detalle_id;
        // b)
        // 23dic2021 if($this->arr_multivbles_input_1campo == null){
            // el array se llena solo si está vacio (si no está vacio significa
            // que hace poco el usuario lo ha modificaco ya, entonces no puede
            // sobreescribir esas modificaciones que hace poco hizo el usuario)
            $this->leer_info_1campo($campo_detalle_id);
        // }
        // c)
        $this->modal_visible_info_multivariable = true; 
    }

    public function cerrar_modal_info_multivariable(){
        $this->modal_visible_info_multivariable = false;
    } 
    
    public function agregar_fila_multivariable(){
        $this->cantidad_filas_1campo++;
    }  
    
    public function eliminar_fila_multivariable($num_fila_multivariable){
        $this->cantidad_filas_1campo--;
        // Para borrar la fila(unset) y además la posición(array_values) 
        // en el arr_multivbles_input_1campo:
        unset($this->arr_multivbles_input_1campo[$num_fila_multivariable]);
        $this->arr_multivbles_input_1campo = array_values($this->arr_multivbles_input_1campo);
// dd($this->arr_input_campos_info_multivariable);        
    }     

    public function submit_info_multivariable(){
        // 21dic2021:
        // llamado al dar click al botón "Grabar datos" desde un modal multivariable 
        // Las rules de los multimodales no se usan con rules() sino directamente 
        // con $this->validate().

        // validación directa en caso de que al menos haya una fila para validar: 
        // 21dic2021: Lo primero es obtener la cantidad de columnas del array: 
        $cols_arr = 0;
        foreach($this->arr_multivbles_input_1campo[0] as $col){
            $cols_arr++;
        }

        if($this->cantidad_filas_1campo >= 1){
            for($fil_validar_multivariable = 0 ; $fil_validar_multivariable < $this->cantidad_filas_1campo ; $fil_validar_multivariable++){
                for($col_validar_multivariable = 0 ; $col_validar_multivariable < $cols_arr ; $col_validar_multivariable++){
                    // 03oct2021:
                    $arr_aux_roles = explode('_@@@_' , $this->obj_formu_detalles_multivble_1campo[$col_validar_multivariable]->roles);
                    if(Auth::user()->hasRole($arr_aux_roles)){
                        $arr_rules_multivariable['arr_multivbles_input_1campo.' . $fil_validar_multivariable . '.' . $col_validar_multivariable] = [];
                        array_push($arr_rules_multivariable['arr_multivbles_input_1campo.' . $fil_validar_multivariable . '.' . $col_validar_multivariable] , 'required');
                        $arr_messages_multivariable['arr_multivbles_input_1campo.' . $fil_validar_multivariable . '.' . $col_validar_multivariable . '.required'] = 'No pueden haber campos en blanco en los multivariables.';
                    }
                }
            }
            // 03oct2021: 
            // El siguiente if es para que si en algun modal de multivariable en el
            // cual no haya ninguna columna para comer, al dar grabar no salga el
            // error php "Undefined variable $arr_rules_multivariable":
            if(isset($arr_rules_multivariable)){
                $this->validate($arr_rules_multivariable , $arr_messages_multivariable);
            }
            
        }

        // Si la validación es correcta, llega aqui.
// dd($this->arr_multivbles_input_todos);
// dd($this->arr_multivbles_input_1campo);

        // Pasar $this->arr_multivbles_input_1campo a
        // $this->>arr_multivbles_input_todos
        foreach($this->arr_multivbles_input_todos as $key_todos => $fila_todos){
            if($fila_todos['campo_detalle_id'] == $this->campo_detalle_id_un_multivble){
                $contador_fila_todos =  $key_todos;                   
                foreach($this->arr_multivbles_input_1campo as $key_fila_modificado => $fila_modificado){
                    foreach($fila_modificado as $key_col_modificado => $valor_modificado){
                        $arr_aux=[
                            'campo_detalle_id' => $fila_todos["campo_detalle_id"],
                            'fila' => $key_fila_modificado,
                            'col' => $key_col_modificado,
                            'valor' => $valor_modificado,
                        ];
                        if($this->arr_multivbles_input_todos[$contador_fila_todos]['campo_detalle_id'] == $this->campo_detalle_id_un_multivble){
                            $this->arr_multivbles_input_todos[$contador_fila_todos] = $arr_aux;
                            $contador_fila_todos++;
                        }else{
                            array_push($this->arr_multivbles_input_todos , $arr_aux);
                        }
                    }
                }
                break;
            }
        }
        // al salir del foreach, ya se pasó toda la información 
        // desde $this->arr_multivbles_input_1campo
        // hasta $this->arr_multivbles_input_todos

// echo "<pre>";
// print_r($this->arr_multivbles_input_1campo);
// print_r($this->arr_multivbles_input_todos);
// dd('revisar');      




$this->reset('arr_multivbles_input_1campo');

        
// dd($this->arr_multivbles_input_todos);

// echo "<pre>";
// print_r($this->arr_multivbles_canti_filas);
// print_r($arr_col_campo_detalle);
// dd('revisar');


// Re-calcular el número de filas de cada cambo multivariable (por
// si el usuario agregó o eliminó filas en el último que modificó):
// $arr_col_campo_detalle = array_column($this->arr_multivbles_input_todos, 'campo_detalle_id');
// $acu = 0;
// foreach($arr_col_campo_detalle as $valor){
//     if($valor == $this->campo_detalle_id_un_multivble){
//         $acu++;
//     }
// }
// $acu = $acu / $cols_arr;
// foreach($this->arr_multivbles_canti_filas as $key => $fila){
//     if($fila['campo_detalle_id'] == $this->campo_detalle_id_un_multivble){
//         $this->arr_multivbles_canti_filas[$key]['canti_filas'] = $acu; 
//         break;
//     }
// } 

// echo "<pre>";
// print_r($this->arr_multivbles_canti_filas);
// print_r($arr_col_campo_detalle);
// dd('revisar');

// Re-calcular el número de filas de cada cambo multivariable (por
// si el usuario agregó o eliminó filas en el último que modificó):
// $arr_col_campo_detalle = array_column($this->arr_multivbles_input_todos, 'campo_detalle_id');
// if(isset($arr_canti_filas_aux)){
//     unset($arr_canti_filas_aux);
// }else{
//     $arr_canti_filas_aux = [];

// }
// // $arr_canti_filas_aux = [];
// foreach($arr_col_campo_detalle as $un_campo_detalle_id){
//     if(array_key_exists($un_campo_detalle_id , $arr_canti_filas_aux)){
//         $arr_canti_filas_aux[$un_campo_detalle_id] = $arr_canti_filas_aux[$un_campo_detalle_id] + 1;
//     }else{
//         $arr_canti_filas_aux[$un_campo_detalle_id] = 1;
//     }
// }
// dd($arr_canti_filas_aux);
// Borra y vuelve a llenar el array que tiene la cantidad
// de filas por cada campo multivariable:
// $this->arr_multivbles_canti_filas = [];
// foreach($arr_canti_filas_aux as $key => $valor){
//     $aux_arr_canti_filas = [];
//     $aux_arr_canti_filas['campo_detalle_id'] = $key;
//     $aux_arr_canti_filas['canti_filas'] = $valor / $cols_arr;
//     array_push($this->arr_multivbles_canti_filas , $aux_arr_canti_filas);
// }  

// dd($this->arr_multivbles_canti_filas);


$this->cerrar_modal_info_multivariable();




    }

    private function leer_info_1campo($campo_detalle_id){
// dd('en leer.....');        
        // 13dic2021 
        // Llamado por el método 
        // El parámetro que recibe es el id del campo al cual el usuario le dió click 
        // Este método llena las siguientes propiedades antes de hacer el render del 
        // diálogo modal:
        //    i) la propiedad $this->obj_multivbles_input_1campo (es
        // decir: la info del campo al cual le dió click el usuario,
        // si dicha info no está grabada en $this->arr_multivbles_input_todos 
        // entonces debe ser leida desde la tabla formu_contenidos_multivariables)
        //    ii) crear la propiedad $this->arr_multivbles_input_1campo a
        // partir de i)
        //    iii) la propiedad $this->obj_formu_detalles_multivble_1campo que
        // es la propiedad que tiene la configuración (cabecera, max filas, etc...
        // de cada columna)
        //    iv) la propiedad $this->$arr_multivbles_canti_filas, que es la
        // que se usará para controlar el número filas que el usuario agregue 
        // o elimine al modificar el multivariable.
        //    v) la propiedad $this->arr_lista_tabla_multivble, que es la 
        // que contienen la info de los tipos de columna que sean listas 
        // desde tablas. (aqui hace un llamado a otro método privado)
        //    vi) La propiedad $this->filas_max_1campo , para obtenerla se 
        // debe leer la tabla formu_detalles
        
        // i):
        // Determinar de dónde se debe tomar la info para $this->arr_multivbles_input_1campo: 
        if($this->arr_multivbles_input_todos == null){
            $arr_params4 = [];
            $sql4 = "SELECT fcm.fila,
                    fcm.col,
                    fcm.valor 
                FROM formu_contenidos_multivariables fcm
                    left join formu_detalles fd on fd.id=fcm.campo_detalle_id
                where  fcm.formu__id=:formu__id 
                    and fd.tipo_producto_id=:tipo_producto_id
                    and fcm.campo_detalle_id=:campo_detalle_id";
            $arr_params4 = [
                ':formu__id' =>  $this->formu__id,
                ':tipo_producto_id' =>  $this->tipo_producto_recibido_id,
                ':campo_detalle_id' =>  $campo_detalle_id,
            ];
            $this->obj_multivbles_input_1campo = (DB::select($sql4 , $arr_params4));
    
            // ii):
            // Convierte este objeto para que sea un array en donde se grabará lo 
            // que el usuario digite desde el diálogo modal:
            $this->arr_multivbles_input_1campo = [];
            foreach($this->obj_multivbles_input_1campo as $un_obj){
                $this->arr_multivbles_input_1campo[$un_obj->fila][$un_obj->col] = $un_obj->valor;
            }
// dd($this->arr_multivbles_input_1campo);
        }else{
            $this->arr_multivbles_input_1campo = [];
            foreach($this->arr_multivbles_input_todos as $un_obj){
                $this->arr_multivbles_input_1campo[$un_obj['fila']][$un_obj['col']] = $un_obj['valor'];
            }            
            // $this->arr_multivbles_input_1campo = 
// dd($this->arr_multivbles_input_1campo);
        }

        // iii):
        // Lee la configuración de cada columna del multivariable: 
        $this->obj_formu_detalles_multivble_1campo = FormuDetallesMultivariable::where('formu_detalles_id' , $campo_detalle_id)->get();
// dd($this->obj_formu_detalles_multivble_1campo);

        // iv):
        // Lleva a la propiedad correspondiente el número de filas que tiene el campo 
        // recibido como parámetro:
// dd($this->arr_multivbles_canti_filas);      
        foreach($this->arr_multivbles_canti_filas as $fila_canti){
            if($fila_canti['campo_detalle_id'] == $campo_detalle_id){
                $this->cantidad_filas_1campo = $fila_canti['canti_filas'];
                break;
            }
        }
// dd($this->cantidad_filas_1campo);        

        // v):
        // Llena la info de campos que sean tipo lista tablas: 
        $this->llenar_combos_listas_tablas_multivariable(); 
        
        // vi):
        $this->obj_un_campo_formu_detalles = FormuDetalle::find($campo_detalle_id);
        $this->filas_min_1campo = $this->obj_un_campo_formu_detalles->min_num;
        $this->filas_max_1campo = $this->obj_un_campo_formu_detalles->max_num;

    }

    private function llenar_combos_listas_tablas_multivariable(){
        // 14dic2021: 
        // Array para llenar los combo box de las listas de tablas de 
        // aquellas columnas de multivariable que sean de ese tipo:
// dd($this->obj_formu_detalles_multivble_1campo) ;  
// dd($this->arr_formu_detalles_multivble_1campo) ;  

        $this->arr_lista_tabla_multivariable = [];
        $obj_config_index_multivariable = new ConfigIndex(); 
// dd($this->arr_formu_detalles_multivariable);        
        foreach($this->obj_formu_detalles_multivble_1campo as $key_ => $fila_multivariable){
            if($fila_multivariable['origen_tipo'] == 2){
                $aux_arr_multivariable = $obj_config_index_multivariable->obtener_lista_desde_tabla($fila_multivariable['origen_datos'])->toArray();
// dd($aux_arr_multivariable);

                // 13sep2021: 
                // agregar al principio de la última columna de $this->arr_input_campos_info_multivariable,
                // la tabla_id (siempre y cuando se trate de una lista desde tablas para los demas 
                // valores será null), tabla_id viene de formu_campos
                foreach($aux_arr_multivariable as $fila_aux_arr => $fila_valor){
                    $origen_datos_procesar = $fila_multivariable['origen_datos'];
// echo "<pre><br><br>Origen datos procesar: <br>".$origen_datos_procesar;                    
                    $primer_componente = explode('_@@@_' , $origen_datos_procesar); 
// echo "<br><br>primercomponente: <br>".print_r($primer_componente);                    

                    $un_campo = $primer_componente[0];
// echo "<br><br>un_campo: <br>".$un_campo;                    

                    $rgto_formucampo = FormuCampo::find($un_campo);
// echo "<br><br>rgto_formucampo: <br>".print_r($rgto_formucampo);                                        
                    $tabla_id = $rgto_formucampo->tabla_id;
// echo "<br><br>tabla_id: <br>".$tabla_id;                    
                    $aux_arr_multivariable[$fila_aux_arr]->id = $tabla_id . '_@@@_' . $fila_valor->id;
            
                }
// dd($aux_arr_multivariable) ;   

                $this->arr_lista_tabla_multivariable[$key_] = $aux_arr_multivariable;
            }
        } 
// dd($this->arr_lista_tabla_multivariable) ;  
        
    }  
    // ====================================================================================
    //      FIN de los métodos para los campos multivariables
    // ====================================================================================       

}
