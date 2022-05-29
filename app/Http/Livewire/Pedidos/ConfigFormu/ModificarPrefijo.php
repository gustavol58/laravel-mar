<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;

use App\Models\Pedidos\FormuTipoProducto;


class ModificarPrefijo extends Component
{
    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_modificar_prefijo;

    public $prefijo;

    protected $rules = [            
        'prefijo' => 'nullable|min:2|max:234|unique:formu_tipo_productos,prefijo',
    ];  

    protected $messages = [
        'prefijo.min' => 'El prefijo no puede tener menos de 2 letras mayúsculas.',
        'prefijo.max' => 'El prefijo no puede tener más de 234 letras mayúsculas.',
        'prefijo.unique' => 'El prefijo digitado ya existe.',
    ];    
    
    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug , $tipo_producto_recibido_prefijo = null){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->prefijo = $tipo_producto_recibido_prefijo;
        $this->modal_visible_modificar_prefijo = true; 
// dd($this->tipo_producto_recibido_nombre);        
    }      

    public function render()
    {

        return view('livewire.pedidos.config-formu.modificar-prefijo');
    }

    public function cerrar_modal_modificar_prefijo(){
        $this->modal_visible_modificar_prefijo = false;
        return redirect(url('generar-formu-admin' ));
    }    

    public function submit_modificar_prefijo(){
        $this->validate();
        $this->modal_visible_modificar_prefijo = false; 

        // Modificar el campo prefijo de la tabla formu_tipo_productos
        $tipo_producto_registro = FormuTipoProducto::find($this->tipo_producto_recibido_id);
        $tipo_producto_registro->prefijo = $this->prefijo;
        $tipo_producto_registro->save();

        // regresa al gridview de tipos de producto
        return redirect()->route('generar-formu-admin');
    }    
}
