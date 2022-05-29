<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;

use App\Models\Pedidos\FormuTipoProducto;
use App\Models\Pedidos\FormuTabla;

class ModificarNombreLargo extends Component
{

    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_modificar_nombre_largo;

    public $nombre_largo;  

    protected $rules = [            
        'nombre_largo' => 'required|max:254|unique:formu_tipo_productos,tipo_producto_nombre',
    ];  

    protected $messages = [
        'nombre_largo.required' => 'Debe digitar el nombre del tipo de producto.',
        'nombre_largo.max' => 'El nombre del tipo de producto no puede tener más de 254 caracteres.',
        'nombre_largo.unique' => 'El tipo de producto digitado ya existe.',
    ];     
    
    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_modificar_nombre_largo = true; 
        $this->nombre_largo = $this->tipo_producto_recibido_nombre;

// dd($this->tipo_producto_recibido_nombre);        
    }    


    public function render()
    {
        return view('livewire.pedidos.config-formu.modificar-nombre-largo');
    }

   

//     public function mostrar_modal_modificar_nombre_largo(){
// dd($this->tipo_producto_recibido_nombre)   ;     
//         $this->nombre_largo = $this->tipo_producto_recibido_nombre;
//         // $this->resetValidation();        
//         $this->modal_visible_modificar_nombre_largo = true;
//     }      

    public function cerrar_modal_modificar_nombre_largo(){
        $this->modal_visible_modificar_nombre_largo = false;
        return redirect(url('generar-formu-admin' ));
    }

    public function submit_modificar_nombre_largo(){
        $this->validate();
        $this->modal_visible_modificar_nombre_largo = false; 

        // Modificar el campo tipo_producto_nombre de la tabla formu_tipo_productos
        $tipo_producto_registro = FormuTipoProducto::find($this->tipo_producto_recibido_id);
        $tipo_producto_registro->tipo_producto_nombre = $this->nombre_largo;
        $tipo_producto_registro->save();

        // también se debe grabar el nombre en la tabla formu_tablas (ver el comentario 
        // dentro de esa tabla, del porque existe esta pequeña infracción a las
        // buenas prácticas de base de datos): 
        $formu_tabla_registro = FormuTabla::where('tipo_producto_id' , $this->tipo_producto_recibido_id)->first();
        $formu_tabla_registro->titulo = $this->nombre_largo;
        $formu_tabla_registro->save();
        
        // regresa al gridview de tipos de producto
        return redirect()->route('generar-formu-admin');
    }


}
