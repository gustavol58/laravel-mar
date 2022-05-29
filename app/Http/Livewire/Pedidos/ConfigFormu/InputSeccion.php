<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Pedidos\FormuDetalle;

class InputSeccion extends Component
{

    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug;

    public $modal_visible_seccion;

    public $seccion_cabecera;    

    // se tuvo que agregar rules() en vez de protected $rules=[], debido a que se debe
    // hacer una regla unique para dos campos de la tabla (cabecera y tipo_producto_id)
    public function rules(){
        return [ 
            'seccion_cabecera' =>  [
                'required', 
                Rule::unique('formu_detalles' , 'cabecera')->where('tipo_producto_id', $this->tipo_producto_recibido_id),
            ] ,  
        ];
    }    

    protected $messages = [
        'seccion_cabecera.required' => 'Debe digitar el nombre de la sección.',
        'seccion_cabecera.unique' => 'El nombre de sección ya esta siendo utilizado.',
    ]; 
    
    public function mount($tipo_producto_recibido_id , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id;
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre;
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug;
        $this->modal_visible_seccion = true;
    }    

    public function render()
    {
        return view('livewire.pedidos.config-formu.input-seccion');
    }

    public function mostrar_modal_seccion(){
        $this->reset(['seccion_cabecera']);
        $this->resetValidation();        
        $this->modal_visible_seccion = true;
    }      

    public function cerrar_modal_seccion(){
        $this->modal_visible_seccion = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function submit_seccion(){
        // grabará en formu_detalles un registro tipo nueva seccion: 
        $this->validate();
        $this->modal_visible_seccion = false;

        $tipo_producto_id = $this->tipo_producto_recibido_id;
        $elemento_html = 8;  // 8 es nueva sección

        $obj_formu_detalle = new FormuDetalle();
        // para saber el orden en que se va: 
        $orden = $obj_formu_detalle->obtener_siguiente_orden($tipo_producto_id);
        // para armar el slug:
        $slug = $obj_formu_detalle->obtener_slug_formu_detalles($this->seccion_cabecera , $orden);      

        $obligatorio = true;
        $roles_grabar = 'admin';

        $nuevo = FormuDetalle::create([
            'tipo_producto_id' => $tipo_producto_id,
            'html_elemento_id' => $elemento_html,
            'cabecera' => $this->seccion_cabecera,
            'slug' => $slug,
            'orden' => $orden,
            'roles' => $roles_grabar,
            'obligatorio' => $obligatorio,
            'user_id' => Auth::user()->id
        ]);

        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));

    }       
}
