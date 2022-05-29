<?php

namespace App\Http\Livewire\Pedidos\ConfigFormu;

use Livewire\Component;
use Illuminate\Validation\Rule;

use App\Models\Pedidos\FormuDetalle;

class ModificarCabeceraCampo extends Component
{

    public $tipo_producto_recibido_id, $tipo_producto_recibido_nombre, $tipo_producto_recibido_slug ;

    public $modal_visible_modificar_cabecera_campo;

    public $formu_detalle_id , $cabecera_campo ;

    // se tuvo que agregar rules() en vez de protected $rules=[], debido a que se debe
    // hacer una regla 'unique validar' en un rango (where) de los registros de la tabla (no
    // de TODOS los registros) y adicionalmente el valor contra el que se compara el 
    // where no es fijo sino que es variable.

    // si la regla es: TODOS los registros, sin importar el tipo_producto_id 
    // protected $rules = [            
    //     'cabecera_campo' => 'required|max:64|unique:formu_detalles,cabecera',
    // ];  

    // si la regla es: ALGUNOS registros y con un mismo tipo_producto_id (ejemplo: 13): 
    // protected $rules = [
    //     'cabecera_campo' => 'required|max:64|unique:formu_detalles,cabecera,NULL,id,tipo_producto_id,13',
    // ];
    
    // Pero como la regla es: ALGUNOS registros y NO siempre será el mismo tipo_producto_id:
    public function rules(){
        return [ 
            'cabecera_campo' =>  [ 
                'required', 
                'max:64',
                'unique:palabras_reservadas,palabra',  // para evitar uso de palabras reservadas mysql
                Rule::unique('formu_detalles' , 'cabecera')
                    ->where('tipo_producto_id', $this->tipo_producto_recibido_id),  // para evitar que en un mismo tipo prodcuto se usen dos cabeceras iguales
            ] 
        ]; 
    }

    protected $messages = [
        'cabecera_campo.required' => 'Debe digitar el nombre del elemento.',
        'cabecera_campo.max' => 'El nombre del elemento no puede tener más de 64 caracteres.',
        'cabecera_campo.unique' => 'El nombre de elemento digitado ya está siendo usado.',
    ];     

    public function mount($formu_detalle_id , $cabecera_actual , $tipo_producto_recibido_id  , $tipo_producto_recibido_nombre , $tipo_producto_recibido_slug){
        $this->formu_detalle_id = $formu_detalle_id; 
        $this->cabecera_campo = $cabecera_actual; 
        $this->tipo_producto_recibido_id = $tipo_producto_recibido_id; 
        $this->tipo_producto_recibido_nombre = $tipo_producto_recibido_nombre; 
        $this->tipo_producto_recibido_slug = $tipo_producto_recibido_slug; 

        $this->modal_visible_modificar_cabecera_campo = true;

    }

    public function render()
    {
        return view('livewire.pedidos.config-formu.modificar-cabecera-campo');
    }

 

    public function cerrar_modal_modificar_cabecera_campo(){
        $this->modal_visible_modificar_cabecera_campo = false;
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }

    public function submit_modificar_cabecera_campo(){
        $this->validate();
        $this->modal_visible_modificar_cabecera_campo = false; 
        // Modificar el campo cabecera de la tabla formu_detalles
        $formu_detalles_registro = FormuDetalle::find($this->formu_detalle_id);
        $formu_detalles_registro->cabecera = $this->cabecera_campo;
        $formu_detalles_registro->save();
        
        return redirect(url('generar-formu-index' , [
            'tipo_producto_recibido_id' => $this->tipo_producto_recibido_id,
            'tipo_producto_recibido_nombre' => $this->tipo_producto_recibido_nombre,
            'tipo_producto_recibido_slug' => $this->tipo_producto_recibido_slug,
        ]));
    }   
}
