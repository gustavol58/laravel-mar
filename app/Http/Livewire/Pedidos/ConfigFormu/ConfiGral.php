<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use App\Models\Pedidos\FormuTipoProducto;

class ConfiGral extends Component
{
    public $encab_titulo, $encab_subtitulo, $encab_columnas;
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug;

    public $modal_visible_gral;

    public $gral_titulo;
    public $gral_subtitulo;
    public $gral_columnas;

    protected $rules = [
        'gral_columnas' => 'numeric|required|min:1|max:4',
    ];  

    protected $messages = [
        'gral_columnas.required' => 'Debe digitar el número de columnas.',
        'gral_columnas.numeric' => 'El número de columnas debe ser numérico.',
        'gral_columnas.min' => 'El número de columnas debe ser mayor o igual a 1',
        'gral_columnas.max' => 'El máximo número de columnas es 4',
    ];     

    public function mount($encab_titulo , $encab_subtitulo, $encab_columnas, $tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->gral_titulo = $encab_titulo;
        $this->gral_subtitulo = $encab_subtitulo;
        $this->gral_columnas = $encab_columnas;
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_gral = true;
    }

    public function render()
    {
        
        return view('livewire.pedidos.config-formu.confi-gral' );
    }

    public function mostrar_modal_gral($titulo , $subtitulo , $columnas){
        // para que al abrir el modal muestre lo que está en la b.d.: 
        $this->gral_titulo = $titulo;
        $this->gral_subtitulo = $subtitulo;
        $this->gral_columnas = $columnas;
        $this->resetValidation(); 
        $this->modal_visible_gral = true;
    }      

    public function cerrar_modal_gral(){
        $this->modal_visible_gral = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function submit_gral(){
        // Graba en la tabla formu_tipo_productos el título y subtítulo,

        $this->validate();

        $this->modal_visible_gral = false;
        
        $tipo_producto = FormuTipoProducto::find($this->tipo_producto_recibido_id);
        $tipo_producto->titulo = $this->gral_titulo;
        $tipo_producto->subtitulo = $this->gral_subtitulo;
        $tipo_producto->columnas = $this->gral_columnas;
        $tipo_producto->save();

        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
 
    }    
}
