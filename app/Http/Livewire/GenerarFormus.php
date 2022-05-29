<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\FormuTipoProducto;
use App\Models\FormuDetalle;
use Illuminate\Support\Str;

class GenerarFormus extends Component
{
    // Nota a ppal_provisional: tendrá el código tipo_producto que se esté trabajando,
    // inicialmente se tiene quemado el 1, que es el que corresponde 
    // al tipo_producto: Etiquetas....
    public $ppal_provisional = 1;  

    public $modal_visible_gral;
    public $modal_visible_texto;

    // para el modal: config gral: 
    public $gral_titulo;
    public $gral_subtitulo;
    public $gral_columnas;

    // para el modal: texto 
    public $texto_cabecera = null;
    public $texto_obligatorio = null;
    public $texto_longitud_max = null;

    // // para la vista prelimininar: 
    // public $preliminar_


    public function render()
    {
        // información del tipo de producto (formu_tipo_productos): 
        $arr_params = [];
        $sql1 = "select tipo_producto_nombre, 
                titulo, subtitulo, columnas, labels_ubicacion 
            from formu_tipo_productos 
            where id = :tipo_producto_id ";
        $arr_params = [
            ':tipo_producto_id' => $this->ppal_provisional ,
        ];
        $tipo_productos = collect(DB::select($sql1 , $arr_params));
        $tipo_producto = $tipo_productos[0];

        // información de formu_detalles 
        $arr_params3 = [];
        $sql3 = "select elemento_html, cabecera, slug, obligatorio, max_largo 
            FROM formu_detalles 
            where tipo_producto_id = :tipo_producto_id 
            order by orden asc ";
        $arr_params3 = [
            ':tipo_producto_id' => $this->ppal_provisional ,
        ];
        $elementos_html = collect(DB::select($sql3 , $arr_params3));
   
        return view('livewire.generar-formus' , compact('tipo_producto' , 'elementos_html') );
    }

    public function mostrar_modal_gral($titulo , $subtitulo , $columnas){
        // para que al abrir el modal muestre lo que está en la b.d.: 
        $this->gral_titulo = $titulo;
        $this->gral_subtitulo = $subtitulo;
        $this->gral_columnas = $columnas;
        $this->resetValidation(); 

        $this->modal_visible_gral = true;
    }      

    public function submit_gral(){
        // Graba en la tabla formu_tipo_productos el título y subtítulo, ver la 
        // explicación de ppal_provisional al principio de este componente

        //  $this->validate();
        // no se puede usar $this->validate a nivel global (es decir con 
        // las propiedades rules y messages) debido a que entonces validaria 
        // las condiciones de los otros modales. Por eso se hace asi:
        $validatedData = $this->validate(
            ['gral_columnas' => 'numeric|required|min:1|max:4'],
            [
                'gral_columnas.required' => 'Debe digitar el número de columnas.',
                'gral_columnas.numeric' => 'El número de columnas debe ser numérico.',
                'gral_columnas.min' => 'El número de columnas debe ser mayor o igual a 1',
                'gral_columnas.max' => 'El máximo número de columnas es 4',
            ],
        );

        if($validatedData){
            $this->modal_visible_gral = false;
            $tipo_producto = FormuEncab::find($this->ppal_provisional);
            $tipo_producto->titulo = $this->gral_titulo;
            $tipo_producto->subtitulo = $this->gral_subtitulo;
            $tipo_producto->columnas = $this->gral_columnas;
            $tipo_producto->save();
        }
 
    }

    public function mostrar_modal_texto(){
        $this->reset(['texto_cabecera' , 'texto_obligatorio' ,  'texto_longitud_max'  ]);
        $this->resetValidation();        
        $this->modal_visible_texto = true;
    }      

    public function submit_texto(){
        // grabará en formu_detalles un registro tipo input text: 

        //  $this->validate();
        // no se puede usar $this->validate a nivel global (es decir con 
        // las propiedades rules y messages) debido a que entonces validaria 
        // las condiciones de los otros modales. Por eso se hace asi:
        $validatedData = $this->validate(
            [
                'texto_cabecera' => 'required|max:64',
                'texto_obligatorio' => 'required',
                'texto_longitud_max' => 'required',
            ],
            [
                'texto_cabecera.required' => 'Debe digitar la cabecera que identificará al nuevo elemento.',
                'texto_cabecera.max' => 'La longitud máxima de la cabecera es: 64 caracteres.',
                'texto_obligatorio.required' => 'Debe escoger si el nuevo elemento se debe suministrar o no de forma obligatoria.',
                'texto_longitud_max.required' => 'Debe indicar cual es la longitud máxima de caracteres que se pueden escribir en el nuevo elemento.',
            ],
        );

        if($validatedData){
            $this->modal_visible_texto = false;

            $tipo_producto_id = $this->ppal_provisional;
            $elemento_html = 1;  // input text
            $slug = str_replace('-','_',Str::slug($this->texto_cabecera)); 

            // para saber el orden en que se va: 
                    // $arr_params2 = [];
                    // $sql2 = "select max(orden) orden_max
                    //     from formu_detalles 
                    //     where tipo_producto_id = :tipo_producto_id ";
                    // $arr_params2 = [
                    //     'tipo_producto_id' => $this->ppal_provisional ,
                    // ];
                    // $tipo_productos = collect(DB::select($sql2 , $arr_params2));
                    // $orden = $tipo_productos[0]->orden_max + 1;
            $orden = DB::table('formu_detalles')->max('orden')->where('tipo_producto_id' , $this->ppal_provisional) + 1;

            $nuevo = FormuDetalle::create([
                'tipo_producto_id' => $tipo_producto_id,
                'elemento_html' => $elemento_html,
                'cabecera' => $this->texto_cabecera,
                'slug' => $slug,
                'orden' => $orden,
                'obligatorio' => $this->texto_obligatorio,
                'max_largo' => $this->texto_longitud_max,
            ]);
        }

    }
}
