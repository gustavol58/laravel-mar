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
use App\Models\Pedidos\FormuEstado;
use App\Models\Pedidos\FormuContenidosEstado;
use App\Models\Pedidos\FormuCampo;


class CrearFormu extends Component
{
    // ==================================================================================
    // Para subir campos tipo archivo
    // ==================================================================================
    use WithFileUploads;

    // ==================================================================================
    // Propiedades para recibir parámetros
    // ==================================================================================
    public $tipo_producto_recibido_id;
    public $tipo_producto_recibido_nombre;
    public $tipo_producto_recibido_slug;
    public $tipo_producto_recibido_prefijo;
    // 24dic2021; nuevos parámetros operación y formu__id
    // 01ene2022: nuevo parámetro formu__estado_nombre, opcional, solo lo usa modificar,
    //            y formu__estado_codigo es un derivado del formu__estado_nombre, ambos
    //            se procesan en el mount() y formu__estado_codigo en el submit_formu()
    // 11feb2022: formu__codigo_producto: solo lo usa el blade cuando operacion='ver'
    public $operacion;
    public $formu__id;
    public $formu__estado_nombre;
    public $formu__estado_codigo;
    public $formu__codigo_producto;

    // ==================================================================================
    // Propiedades para validación de campos del formulario
    // ==================================================================================
    public $mensaje_correcto;
    public $mensaje_error;

    // ==================================================================================
    // Propiedad con la que se hacen los wire:model con los html (inputs y otros)
    // ==================================================================================
    public $arr_input_campos = [];

    // ==================================================================================
    // Propiedades para manejo de roles
    // ==================================================================================
    // 03nov2021: 
    // Propiedad que almacenará los roles que pertenecen al tipo de producto,con la
    // cual se podrán asignar los estados cuando un producto sea creado. Es binaria de 
    // 3 cifras: la primera corresponde a 'comer', la segunda a 'produ' y la tercera 
    // a 'disen' (Ej: 100: solo 'comer')
    public $roles_tipo_producto = '000';

    // ==================================================================================
    // Propiedades para campos tipo subir archivo
    // ==================================================================================
    // Usada solo si se utiliza el elemento 'subir archivo', para validar los 
    // formatos de archivos permitidos para subir:
    public $arr_validaciones_subir_archivos = [];

    // usada cuando el usuario elimina adjuntos (input type file)
    public $contador_eliminar_adjunto = 1;

    // ==================================================================================
    // Propiedades para campos multivariables
    // ==================================================================================
    public $modal_visible_info_multivariable;
    public $modal_visible_cancelar;
    public $detalle_id_multivariable;
    public $cabecera_multivariable;
    public $slug_multivariable;
    public $filas_min_multivariable;
    public $filas_max_multivariable;
    // filas_act: el número de filas actual que se están mostrando en el modal, 
    // pueden ser agregadas o disminuidas por el usuario comercial/producción:
    public $filas_act_multivariable;
    // para grabar los origen_tabla de cada columna 
    public $arr_lista_tabla_multivariable = [];
    // para grabar la info de la tabla formu_detalles_multivariable:
    public $arr_formu_detalles_multivariable ;
    // almacenará la info digitada por el usuario, para cada campo multivariable:
    public $arr_input_campos_info_multivariable = []; 
    // en el siguiente array se acumulan los datos de cada campo multivariable: 
    public $arr_todos_input_campos_info_multivariable = [];
    // y en este se lleva la cuenta de cuantas filas con info tiene cada campo multivariable:
    public $arr_todos_input_campos_info_multivariable_canti_filas; 
    // NOTA FINAL: Recordar que en multivariables no se usarán rules() para validar los input 
    // del modal, sino que se usará el $this->validate() 
    // directo (verlo implementado en el método submit_multivariable())    

    // ==================================================================================
    // Rules y messges, para los input del formulario
    // ==================================================================================
    public $arr_rules = [];
    protected function rules(){
        return $this->arr_rules;
    } 
    public $arr_messages ;
    protected function messages(){
        return $this->arr_messages;
    }  

    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug , $operacion , $tipo_producto_recibido_prefijo = null , $formu__id = null , $formu__codigo_producto = null , $formu__estado_nombre = null){
        // ==================================================================================
        // Recibir los parámetros 
        // ==================================================================================
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        // 24dic2021: nuevo parámetro operacion
        $this->operacion = $operacion; 
        $this->tipo_producto_recibido_prefijo = $tipo_producto_recibido_prefijo; 
        $this->formu__id = $formu__id; 
        $this->formu__estado_nombre = $formu__estado_nombre; 
        $this->formu__codigo_producto = $formu__codigo_producto; 
        // ==================================================================================
        // Determinar formu__estado_codigo:
        // ==================================================================================
        if($this->formu__estado_nombre !== null){
            // obtener array que contiene nombres - códigos de estado:
            $arr_estados_id = [];
            $arr_estados_id_aux = FormuEstado::get()->toArray();
            foreach($arr_estados_id_aux as $fila_){
                    $arr_estados_id[$fila_['nombre_estado']] = $fila_['id'];
            } 
            $this->formu__estado_codigo = $arr_estados_id[$this->formu__estado_nombre];
        }else{
            $this->formu__estado_codigo = null;
        }

        // ==================================================================================
        // Para que no hayan diálogos modales abiertos
        // ==================================================================================
        $this->modal_visible_info_multivariable = false;
        $this->modal_visible_cancelar = false;

        // ==================================================================================
        // Para validación de campos tipo subir archivo
        // ==================================================================================
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

        // ==================================================================================
        // Llenar los arrays para validación del lado cliente: arr_rules y arr_messages
        // ==================================================================================
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

            // 06oct2021: todas las rules que se agreguen también deben depender del rol):
            $es_rol_permitido = false;
            $arr_aux_roles = explode('_@@@_' , $fila->roles);
            if(Auth::user()->hasRole($arr_aux_roles)){
                $es_rol_permitido = true;
            }       

            if($fila->obligatorio && $es_rol_permitido){
                if($fila->html_elemento_id == 12){
                    // 16oct2021: Una última verificación antes de agregar el 'required' y 
                    // solo tiene que ver con los campos multivariable:
                    if($this->operacion == "crear"){
                        // Al formulario para crear productos solamente 
                        // acceden dos roles: admin y comer
                        // si el usuario es comer y en el multivariable correspondiente al 
                        // detalle_id que se está recorriendo en esta iteración del ciclo,
                        // no existe al menos una columna con rol 'comer' permitido, dicho 
                        // multivariable no debe tener la RULE 'required', 
                        // para que no salga el error descrito en la bitácora 26sep2021.
                        if(Auth::user()->hasRole('comer')){
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
                    }else if($this->operacion == 'modificar'){
                        // es un usuario admin, puede agregar la RULE:
                        array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'required');
                        $this->arr_messages['arr_input_campos.' . $fila->slug . '.required'] = 'Este campo no puede estar en blanco.';
                    }else if($this->operacion == 'ver'){
                        // si la operación es 'ver' no hay que manejar rules.
                    }
                }
// else{
//     // 02ene2021:
//     // En cambio al formulario de modificar acceden 4 roles (admin,
//     // comer, produ y disen)
//     // si el usuario es admin todas las columnas deben estar llenas.
//     // si el usuario es comer todas las columnas 'comer' deben estar llenas.
//     // si el usuario es produ todas las columnas 'produ' deben estar llenas.
//     // si el usuario es disen todas las columnas 'disen' deben estar llenas.
//     if(Auth::user()->hasRole('admin')){
//         array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'required');
//         $this->arr_messages['arr_input_campos.' . $fila->slug . '.required'] = 'Este campo no puede estar en blanco.';
//     }else if(Auth::user()->hasRole('comer')){
//         // verificar que el campo multivariable tenga columnas 'comer': 
//         $arr_para_nro_rgtos = FormuDetallesMultivariable::where('formu_detalles_id' , $fila->id)->where('roles' , 'like' , '%comer%' )->get()->toArray();
//         if(count($arr_para_nro_rgtos) == 0){
//             // es un usuario comer y el multivariable no 
//             // tiene ninguna columna comer, NO se puede
//             // agregar la RULE 'required'
//         }else{
//             // es un usuario comer y el multivariable tiene al menos 
//             // una columna comer, puede agregar la RULE:
//             array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'required');
//             $this->arr_messages['arr_input_campos.' . $fila->slug . '.required'] = 'Este campo no puede estar en blanco.';
//         }
//     }else if(Auth::user()->hasRole('produ')){
//         // verificar que el campo multivariable tenga columnas 'produ': 
//         $arr_para_nro_rgtos = FormuDetallesMultivariable::where('formu_detalles_id' , $fila->id)->where('roles' , 'like' , '%produ%' )->get()->toArray();
//         if(count($arr_para_nro_rgtos) == 0){
//             // es un usuario produ y el multivariable no 
//             // tiene ninguna columna produ, NO se puede
//             // agregar la RULE 'required'
//         }else{
//             // es un usuario produ y el multivariable tiene al menos 
//             // una columna produ, puede agregar la RULE:
//             array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'required');
//             $this->arr_messages['arr_input_campos.' . $fila->slug . '.required'] = 'Este campo no puede estar en blanco.';
//         }
//     }else if(Auth::user()->hasRole('disen')){
//         // verificar que el campo multivariable tenga columnas 'disen': 
//         $arr_para_nro_rgtos = FormuDetallesMultivariable::where('formu_detalles_id' , $fila->id)->where('roles' , 'like' , '%disen%' )->get()->toArray();
//         if(count($arr_para_nro_rgtos) == 0){
//             // es un usuario disen y el multivariable no 
//             // tiene ninguna columna disen, NO se puede
//             // agregar la RULE 'required'
//         }else{
//             // es un usuario disen y el multivariable tiene al menos 
//             // una columna disen, puede agregar la RULE:
//             array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'required');
//             $this->arr_messages['arr_input_campos.' . $fila->slug . '.required'] = 'Este campo no puede estar en blanco.';
//         }
//     }
// }
            }else{
                // es un elemento diferente a campo multivariable, puede agregar la RULE:
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
                if($fila->max_largo !== null && $es_rol_permitido){
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'max:' . $fila->max_largo);
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.max'] = 'La longitud máxima es de ' . $fila->max_largo . ' caracteres.';
                }
                break; 
            case 2: 
                // número entero
                if($es_rol_permitido){
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'integer');
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.integer'] = 'Debe digitar un número entero';
                }

                if($fila->min_num !== null && $es_rol_permitido){
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'min:' . $fila->min_num);
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.min'] = 'Debe digitar un número mayor o igual a  ' . $fila->min_num;
                }
                if($fila->max_num !== null && $es_rol_permitido){
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'max:' . $fila->max_num);
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.max'] = 'Debe digitar un número menor o igual a  ' . $fila->max_num;
                }
                break;
            case 6: 
                // email
                if($es_rol_permitido){
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'email');
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.email'] = 'Debe digitar un formato de email correcto';
                }
                break;
            case 7: 
                // fecha en formato aaaa-mm-dd
                if($es_rol_permitido){
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'date');
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.date'] = 'Debe suministrar un formato de fecha correcto';
                }
                break;
            case 9: 
                // número decimal
                if($es_rol_permitido){
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'numeric');
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.numeric'] = 'Debe digitar un número entero o decimal';
                }
                if($fila->min_num !== null && $es_rol_permitido){
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'min:' . $fila->min_num);
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.min'] = 'Debe digitar un número mayor o igual a  ' . $fila->min_num;
                }
                if($fila->max_num !== null && $es_rol_permitido){
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'max:' . $fila->max_num);
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.max'] = 'Debe digitar un número menor o igual a  ' . $fila->max_num;
                }
                break;   
            case 11: 
                // subir archivo:
                if(!$fila->obligatorio && $es_rol_permitido){
                    array_push($this->arr_rules['arr_input_campos.' . $fila->slug] , 'nullable');
                    $this->arr_messages['arr_input_campos.' . $fila->slug . '.nullable'] = 'Este campo no puede estar en blanco.';
                }

                if($es_rol_permitido){
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
                }
                break;
            default:
                break;
            }
        }
        // fin del foreach para Llenar los arrays para validación 


        // ==================================================================================
        // 24dic2021 Traer info del producto desde b.d. (si operacion es 'modificar' o 'ver')
        // ==================================================================================
        if($this->operacion == "modificar" || $this->operacion == "ver"){
            // debe llenar las propiedades que el usuario modificará con los inputs html:

            // 1) Obtener toda la info del producto que va a modificar:
            $tabla_formu = "formu__" . $this->tipo_producto_recibido_slug;
            $arr_params = [];
            $sql = "select * 
                from " . $tabla_formu . " 
                where id = :formu__id";
            $arr_params = [
                ':formu__id' =>  $formu__id,
            ];
            $obj_registro_formu__original_aux = (DB::select($sql , $arr_params));
            $this->arr_input_campos = (array)$obj_registro_formu__original_aux[0];
            // En este momento ya están la mayoria de campos listos para ser
            // mostrados: textos, números enteros y decimales, emails, fechas, listas 
            // desde tablas. Faltan los siguientes: Listas desde valores, casillas, 
            // radio buttons, subir archivo y multivariables.

            // 2) Para los que faltan:
            $tipo_productos = FormuTipoProducto::select('titulo' , 'subtitulo' , 'columnas' , 'tipo_producto_slug')
                ->where('id', $this->tipo_producto_recibido_id )
                ->get();
            $tipo_producto = $tipo_productos[0];   
            $elementos_html_para_modificar = FormuDetalle::select('id' , 'html_elemento_id' , 'cabecera' , 
                'slug' , 'roles' , 'obligatorio' , 'max_largo' , 'lista_datos' , 
            )->where('tipo_producto_id', $this->tipo_producto_recibido_id )->orderBy('orden')->get();

            // Las listas desde valores tienen en formu__..... el id que debe ser
            // buscado en la tabla formu_lista_valores para leer desde alli el CONSE.
            // Es similar el proceso para radio button.
            // Las casilllas, subir archivo y multivariables tienen procesos completamente distintos.
            $elementos_html_para_modificar->map(function($fila , $key) use($tipo_producto , $tabla_formu){
                if($fila->html_elemento_id == 3){
                    // el origen es una lista de valores: 
                    if($this->arr_input_campos[$fila->slug] == 0){
                        // Significa que en la lista de valores no está escogida ninguna opción:
                        $this->arr_input_campos[$fila->slug] = 0;
                    }else{
                        $arr_params2 = [];
                        $sql2 = "SELECT conse FROM formu_lista_valores where id=:lista_valor";
                        $arr_params2 = [
                            ':lista_valor' =>  $this->arr_input_campos[$fila->slug],
                        ];
                        $obj_conse = DB::select($sql2 , $arr_params2);
                        $this->arr_input_campos[$fila->slug] = $obj_conse[0]->conse;
                    }
                }else if($fila->html_elemento_id == 5){
                    // el origen es un radio button:
                    if($this->arr_input_campos[$fila->slug] == 0){
                        // Significa que en la lista de valores no está escogida ninguna opción:
                        $this->arr_input_campos[$fila->slug] = 0;
                    }else{
                        $arr_params2 = [];
                        $sql2 = "SELECT conse FROM formu_lista_valores where id=:lista_valor";
                        $arr_params2 = [
                            ':lista_valor' =>  $this->arr_input_campos[$fila->slug],
                        ];
                        $obj_conse = DB::select($sql2 , $arr_params2);
                        $this->arr_input_campos[$fila->slug] = $obj_conse[0]->conse;
                    }
                }else if($fila->html_elemento_id == 4){
                    // el origen es una casilla;
                    if($this->arr_input_campos[$fila->slug] == 0){
                        // Significa que en la lista de valores no está escogida ninguna opción:
                        $this->arr_input_campos[$fila->slug] = 0;
                    }else{
                        // Lo que debe haber en $this->arr_input_campos, para campos tipo 'casillas' 
                        // debe ser un array en el que estarán todas las casillas que fueron
                        // marcadas por el usuario (NOTA: el primer elemento debe comenzar con 1,
                        // no con 0)
// $arr_params = [];
// $sql_casillas = "select id
//     from formu_lista_valores 
//     where formu__tabla=:formu__tabla
//         and formu__campo=:formu__campo 
//     order by id";
// $arr_params = [
//     ':formu__tabla' =>  $tabla_formu,
//     ':formu__campo' =>  $fila->slug,
// ];
// $obj_casillas = DB::select($sql_casillas , $arr_params);

                        // Mysql (Para obtener las casillas que el 
                        // usuario activó cuando creó el producto): 
                        $arr_params_escogidas = [];
                        $sql_casillas_escogidas = "SELECT fce.formu_lista_valores_id , 
                                flv.conse 
                            FROM formu_casillas_escogidas fce 
                                left join formu_lista_valores flv on flv.id=fce.formu_lista_valores_id 
                            where fce.formu__campo_casilla=:escogidas_id ";
                        $arr_params_escogidas = [
                            ':escogidas_id' =>  $this->arr_input_campos[$fila->slug],
                        ];
                        $obj_casillas_escogidas = DB::select($sql_casillas_escogidas , $arr_params_escogidas);
                        // $obj_casillas_escogidas es un array de objetos, hay que convertirlo 
                        // en un array "array":
                        $arr_casillas_escogidas = [];
                        $indice_ini_uno = 1;
                        foreach($obj_casillas_escogidas as $valor){
                            $arr_casillas_escogidas[$indice_ini_uno] = $valor->conse;
                            $indice_ini_uno++;
                        }
                        $this->arr_input_campos[$fila->slug] = $arr_casillas_escogidas;
                    }
                }else if($fila->html_elemento_id == 11){
                    // tipo Subir archivo
                    // Se debe pasar a un nuevo elemento de $this->arr_input_campos el 
                    // nombre de la imagen, y dejar en null el que lo tenia, asi:
                    if($this->arr_input_campos[$fila->slug] == null){
                        $this->arr_input_campos[$fila->slug . "_viejo"] = null;
                    }else{
                        // en el nuevo elemento de $this->arr_input_campos guardará 
                        // toda la ruta del hosting y nombre de archivo:
                        $public_path = config('constantes.path_foto_compte_https').'/storage/';
                        $ruta_nombre_archivo = $public_path . 'subidos_desde_formus/' . $this->tipo_producto_recibido_slug . '/' . $fila->slug . '/' . $this->arr_input_campos['id'] . '_' . $this->arr_input_campos[$fila->slug];
                        $this->arr_input_campos[$fila->slug . "_viejo"] = $ruta_nombre_archivo;
                        $this->arr_input_campos[$fila->slug] = null;
                    }                    
                }else if($fila->html_elemento_id == 12){

                    // tipo Multivariable:
                    // Llevar a la propiedad $this->arr_todos_input_campos_info_multivariable,
                    // la info de multivariables que esté grabada en la b.d.:

                    // Obtener la info de todos los campos multivariables del producto:
                    $arr_params_multivbles = [];
                    $sql_multivbles = "SELECT fcm.campo_detalle_id,
                            fcm.fila,
                            fcm.col,
                            fcm.valor 
                        FROM formu_contenidos_multivariables fcm
                            left join formu_detalles fd on fd.id=fcm.campo_detalle_id
                        where  fcm.formu__id=:formu__id 
                            and fd.tipo_producto_id=:tipo_producto_id
                            and fcm.campo_detalle_id=:campo_detalle_id";
                    $arr_params_multivbles = [
                        ':formu__id' =>  $this->formu__id,
                        ':tipo_producto_id' =>  $this->tipo_producto_recibido_id,
                        ':campo_detalle_id' =>  $fila->id,
                    ];
                    $obj_contenidos_multivariables = (DB::select($sql_multivbles , $arr_params_multivbles));

                    // Es posible que no haya encontrado registros (ejemplo: un multivariable
                    // con filas mínimas =0, o sea no obligatorio):
                    if($obj_contenidos_multivariables){
                        // Llevar el obj obtenido a $this->arr_todos_input_campos_info_multivariable:
                        $campo_detalle_id_anterior = $obj_contenidos_multivariables[0]->campo_detalle_id;

                        $arr_aux_datos_multivariable = [];
                        foreach($obj_contenidos_multivariables as $un_obj){
                            if($campo_detalle_id_anterior == $un_obj->campo_detalle_id){
                                $arr_aux_datos_multivariable[$un_obj->fila][$un_obj->col] = $un_obj->valor;
                            }else{
                                array_push($this->arr_todos_input_campos_info_multivariable , [
                                    'detalle_id_campo_multivariable' => $campo_detalle_id_anterior,
                                    'datos_multivariable' => $arr_aux_datos_multivariable,
                                ]);
                                $campo_detalle_id_anterior = $un_obj->campo_detalle_id;
                                $arr_aux_datos_multivariable = [];
                                $arr_aux_datos_multivariable[$un_obj->fila][$un_obj->col] = $un_obj->valor;
                            }
                        }
                        array_push($this->arr_todos_input_campos_info_multivariable , [
                            'detalle_id_campo_multivariable' => $campo_detalle_id_anterior,
                            'datos_multivariable' => $arr_aux_datos_multivariable,
                        ]);

                        // Para que la cantidad de filas de cada multivariable aparezca 
                        // en su correspondiente button en la vista:
                        // $this->arr_todos_input_campos_info_multivariable_canti_filas = [];
                        unset($this->arr_todos_input_campos_info_multivariable_canti_filas);
                        $this->arr_todos_input_campos_info_multivariable_canti_filas = [];

                        foreach($this->arr_todos_input_campos_info_multivariable as $fila_todos){
                            $aux_arr_ = [];
                            $aux_arr_['detalle_id_campo_multivariable'] = $fila_todos['detalle_id_campo_multivariable'];
                            $aux_arr_['canti_filas'] = count($fila_todos['datos_multivariable']);
                            array_push($this->arr_todos_input_campos_info_multivariable_canti_filas , $aux_arr_);
                        }
                    }
                }
            }); // fin de $elementos_html_para_modificar->map()
        }
        // ==================================================================================
        // 24dic2021 FIN Traer info del producto desde b.d. (si operacion es 'modificar' o 'ver')
        // ==================================================================================                      
    }
        
    public function render(){
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
        // 03nov2021 
        // para llenar la propiedad $this->roles_tipo_producto, que se encarga de 
        // permitir el cambio correcto de roles por parte de un 'admin', se resumirán 
        // todos los roles que manejen los campos de este tipo de producto: 
        $comer = '0';
        $produ = '0';
        $disen = '0';
        foreach ($elementos_html as $fila_elementos_html) {
            $arr_roles_un_producto = explode('_@@@_' , $fila_elementos_html['roles']);
            foreach ($arr_roles_un_producto as $value) {
                if($value == 'comer'){
                    $comer = '1';
                }else if($value == 'produ'){
                    $produ = '1';
                }else if($value == 'disen'){
                    $disen = '1';
                }
            }
            $this->roles_tipo_producto = $comer . $produ . $disen;
        }

        // Para llenar los combobox, casillas y radios que estén presentes en el formulario, 
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
                $fila->arr_para_casillas = FormuListaValore::select('conse' , 'texto')->where('formu__tabla', 'formu__'.$tipo_producto->tipo_producto_slug)->where('formu__campo' , $fila->slug)->where('conse' , '<>' , 0)->orderBy('conse')->get()->toArray();
            }else if($fila->html_elemento_id == 5){
                $fila->arr_para_radios = FormuListaValore::select('conse' , 'texto')->where('formu__tabla', 'formu__'.$tipo_producto->tipo_producto_slug)->where('formu__campo' , $fila->slug)->where('conse' , '<>' , 0)->orderBy('conse')->get()->toArray();
            }else if($fila->html_elemento_id == 12){
                // 16oct2021: 
                // Recordar que al formulario para crear productos solamente 
                // acceden dos roles: admin y comer
                // si el usuario es comer y en el multivariable correspondiente al 
                // detalle_id que se está recorriendo en esta iteración del ciclo,
                // no existe al menos una columna con rol 'comer' permitido, dicho 
                // multivariable deberá inhabilitarse en la vista, 
                // para que no salga el error descrito en la bitácora 26sep2021.
                if(Auth::user()->hasRole('comer')){
                    $arr_para_nro_rgtos = FormuDetallesMultivariable::where('formu_detalles_id' , $fila->id)->where('roles' , 'like' , '%comer%' )->get()->toArray();
                    if(count($arr_para_nro_rgtos) == 0){
                        // es un usuario comer y el multivariable no 
                        // tiene ninguna columna comer:                        
                        $fila->multi_sin_col_comer = true;
                    }else{
                        // es un usuario comer y el multivariable SI
                        // tiene al menos una columna comer:                          
                        $fila->multi_sin_col_comer = false;
                    }
                }else{
                    // es un usuario admin
                    $fila->multi_sin_col_comer = false;
                }
            }
        }); // fin de $elementos_html->map() para crear arrays para comboboxes

        // para llenar el combo de lista (propiedad pública) desde tablas del modal
        // multivariable que esté abierto:
        if($this->modal_visible_info_multivariable){
            $this->llenar_combos_listas_tablas_multivariable();
        }

        if($this->operacion == 'ver' || $this->operacion == 'modificar'){
            // 23feb2022:
            // Obtener el nombre del estado del producto:
            $arr_params = [];
            $sql = "select fe.nombre_estado 
                from formu_contenidos_estados fce 
                    left join formu_estados fe on fe.id=fce.formu_estado_id 
                where formu_tipo_producto_id = :tipo_producto_id 
                    and fce.formu__id = :formu__id
                    and tiempo_estado = 1";
            $arr_params = [
                ':tipo_producto_id' =>  $this->tipo_producto_recibido_id,
                ':formu__id' =>  $this->formu__id,
            ];
            $arr_registro_estado = (array)(DB::select($sql , $arr_params));
            $this->formu__estado_nombre = $arr_registro_estado[0]->nombre_estado;
        }

        if($this->operacion == 'ver'){
            // Para que muestre un "title" diferente:
            return view(
                'livewire.pedidos.formu.crear-formu' , 
                compact('tipo_producto' , 'elementos_html')
            )->layout('layouts.app', ['title' => $this->formu__codigo_producto]);
        }else{
            // operación es 'modificar' o 'crear':
            return view(
                'livewire.pedidos.formu.crear-formu' , 
                compact('tipo_producto' , 'elementos_html')
            );
        }
    }

    public function submit_formu(){
        // Llega acá cuando se presiona el botón "Crear producto" en el formulario 
        // de creación de un producto o en el botón "Grabar modificaciones del producto" 
        // en la modificación de un producto.

        // ==============================================================
        // Para mensajes de proceso correcto, o de errror
        // ==============================================================        
        $this->mensaje_correcto = "";
        $this->mensaje_error = ""; 

// dd($this->arr_rules);

// echo "<pre>arr input campos: ";
// print_r($this->arr_input_campos);                 
// echo "<br>arr rules:";
// print_r($this->arr_rules);                 
// dd('revisar');

// dd($this->arr_todos_input_campos_info_multivariable); 

// dd($this->arr_input_campos); 


        // ================================================================================
        // Validaciones de los campos subir archivo y multivariables, cuando se MODIFICA:
        // ================================================================================
        // 26dic2021:
        if($this->operacion == 'modificar'){
            // En la modificación de productos hay problema con la validación de 
            // campos tipo 'subir archivo' y multivariables. Se solucionaron asi:
            $campos = FormuDetalle::select('slug' , 
                    'html_elemento_id' , 'id' , 'obligatorio'
                )
                ->where('tipo_producto_id', $this->tipo_producto_recibido_id )
                ->get(); 
            foreach($campos as $fila){
                if($fila->html_elemento_id == 11){
                    // Campos tipo 'subir archivo':
                    // se debe verificar para los campos tipo 'subir archivo' que si tiene
                    // la vista preliminar no muestre el error 'No puede estar en blanco'
                    // que muestran las rules originales (puestas en mount()). Esto se
                    // logra modificando el array $this->arr_rules:
                    $campo_viejo = $fila->slug . "_viejo";
                    if($this->arr_input_campos[$fila->slug] == null 
                        && $this->arr_input_campos[$campo_viejo] !== null){
                            $this->arr_rules["arr_input_campos." . $fila->slug][0] = "nullable";
                            // NOTA: Esta modificación de $this->arr_rules implica un proceso 
                            // adiconal que se tuvo que hacer en el método reset_archivo_viejo()
                    }
                }else if($fila->html_elemento_id == 12){
                    // Para los campos multivariables: 
                    // 03ene2021: 
                    // Para que si hay campos multivariables con columnas vacias,
                    // muestre error y no deje grabar:
                   
                    $error_vacio = false; 
                    $rol_usuario_para_like = '%' . Auth::user()->roles->pluck('name')->first() . '%';
                    $arr_aux_detalles_multivariables = FormuDetallesMultivariable::where
                        ('formu_detalles_id' , $fila->id)
                        ->where('roles' , 'like' , $rol_usuario_para_like )
                        ->get()->toArray();

                    foreach ($arr_aux_detalles_multivariables as $fila1) {
                        if($fila->obligatorio){
                            // primero verifica que $fila1->formu_detalles_id exista
                            // en $this->arr_todos_input_campos_info_multivariable:
                            $error_campo_existe_en_todos = false;
                            foreach($this->arr_todos_input_campos_info_multivariable as $fila_buscar){
                                if($fila_buscar['detalle_id_campo_multivariable'] == $fila1['formu_detalles_id']){
                                    // lo encontró
                                    $error_campo_existe_en_todos = true;
                                    break;
                                }
                            }
                            // Si existe, hara el proceso de revisión, en caso contrario
                            // será mostrado un error en el $this->validated:                            
                            if($error_campo_existe_en_todos){
                                $aux_col = $fila1['col'] - 1;
                                foreach ($this->arr_todos_input_campos_info_multivariable as $fila2) {
                                    if($fila2['detalle_id_campo_multivariable'] == $fila1['formu_detalles_id']){
                                        foreach ($fila2['datos_multivariable'] as $fila3) {
                                            if(isset($fila3[$aux_col]) 
                                                    && $fila3[$aux_col] !== null){
                                                // el campo esta lleno, todo va bien
                                            }else{
                                                $error_vacio = true; 
                                                break 3;
                                            }
                                        } 
                                    }
                                }
                            }else{
                                $error_vacio = true; 
                                break;
                            }
                        }
                    }
                    if($error_vacio){
                        // faltan campos por llenar, se agrega la rule para que muestre el
                        // mensaje y no permita grabar cuando haga el $this->validated
                        $this->arr_rules["arr_input_campos." . $fila->slug][0] = "required";
                    }
                }else if(!$fila->obligatorio){
                    $this->arr_rules["arr_input_campos." . $fila->slug][0] = "nullable";
                }
            }
        }

        // ================================================================================
        // Ajustes a $this->arr_rules:
        // ================================================================================
        // 03oct2021:
        // quitar de $this->arr_rules las filas cuyo valor sean []:
        foreach($this->arr_rules as $key_rule => $fila_rule){
            if($fila_rule == []){
                unset($this->arr_rules[$key_rule]);
            }
        }

        // ================================================================================
        // Verificar que al menos se haya digitado un campo del formulario
        // ================================================================================
        // 28sep2021:
        $permiso_procesar = true;
        if ($this->arr_input_campos == []) {
            // si no se digitó ningun de los campos que no sean multivariable:
            if ($this->arr_todos_input_campos_info_multivariable == []) {
                // si tampoco se llenó ningún campo multivariable, debe mostrar error:
                $this->mensaje_error = "No hay información para grabar." ;
                $permiso_procesar = false;
            } else {
                // verificar que los multivariables llenos con no lo estén con filas vacias:
                $cuenta_filas_vacias = 0;
                foreach($this->arr_todos_input_campos_info_multivariable as $fila_todos){
                    if($fila_todos['datos_multivariable'] == []){
                        $cuenta_filas_vacias++;
                    }
                }
                if($cuenta_filas_vacias == count($this->arr_todos_input_campos_info_multivariable)){
                    // si todos los multivariables llenos lo están con filas
                    // vacias, debe mostrar error:
                    $this->mensaje_error = "No hay información para grabar." ;
                    $permiso_procesar = false;
                }
                # puede procesar
            }
        } else {
            # puede procesar
        }

        // ================================================================================
        // Agregar o modificar en la b.d.
        // ================================================================================
        if($permiso_procesar){
            if ($this->arr_rules) {
                $this->validate(); 
            }
// dd($this->arr_input_campos);           
            
            // a) Preparar el array con el que luego se ejecutará el insert into 
            //    o el update:
            $tabla_formu = "formu__" . $this->tipo_producto_recibido_slug;
            $arr_grabar_rgto = [];
            foreach($this->arr_input_campos as $key => $valor){
                // 25sep2021: 
                // Recordar que en el método submit_info_multivariable()  se agregaron MANUALMENTE a 
                // $this->arr_input_campos unos registros cuyas claves eran los 
                // slugs de campos multivariables y cuyos valores eran: '@@@_tiene_filas_@@@',
                // en este momento hay que evitar que esos registros agregados manualmente,
                // sean tenidos en cuenta en el insert into y update
                if ($valor == '@@@_tiene_filas_@@@'){
                    // el registro no debe ir en el insert into ni en el update
                } else {
                    // Lo primero es obtener el html_elemento_id correspondiente: 
                    $html_elemento_id = $this->obtener_html_elemento_id($this->tipo_producto_recibido_id , $key);
                    switch ($html_elemento_id) {
                        case 999:
                            // el campo es: id, codigo, created_at, updated_at, user_id
                            // o contiene '_viejo', y por lo tanto no debe ser tenido en 
                            // cuenta ni para el insert into ni para el update. Por eso
                            // no se hace nada en este case.
                            break;
                        case 3:
                            // es una selección desde valores, se debe grabar en 
                            // formu__.... el id que corresponda a formu_lista_valores
                            // (no el conse, sino el id): 
                            $arr_lista_valores_id = FormuListaValore::select('id')->where('formu__tabla' , $tabla_formu)->where('formu__campo' , $key)->where('conse' , $valor)->get();
                            $lista_valores_id = $arr_lista_valores_id[0]['id'];                    
                            $arr_grabar_rgto[$key] = $lista_valores_id;
                            break; 
                        case 4: 
                            // es un elemento tipo casillas. En este caso $valor
                            // es un array. 
                            // Se deben insertar las casillas que fueron
                            // escogidas, en la tabla formu_casilas_escogidas 
                            // Y luego se grabará el id correspondiente en la tabla formu__.....
                            // lo primero es saber en que consecutivo va la formu_casillas_escogidas: 
                            $nuevo_conse_casillas = DB::table('formu_casillas_escogidas')->max('formu__campo_casilla');
                            if($nuevo_conse_casillas == null){
                                $nuevo_conse_casillas = 1;
                            }else{
                                $nuevo_conse_casillas++;
                            }
            
                            // y ahora se graba en formu_casillas_escogidas cada una de las 
                            // casillas que hayan sido escogidas (un registro por casilla):
                            foreach($valor as $ele){
                                $arr_lista_valores_id = FormuListaValore::select('id')->where('formu__tabla' , $tabla_formu)->where('formu__campo' , $key)->where('conse' , $ele)->get();
                                $lista_valores_id = $arr_lista_valores_id[0]['id'];
                                FormuCasillasEscogida::create([
                                    'formu__campo_casilla' => $nuevo_conse_casillas,
                                    'formu_lista_valores_id' => $lista_valores_id,
                                ]);                     
                            }
                            // y por último, para que al salir del foreach mas externo, se 
                            // grabe en formu__.... el nuevo_conse_casillas:
                            $arr_grabar_rgto[$key] = $nuevo_conse_casillas;                    
                            break;
                        case 5: 
                            // es un radio button, se debe grabar en 
                            // formu__.... el id que corresponda a formu_lista_valores
                            // (no el conse, sino el id): 
                            $arr_lista_valores_id = FormuListaValore::select('id')->where('formu__tabla' , $tabla_formu)->where('formu__campo' , $key)->where('conse' , $valor)->get();
                            $lista_valores_id = $arr_lista_valores_id[0]['id'];                    
                            $arr_grabar_rgto[$key] = $lista_valores_id;                    
                            break;
                        case 11: 
                            // es un elemento subir archivo, debe hacer la preparación
                            // para grabar en formu__..... el  nombre de archivo y su
                            // extensión (no el nombre temporal con que fué subido), y 
                            // adicionalmente, grabarlo con SLUG (ya que en el hosting 
                            // el nombre de archivo también será grabado asi):
                            // Si estamos en modificar y $valor es null, significa que 
                            // el usuario no cambió el archivo: 
                            if($this->operacion == 'modificar'
                                    && $valor == null){
                                // no debe llevar nada al $arr_grabar_rgto
                            }else{
                                $nombre_archivo_original_slug = $this->armar_slug_con_ultimo_punto($valor->getClientOriginalName());
                                $arr_grabar_rgto[$key] = $nombre_archivo_original_slug;
                            }
                            break;
                        default:
                            // no es ni casilla ni selección desde valores ni radio ni subir archivo:
                            $arr_grabar_rgto[$key] = $valor;
                            break;
                    }   
                }
            }  // fin del foreach

            if($this->operacion == 'crear'){
                // b) insert into en formu__...... :
                $arr_grabar_rgto['created_at'] = date('Y-m-d h:i:s');
                $arr_grabar_rgto['user_id'] = Auth::user()->id;
// dd($arr_grabar_rgto);

                // Es recomendable usar siempre query builder, este previene SQL inyección
                // query builder:  DB::tablet(...)->insert(...);  // this prevents SQL injection.
                // raw builder:    DB::insert(...);    // this dont prevent injection            
                $ultimo_id = DB::table($tabla_formu)->insertGetId($arr_grabar_rgto); 

                // Una vez obtenido el $ultimo_id, se actualiza el campo 'codigo' siempre y 
                // cuando el tipo de producto tenga prefijo !== null: 
                if($this->tipo_producto_recibido_prefijo !== null){
                    $aux_codigo_producto = $this->tipo_producto_recibido_prefijo . '-' . date('Ymd') . '-' . $ultimo_id;
                    $affected = DB::table($tabla_formu)
                        ->where('id', $ultimo_id)
                        ->update(['codigo' => $aux_codigo_producto]);
                }
            }else if($this->operacion == 'modificar'){
                // c) update en formu__..................... :
                $arr_grabar_rgto['updated_at'] = date('Y-m-d h:i:s');
                // 03ene2022: Comentariada porque borraria el usuario
                // que creó el producto. Habrá que agregar un nuevo
                // campo user updated a la tabla - pendiente.
                // $arr_grabar_rgto['user_id'] = Auth::user()->id;                    
                DB::table($tabla_formu)
                    ->where('id', $this->formu__id)
                    ->update($arr_grabar_rgto);
            }else if($this->operacion == 'ver'){
                // en realidad, si es 'ver' nunca llegará a esta linea
            }
            // 29dic2021: Gestión de los campos 'Subir archivo':
            if($this->operacion == 'crear'){
                // c) Si en formu__... fueron grabados campos tipo subir archivo, estos deben ser 
                // grabados en storage/app/public/subidos_desde_formus, para esto, se recorre
                // nuevamente arr_input_campos y por cada tipo subir archivo, sube el archivo 
                // al hosting, según lo especificado en la bitácora (24jul2021), es decir, se 
                // sube el archivo con el nombre original, anteponiéndole el id de formu__... 
                // y adicionalmente grabando el nombre con slug, para evitar problemas con 
                // espacios en blanco en los nombres de los archivos: 
                foreach($this->arr_input_campos as $key => $valor){
                    $html_elemento_id = $this->obtener_html_elemento_id($this->tipo_producto_recibido_id , $key);
                    if($html_elemento_id == 11){
                        // en el hosting se debe grabar el nombre de archivo con SLUG, para evitar 
                        // problemas con caracteres especiales. Ya que slug "desaparece" los puntos 
                        // que tenga el nombre de archivo, es necesario preservar el último 
                        // punto (que corresponde a la extensión de archivo):
                        $slug_con_punto_extension = $this->armar_slug_con_ultimo_punto($valor->getClientOriginalName());
                        $valor->storeAs('public/subidos_desde_formus/' . $this->tipo_producto_recibido_slug . '/' . $key . '/' , $ultimo_id . '_' . $slug_con_punto_extension);
                    }
                }
            }else if($this->operacion == 'modificar'){
                // En caso de que sea modificación de un producto, solamente se tendrá que hacer 
                // algún proceso si el usuario cambio algun archivo. Si el usuario cambió el archivo
                // en el correspondiente elemento del campo (en arr_input_campos) habrá un 
                // objeto, en caso contrario habrá null:
                foreach($this->arr_input_campos as $key => $valor){
                    $html_elemento_id = $this->obtener_html_elemento_id($this->tipo_producto_recibido_id , $key);
                    if($html_elemento_id == 11){
                        if($valor == null){
                            // el usuario no cambió el archivo, no hay que hacer nada.
                        }else{
                            // El usuario cambió el archivo, hay que subir el nuevo archivo 
                            // a la carpeta del hosting
                            $slug_con_punto_extension = $this->armar_slug_con_ultimo_punto($valor->getClientOriginalName());
                            $valor->storeAs('public/subidos_desde_formus/' . $this->tipo_producto_recibido_slug . '/' . $key . '/' , $this->formu__id . '_' . $slug_con_punto_extension);
                        }
                    }
                }
            }else if($this->operacion == 'ver'){
                // en realidad, si es 'ver' nunca llegará a esta linea
            }

            // ==============================================================
            // 12sep2021: Cuando aplique, agregar la info de campos multivariables en
            // la tabla formu_contenidos_multivariables
            // ==============================================================
            // La grabación en formu_contenidos_multivariable se hace a partir del 
            // $this->arr_todos_input_campos_info_multivariable
            if($this->arr_todos_input_campos_info_multivariable !== null){
                foreach($this->arr_todos_input_campos_info_multivariable as $key_fila => $valor_fila){
                    $campo_detalle_id = $valor_fila['detalle_id_campo_multivariable'];
                    foreach($valor_fila['datos_multivariable'] as $key_fila_2 => $valor_fila_2){
                        foreach($valor_fila_2 as $key_col => $valor_col){
                            FormuContenidosMultivariable::create([
                                'campo_detalle_id' => $campo_detalle_id,
                                'formu__id' => ($this->operacion == 'crear') ? $ultimo_id : $this->formu__id,
                                'fila' => $key_fila_2,
                                'col' => $key_col,
                                'valor' => $valor_col,
                            ]);
                        }
                    }
                }
            }

            // ==============================================================
            // 19oct2021: 
            // Si es 'crear' debe el nuevo ESTADO en la tabla formu_contenidos_estados
            // Si es 'modificar' es posible que lo tenga que agregar o no
            // ==============================================================   
            if($this->operacion == 'crear'){
                // El estado del nuevo producto dependerá de los roles que se le hayan configurado
                // a los campos del tipo de producto, en la propiedad $this->roles_tipo_producto 
                // que es una propiedad "binaria" de tres posiciones en donde la primer posición 
                // indica si tiene o no campos 'comer', la segunda posición campo 'produ' y 
                // la tercera campos 'disen', ejemplo:
                //      100:    tiene campos 'comer' 
                //      110:    tiene campos 'comer' y 'produ'   ,   etc... 
                switch ($this->roles_tipo_producto) {
                case '100':
                    $formu_estado_id = 1;
                    break;
                case '010':
                case '110':
                    $formu_estado_id = 9;
                    break;
                case '001':
                case '101':
                    $formu_estado_id = 10;
                    break;
                case '111':
                    $formu_estado_id = 11;
                    break;
                default: 
                    $formu_estado_id = 99;
                    break;
                }
            }else if($this->operacion == 'modificar'){
                // 30dic2021. Ver origen del algoritmo en el cuaderno (basado en los
                //            2 diagramas de flujo de estados)
                // A la opción de modificar un producto pueden acceder 4 roles: admin,
                // comer, produ y disen. Pero comer no puede modificar campos de produ
                // ni de disen, por lo que no se tiene en cuenta aquí.
                // Es posible que a pesar de que un admin, produ y disen modifiquen un
                // producto, su estado no cambie (ejemplo: Si el estado es 24: por 
                // aprobar producción, por aprobar diseño; los usuarios pueden modificar
                // el producto pero no implica que cambie el estado. En cambio si el
                // estado es 22: por aprobar producción, por digitar diseño; si un 
                // usuario de diseño modifica el producto, es decir graba, el estado
                // debe pasar a 24: Por aprobar producción, por aprobar diseño)
                $formu_estado_id = 999;  // 999: Significa que no debe cambiar de estado
                if(Auth::user()->hasRole('produ')){
                    if($this->formu__estado_codigo == 12){
                        $formu_estado_id = 2;
                    }else if($this->formu__estado_codigo == 21){
                        $formu_estado_id = 22;
                    }else if($this->formu__estado_codigo == 23){
                        $formu_estado_id = 24;
                    }else if($this->formu__estado_codigo == 26){
                        $formu_estado_id = 28;
                    }
                }else if(Auth::user()->hasRole('disen')){
                    if($this->formu__estado_codigo == 13){
                        $formu_estado_id = 3;
                    }else if($this->formu__estado_codigo == 21){
                        $formu_estado_id = 23;
                    }else if($this->formu__estado_codigo == 22){
                        $formu_estado_id = 24;
                    }else if($this->formu__estado_codigo == 25){
                        $formu_estado_id = 27;
                    }
                }else if(Auth::user()->hasRole('admin')){
                    if($this->formu__estado_codigo == 12){
                        $formu_estado_id = 2;
                    }else if($this->formu__estado_codigo == 13){
                        $formu_estado_id = 3;
                    }else if($this->formu__estado_codigo == 21){
                        $formu_estado_id = 24;
                    }else if($this->formu__estado_codigo == 22){
                        $formu_estado_id = 24;
                    }else if($this->formu__estado_codigo == 23){
                        $formu_estado_id = 24;
                    }else if($this->formu__estado_codigo == 26){
                        $formu_estado_id = 28;
                    }else if($this->formu__estado_codigo == 25){
                        $formu_estado_id = 27;
                    }
                }
            }else if($this->operacion == 'ver'){
                // en realidad, si es 'ver' nunca llegará a esta linea
            }
            if($formu_estado_id !== 999){
                // primero que todo poner todos los campos tiempo_estado en cero: 
                $obj_contenidos_estados = new FormuContenidosEstado();             
                $obj_contenidos_estados->tiempos_a_cero($this->tipo_producto_recibido_id , $this->formu__id);

                // luego agregar a la tabla formu_contenidos_estados
                FormuContenidosEstado::create([
                    'formu_tipo_producto_id' => $this->tipo_producto_recibido_id,
                    'formu__id' => ($this->operacion == 'crear') ? $ultimo_id : $this->formu__id,
                    'formu_estado_id' => $formu_estado_id, 
                    'tiempo_estado' => 1,
                    'fec_rgto' => date('Y-m-d h:i:s'),
                    'user_id' => Auth::user()->id,
                ]);
            }

            // ==============================================================
            // Reiniciar formulario:
            // ==============================================================
            if($this->operacion == 'crear'){
                $this->mensaje_correcto = "Se acabó de agregar un nuevo registro a la base de datos." ;
                $this->limpiar();
            
            }else if($this->operacion == 'modificar'){
                $this->mensaje_correcto = "Los cambios al producto fueron grabados correctamente." ;
                $this->limpiar();
                return redirect(url('ver-formu' , [
                    'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
                ]));                
            }else if($this->operacion == 'ver'){
                // en realidad, si es 'ver' nunca llegará a esta linea
            }
        }
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

    public function reset_archivo_viejo($slug_campo , $slug_campo_viejo){
        // 26dic2021:
        // Llega aqui cuando el usuario presiona el botón X (eliminar) de un elemento
        // de subir archivo que estaba mostrando un archivo ".....viejo", en este
        // caso no hay que limpiar el input type file sino solo la vista preliminar:
        $this->arr_input_campos[$slug_campo_viejo] = null;

        // 26dic2021:
        // Debido a lo hecho en el método submit_formu(), es necesario 
        // lo siguiente: Volver a agregar 'required' $this->arr_rules para este 
        // campo al que se le habia cambiado en submit_formu() por 'nullable'. Eso
        // sí: teniendo en cuenta que esta modificación solo se puede hacer 
        // si el campo en cuestión tiene 'obligatorio' en la tabla formu_detalles: 
        $arr_params = [];
        $sql = "select obligatorio from formu_detalles where slug=:slug ";
        $arr_params = [
            ':slug' =>  $slug_campo,
        ];
        $obj_det = (DB::select($sql , $arr_params));
        $obligatorio = $obj_det[0]->obligatorio;
        if($obligatorio){
            $this->arr_rules["arr_input_campos." . $slug_campo][0] = "required";
        }
    }  

    public function mostrar_modal_btn_cancelar(){
        // Muestra el modal en donde se le avisará al usuario 
        // que hay info sin grabar.
// dd($this->arr_input_campos) ;    
        // Primero se verifica si hay o no info sin grabar: 
        $vacio_arr_input_campos = true;
        foreach ($this->arr_input_campos as $key => $value) {
            // 13oct2021: Si el elemento es casillas se trata de un 
            // array, por eso este if: 
            if(is_array($value)){
                if(count($value) !== 0){
                    $vacio_arr_input_campos = false;
                }
            }else{
                if(strlen(trim($value)) !== 0){
                    $vacio_arr_input_campos = false;
                }
            }
        }
        $vacio_arr_multivariable_canti_filas = true;
        if ($this->arr_todos_input_campos_info_multivariable_canti_filas !== null) {
            foreach ($this->arr_todos_input_campos_info_multivariable_canti_filas as $key => $fila) {
                if($fila['canti_filas'] !== 0){
                    $vacio_arr_multivariable_canti_filas = false;
                }
            }
        }

        if ($vacio_arr_input_campos  && $vacio_arr_multivariable_canti_filas ) {
            // No hay info sin grabar, puede regresar:
            $this->btn_cancelar();
        }else{
            // Hay info sin grabar, se debe abrir el modal para advertir, en el 
            // modal el usuario decidirá la acción a ejecutar:
            $this->modal_visible_cancelar = true;
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
        $this->reset('arr_input_campos');
        $this->reset('arr_todos_input_campos_info_multivariable');
        $this->reset('arr_todos_input_campos_info_multivariable_canti_filas');
    }

    public function btn_cancelar(){
        $this->limpiar();
        return redirect(url('ver-formu' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
        ]));
    }

    private function obtener_html_elemento_id($tipo_producto_id , $key){
        // retorna el tipo html_elemento_id del campo que llega en $key, SIEMPRE
        // Y CUANDO el campo no sea: id, codigo, created_at, updated_at, user_id y los
        // que contengan '_viejo':
        $campo_viejo = strripos($key, "_viejo");
        if ($key == 'id' || $key == 'codigo' || $key == 'created_at'
            || $key == 'updated_at' || $key == 'user_id' || $campo_viejo == true) {
                return 999;
        }else{
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
        return $coll_html_elemento_id[0]->html_elemento_id;
    }
}  

private function armar_slug_con_ultimo_punto($convertir){
        // Slug "desaparece" los puntos 
        // que tenga un nombre de archivo, es necesario preservar el último 
        // punto (que corresponde a la extensión de archivo):                
        $nombre_slug_original = $convertir;
        $nombre_slug_extension = Str::afterLast($nombre_slug_original, '.');
        $nombre_slug_sin_punto = Str::slug($nombre_slug_original);
        return Str::replaceLast($nombre_slug_extension, '.' . $nombre_slug_extension, $nombre_slug_sin_punto);
    }      
    
    // ===============================================================================
    //      Métodos que tienen que ver con los modales multivariables
    // ===============================================================================
    public function llamar_llenar_multivariable($formu_detalles_id_multivariable){
        // 31ago2021: 
        // llamado cuando el usuario presiona el boton "Click para diligenciar ...."  
        // en la creación de un producto.
        // Recibe el id de  formu_detalles, con el 
        // cual se podrá obtener toda la info a pedir en el modal, a partir de la tabla
        // formu_detalles_multivariables
        // Este método hace todo esto: 
        //      Lee info de las tablas formu_detalles y formu_detalles_multivariables 
        //      Verifica si el campo multivariable tiene o no info asignada
        //      Crea la info para los combobox de valores desde tablas
        //      Visibiliza el modal (recordar que se preserva lo que se hubiera 
        //      escrito en los campos de NUEVO producto) 


// dd($this->arr_todos_input_campos_info_multivariable);


        // obtiene toda la info para el modal: 
        // desde formu_detalles: cabecera, slug, min num, max num, multivariable id
        // desde formu_detalles_multivariables: col1_cabecera, col1_roles, etc.... 
        $formu_detalles_multivariable = FormuDetalle::find($formu_detalles_id_multivariable);
        $formu_detallesmultivariables_multivariable = FormuDetallesMultivariable::where('formu_detalles_id' , $formu_detalles_id_multivariable)->get();  

        $this->detalle_id_multivariable = $formu_detalles_id_multivariable;
        $this->cabecera_multivariable = $formu_detalles_multivariable->cabecera;
        $this->slug_multivariable = $formu_detalles_multivariable->slug;
        $this->filas_min_multivariable = $formu_detalles_multivariable->min_num;
        if($formu_detalles_multivariable->max_num == null){
            $this->filas_max_multivariable = 999;
        }else{
            $this->filas_max_multivariable = $formu_detalles_multivariable->max_num;
        }
        $this->filas_act_multivariable = $this->filas_min_multivariable;

        $this->arr_formu_detalles_multivariable = $formu_detallesmultivariables_multivariable;
// dd($this->arr_formu_detalles_multivariable);        
        // 11sep2021: 
        // Para permitir editar el contenido de los multivariable:
        // Busca en $this->arr_todos_input_campos_info_multivariable, si existe info para 
        // el campo multivariable, si es asi llena el array con el que se pedirá la info en el modal: 
        $buscar_id = array_search($formu_detalles_id_multivariable, array_column($this->arr_todos_input_campos_info_multivariable , 'detalle_id_campo_multivariable'));
        if($buscar_id !== false){
// echo "<pre>"; 
// print_r($this->arr_todos_input_campos_info_multivariable);
            $this->arr_input_campos_info_multivariable = $this->arr_todos_input_campos_info_multivariable[$buscar_id]['datos_multivariable'];
 
// dd($this->arr_input_campos_info_multivariable);
            // Ya que existe, se debe averiguar cuántas filas existen, para poder mostrarlas 
            // todas en el modal: 
            $this->filas_act_multivariable = count($this->arr_input_campos_info_multivariable);



// print_r($this->arr_input_campos_info_multivariable);
// dd($this->arr_todos_input_campos_info_multivariable);
        }else{
            // sin lo siguiente, al dar click a un campo traeria la info del último
            // que se haya abierto:
            $this->arr_input_campos_info_multivariable = [];
        }; 
// dd($this->arr_input_campos_info_multivariable);
        // hace el llenado de $this->arr_lista_tabla_multivariable, la
        // propiedad que contiene la lista desde tablas del campo multivariable:
        $this->llenar_combos_listas_tablas_multivariable();

        $this->modal_visible_info_multivariable = true;

        // return view('livewire.pedidos.formu.crear-formu');
    }

    public function submit_info_multivariable(){
        // llamado al dar click al botón "Grabar datos" desde un modal multivariable 
        // Las rules de los multimodales no se usan con rules() sino directamente 
        // con $this->validate().

        // validación directa en caso de que al menos haya una fila para validar: 
        if($this->filas_act_multivariable >= 1){
            for($fil_validar_multivariable = 0 ; $fil_validar_multivariable < $this->filas_act_multivariable ; $fil_validar_multivariable++){
                for($col_validar_multivariable = 0 ; $col_validar_multivariable < count($this->arr_formu_detalles_multivariable) ; $col_validar_multivariable++){
                    // 03oct2021:
                    $arr_aux_roles = explode('_@@@_' , $this->arr_formu_detalles_multivariable[$col_validar_multivariable]['roles']);
                    if(Auth::user()->hasRole($arr_aux_roles)){
                        $arr_rules_multivariable['arr_input_campos_info_multivariable.' . $fil_validar_multivariable . '.' . $col_validar_multivariable] = [];
                        array_push($arr_rules_multivariable['arr_input_campos_info_multivariable.' . $fil_validar_multivariable . '.' . $col_validar_multivariable] , 'required');
                        $arr_messages_multivariable['arr_input_campos_info_multivariable.' . $fil_validar_multivariable . '.' . $col_validar_multivariable . '.required'] = 'No pueden haber campos en blanco.';
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
// dd($this->arr_input_campos);

        // una vez validado el $this->arr_input_campos_info_multivariable, 
        // se debe agregar o modificar en el array que tiene la info de todos 
        // los modales ($this->arr_todos_input_campos_info_multivariable), 
        // que se agregue o modifique depende si existe info o no 
        // en él. Este array será utilizado después, en el método submit_formu().
        // Luego el programa cierra el modal y regresará a la ventana de ingreso
        // de productos (es decir, el método render() de este componente)

        // 12sep2021: 
        // Para determinar si se debe agregar o modificar el contenido de los multivariable:
        // Busca en $this->arr_todos_input_campos_info_multivariable, si existe info para 
        // el campo multivariable lo debe modificar, sino lo debe agregar: 
// dd($this->arr_todos_input_campos_info_multivariable);            
// dd(array_column($this->arr_todos_input_campos_info_multivariable , 'detalle_id_campo_multivariable'));            
        $buscar_id = array_search($this->detalle_id_multivariable, array_column($this->arr_todos_input_campos_info_multivariable , 'detalle_id_campo_multivariable'));

        if($buscar_id !== false){
            // existe: lo debe modificar:
            $this->arr_todos_input_campos_info_multivariable[$buscar_id]['datos_multivariable'] = $this->arr_input_campos_info_multivariable; 
        }else{
            // no existe: lo debe agregar:
            $arr_aux = [ 
                'detalle_id_campo_multivariable' => $this->detalle_id_multivariable,
                'datos_multivariable' => $this->arr_input_campos_info_multivariable,
            ];            
            array_push($this->arr_todos_input_campos_info_multivariable , $arr_aux);
        }
        // 24sep2021: para mostrar en los botones multivariables el número filas que tienen info: 
        // $this->arr_todos_input_campos_info_multivariable_canti_filas = [];
        unset($this->arr_todos_input_campos_info_multivariable_canti_filas);
        $this->arr_todos_input_campos_info_multivariable_canti_filas = [];
        foreach($this->arr_todos_input_campos_info_multivariable as $fila_todos){
            $aux_arr_ = [];
            $aux_arr_['detalle_id_campo_multivariable'] = $fila_todos['detalle_id_campo_multivariable'];
            $aux_arr_['canti_filas'] = count($fila_todos['datos_multivariable']);
            array_push($this->arr_todos_input_campos_info_multivariable_canti_filas , $aux_arr_);
        }
        $this->reset('arr_input_campos_info_multivariable');
// dd($this->arr_todos_input_campos_info_multivariable_canti_filas);                    


        // 25sep2021: 
        // SIEMPRE Y CUANDO el multivariable tenga al menos una fila, se debe 
        // agregar MANUALMENTE al array que tiene los inputs digitados por el 
        // usuario ($this->arr_input_campos), un registro que contenga el slug 
        // del campo multivariable y llevar alli (a su valor) cualquier cosa, en este
        // caso decidimos llevar la cadena '@@@_tiene_filas_@@@', esto para que 
        // en las validaciones de las $this->arr_rules, si 'el multivariable es obligatorio, 
        // se pueda mostrar el mensaje de aviso 'No puede estar en blanco...
        // NOTA: No hay necesidad de poner en $this->arr_input_campos la cantidad de las filas,
        //       basta con colocar cualquier cosa (lo importante es que la clave exista), esto 
        //       hará que el campo cumpla con su 'required'
        $fila_para_cantidad = $this->arr_todos_input_campos_info_multivariable_canti_filas[0];   
// dd($arr_);   
        if ($fila_para_cantidad['canti_filas'] >= 1) {
            $this->arr_input_campos[$this->slug_multivariable] = '@@@_tiene_filas_@@@'; 
        }
         
// dd($this->slug_multivariable);        
// dd($this->arr_todos_input_campos_info_multivariable);

        $this->cerrar_modal_info_multivariable();
    }

    public function cerrar_modal_info_multivariable(){
        $this->modal_visible_info_multivariable = false;

        // $this->reset('arr_input_campos_info_multivariable'); 

        // $this->reset('arr_messages_multivariable');
        // return redirect(url('crear-formu' , [
        //     'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
        //     'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
        //     'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        // ]));
    }    

    public function agregar_fila_multivariable(){
        $this->filas_act_multivariable ++;
    }

    public function eliminar_fila_multivariable($num_fila_multivariable){
        $this->filas_act_multivariable --;
        // Para borrar la fila(unset) y además la posición(array_values) 
        // en el arr_input_campos_info_multivariable:
        unset($this->arr_input_campos_info_multivariable[$num_fila_multivariable]);
        $this->arr_input_campos_info_multivariable = array_values($this->arr_input_campos_info_multivariable);

// dd($this->arr_input_campos_info_multivariable);        
    }    

    private function llenar_combos_listas_tablas_multivariable(){
        // 08sep2021: 
        // Array para llenar los combo box 
        // de las listas de tablas:
// dd($this->arr_formu_detalles_multivariable) ;  



        $this->arr_lista_tabla_multivariable = [];
        $obj_config_index_multivariable = new ConfigIndex(); 
// dd($this->arr_formu_detalles_multivariable);        
        foreach($this->arr_formu_detalles_multivariable as $key_ => $fila_multivariable){
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

    public function determinar_disabled($fila_roles){
        // Llamado desde la vista para determinar si el correspondiente input 
        // se tiene que activar o no (disabled), lo cual dependde de los 
        // roles del campo y también de si la operación es 'ver'
        $arr_resultado = [];
        $arr_aux_roles = explode('_@@@_' , $fila_roles);
        if(Auth::user()->hasRole($arr_aux_roles)){
            if($this->operacion == 'ver'){
                $arr_resultado['disabled1___'] = 'disabled';
                $arr_resultado['fondo1___'] = 'bg-gray-300';
            }else{
                $arr_resultado['disabled1___'] = '';
                $arr_resultado['fondo1___'] = '';
            }
        }else{
            $arr_resultado['disabled1___'] = 'disabled';
            $arr_resultado['fondo1___'] = 'bg-gray-300';
        }
        return $arr_resultado;
    }

    // public function hydrateFoo($value){
    //     echo "<script>alert('hydratefoo');</script>";
    // }
    // public function hydrate($value){
    //     echo "<script>alert('hydrate');</script>";
    // }

}


