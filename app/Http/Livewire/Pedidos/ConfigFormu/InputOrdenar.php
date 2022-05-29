<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use App\Models\Pedidos\FormuDetalle;
use Illuminate\Support\Facades\DB;


class InputOrdenar extends Component
{
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_orden;

    public $obj_orden;

    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_orden = true;
        $obj_formu_detalle = new FormuDetalle();
        $this->obj_orden = $obj_formu_detalle->leer_cabeceras_orden($this->tipo_producto_recibido_id);
    }

    public function render()
    {
        return view('livewire.pedidos.config-formu.input-ordenar');
    }

    public function mostrar_modal_orden(){
        // $this->reset(['texto_cabecera' , 'texto_obligatorio' ,  'texto_longitud_max'  ]);
        // $this->resetValidation();        
        $this->modal_visible_orden = true;
    }      

    public function cerrar_modal_orden(){
        $this->modal_visible_orden = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }   
    
    public function subir_array($indice_subir){
        $fila_aux = $this->obj_orden[$indice_subir - 1];
        $this->obj_orden[$indice_subir - 1] = $this->obj_orden[$indice_subir];
        $this->obj_orden[$indice_subir] = $fila_aux;
    }

    public function bajar_array($indice_bajar){
        $fila_aux = $this->obj_orden[$indice_bajar + 1];
        $this->obj_orden[$indice_bajar + 1] = $this->obj_orden[$indice_bajar];
        $this->obj_orden[$indice_bajar] = $fila_aux;
    }

    public function submit_orden(){
        // Actualiza el orden en la tabla formu_detalles, según lo 
        // re-ordenado por el usuario en el obj_orden: 
        $this->modal_visible_orden = false;

        $conta = 1;
        foreach ($this->obj_orden as $key => $item1){
            $un_detalle = FormuDetalle::find($item1['detalle_id']);
            $un_detalle->orden = $conta;
            $un_detalle->save();
            $conta++;
        }
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));

    }
    
}
