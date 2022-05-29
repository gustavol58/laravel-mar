<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use App\Models\Pedidos\FormuDetalle;
use App\Models\Pedidos\FormuCampo;
use App\Models\Pedidos\FormuContenidosMultivariable;
use App\Models\Pedidos\FormuDetallesMultivariable;
use App\Models\Pedidos\FormuListaValore;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InputEliminar extends Component
{
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_eliminar;

    public $obj_eliminar;

    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_eliminar = true;
        $obj_formu_detalle = new FormuDetalle();
        $this->obj_eliminar = $obj_formu_detalle->leer_cabeceras_eliminar($this->tipo_producto_recibido_id);
        // revisa en la base de datos cuales campos de la tabla ya tienen valores asignados, 
        // para determinar cuales se pueden eliminar y cuáles no, y de acuerdo a esto llena 
        // el campo 'eliminar' en el objeto $this->obj_eliminar:
        $this->determinar_eliminar();
    }    

    public function render()
    {
        return view('livewire.pedidos.config-formu.input-eliminar');
    }

    public function mostrar_modal_eliminar(){
        $this->modal_visible_eliminar = true;
    }      

    public function cerrar_modal_eliminar(){
        $this->modal_visible_eliminar = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }   
    
    public function marcar_para_eliminar($indice){
        foreach($this->obj_eliminar as $key => $item){
            if($key == $indice){
                $aux_ = $this->obj_eliminar[$key];
                $aux_['marca_eliminar'] = !$aux_['marca_eliminar'];
                $this->obj_eliminar[$key] = $aux_;
            }
        }
    }

    public function submit_eliminar(){
        // Recorre $this->obj_eliminar, y para los elementos que hayan 
        // sido marcados para eliminar:
        //  1.  eliminar el registro de formu_detalles 
        //      (y también de formu_detalles_multivariables, si aplica) 
        //  Si no se trata de una nueva sección o de campo multivariable:
        //      2. eliminar el registro de formu_campos 
        //      3. Eliminar el campo de la tabla formu__.......
        $this->modal_visible_eliminar = false;
// dd($this->obj_eliminar);
        foreach($this->obj_eliminar as $key => $item){
            if($item['marca_eliminar']){
                $html_elemento_id = $item['html_elemento_id'];

                // 09oct2021:
                // Si el campo es MULTIVARIABLE, hay que borrar los 
                // 'hijos' (columnas del multivariable) que existan en
                // la tabla formu_detalles_multivariables: 
                if($html_elemento_id == 12){
                    $detalle_multivariable_borrar = FormuDetallesMultivariable::where('formu_detalles_id' , $item['detalle_id']);
                    $detalle_multivariable_borrar->delete();                    
                }

                if($html_elemento_id == 3){
                    // 12oct2021:
                    // Si el campo es LISTA DESDE VALORES, hay que borrar los 
                    // 'valores' que existan en la tabla formu_lista_valores, para hacerlo lo 
                    // primero es determinar el nombre de la tabla formu__.... y el slug 
                    // del campo, se determinan con ayuda de las tablas formu_detalles 
                    // y formu_tipo_productos:
                    $arr_params = [];
                    $sql = "SELECT concat('formu__',ftip.tipo_producto_slug) formu__tabla, 
                            fdet.slug formu__campo 
                        FROM `formu_detalles` fdet 
                            left join formu_tipo_productos ftip on ftip.id=fdet.tipo_producto_id 
                        WHERE fdet.id=:formu_detalle_id ";
                    $arr_params = [
                        ':formu_detalle_id' =>  $item['detalle_id'],
                    ];
                    $coll_html_elemento_id = collect(DB::select($sql , $arr_params));
                    $aux_formu__tabla =  $coll_html_elemento_id[0]->formu__tabla;                    
                    $aux_formu__campo =  $coll_html_elemento_id[0]->formu__campo;                    

                    // y ahora si, el borrado en la tabla formu_lista_valores:
                    $detalle_valores_borrar = FormuListaValore::where('formu__tabla' , $aux_formu__tabla)->where('formu__campo' , $aux_formu__campo);
                    $detalle_valores_borrar->delete();                    
                }



                // 1. eliminar el registro de formu_detalles: 
                $detalle_borrar = FormuDetalle::find($item['detalle_id']);
                $detalle_borrar->delete();

                // Verificar que el elemento no sea ni nueva sección ni multivariable,
                // porque si lo es no existe ni en formu_campos ni en la
                // estructura de formu__.........
                if($html_elemento_id !== 8 && $html_elemento_id !== 12){
                    // 2. eliminar el registro de formu_campos
                    $campo_borrar = FormuCampo::find($item['campo_id']);
                    $campo_borrar->delete();

                    // 3. Eliminar el campo de la tabla formu__....... 
                    $tabla_formu__ = "formu__" . $this->tipo_producto_recibido_slug;
                    $slug = $item['campo'];
                    // 19jun2021 
                    // Si de pronto el campo a eliminar es foreign key,  hay que desactivar los
                    // foreing keys de mysql antes de agregarlo:
                    Schema::disableForeignKeyConstraints();    
                    Schema::table($tabla_formu__ , function (Blueprint $table) use ($slug , $tabla_formu__ , $html_elemento_id) {
                        if($html_elemento_id == 10){
                            // el tipo elemento 10 (lista desde tablas) tiene foreing key asociada: 
                            // Para poder borrar una columna que sea foreign key, primero se debe 
                            // borrar el foreign key:                   
                            $table->dropForeign($tabla_formu__.'_'.$slug.'_foreign');                 
                        }
                        $table->dropColumn($slug);
                    });  
                    Schema::enableForeignKeyConstraints();                   
                }
               
            }
        }

        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));

    }    

    private function determinar_eliminar(){
        // Determina cuáles de los campos que existan en  $this->obj_eliminar 
        // pueden ser eliminados o no. El criterio para ser eliminados es que 
        // el usuario no haya grabado todavia info en estos campos.

        // 09oct2021: 
        // Determinar si el usuario ya grabo info o no en cada campo, depende del
        // tipo de elemento_html, asi: 
        //      TIPO                CONTENIDO SI NO SE HA LLENADO INFO 
        //      1,2,6,7,9,10,11     NULL 
        //      3,4,5               CERO
        //      8                   No aplica porque no es campo sino nueva sección 
        //      12                  Para los multivariables se explica dentro del if

// dd($this->obj_eliminar);

        // Se lleva TRUE a la segunda columna de $this->obj_eliminar para aquellos campos 
        // que aun esten vacios:
        foreach($this->obj_eliminar as $key => $item){
            if($item->html_elemento_id !== 8 && $item->html_elemento_id !== 12){
                if($item->html_elemento_id == 3 
                        || $item->html_elemento_id == 4
                        || $item->html_elemento_id == 5){
                    $valor_where = " <> 0 ";
                }else{
                    $valor_where = " is not null ";
                }
                $sql = "select count(1) nro_rgtos 
                    from formu__" . $this->tipo_producto_recibido_slug . " 
                    where " . $item->campo . $valor_where;   
                $obj_resultado = collect(DB::select($sql));
                if($obj_resultado[0]->nro_rgtos == 0){
                    $this->obj_eliminar[$key]->eliminar = true;
                }                  
            }else if($item->html_elemento_id == 8){
                // Es una nueva sección:
                $this->obj_eliminar[$key]->eliminar = true;
            }else if($item->html_elemento_id == 12){
                // Para campos multivariables: No se debe buscar en formu__.... sino en la 
                // tabla formu_contenidos_multivariables, si allí hay registros que concuerdan 
                // en su campo campo_detalle_id con 'detalle_id'  de $this->obj_eliminar
                $arr_multivariable = FormuContenidosMultivariable::where('campo_detalle_id' , $item->detalle_id)->get()->toArray();
                if(count($arr_multivariable) == 0){
                    $this->obj_eliminar[$key]->eliminar = true;
                }
            }
        }
    }
}
