<?php

namespace App\Http\Livewire\Pedidos\Formu;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedidos\FormuTipoProducto;


class EscogerTipo extends Component
{
    // 16oct2021 public $modal_visible_tipoproducto = true;
    // public $combo_tipoproducto;

    // protected $rules = [            
    //     'combo_tipoproducto' => 'required',
    // ];  

    // protected $messages = [
    //     'combo_tipoproducto.required' => 'Debe escoger un tipo de producto.',
    // ];       

    public function render()
    {
        // Debe leer los tipos de producto que al menos tengan un campo agregado 
        // desde la configuración del formulario: 

        // 16oct2021: 
        // A los Tipos de producto que solo son "tablas info" (por ejemplo 
        // listados formatos) el único que le agrega productos es el usuario admin:
        if(Auth::user()->hasRole('admin')){
            $where_prefijos = "  ";  

        }else{
            $where_prefijos = " and f_tp.prefijo is not null ";  
        }   
        // 05nov2021: 
        // Para que en el gridview Mtto productos muestre tipos de producto que 
        // concuerde con los roles según el usuario logueado:
        $sql = "select distinct(f_tp.id) id , 
                f_tp.tipo_producto_nombre ,
                f_tp.tipo_producto_slug ,
                f_tp.prefijo,
                (SELECT distinct group_concat(roles SEPARATOR '_@@@_') 
                    FROM formu_detalles 
                    where tipo_producto_id=f_tp.id 
                    group by tipo_producto_id
                ) roles_tipo_producto
            from formu_tipo_productos f_tp 
                left join formu_detalles f_det on f_det.tipo_producto_id=f_tp.id 
            where f_tp.id <> 1 and f_det.id is not null " 
                . $where_prefijos .
            "order by f_tp.tipo_producto_nombre ";
            
        $tipos_producto = collect(DB::select($sql));

        // 05nov2021: 
        // Antes de llamar el blade, se debe transformar el campo 'roles_tipo_producto' 
        // para que de algo como esto "admin_@@@_comer_@@@_admin_@@@_comer_@@@_admin_@@@_disen"
        // se obtengan los roles no repetidos, asi:"admin_@@@_comer_@@@_disen"
        foreach ($tipos_producto as $key => $fila_roles_tipo_producto) {
            $arr_aux_roles_tipo_producto = explode('_@@@_' , $fila_roles_tipo_producto->roles_tipo_producto);
            $arr_aux_roles_tipo_producto_unicos = array_unique($arr_aux_roles_tipo_producto);
            $tipos_producto[$key]->roles_tipo_producto = implode('_@@@_' , $arr_aux_roles_tipo_producto_unicos);
        }

        // 05nov2021:
        // Una vez transformado el campo 'roles_tipo_producto', se debe recorrer la 
        // colección y eliminar de alli los registros que no correspondan al 
        // rol de usuario que está logueado:
        foreach($tipos_producto as $key => $fila_tipos_producto){
            if(in_array(Auth::user()->roles[0]->name , explode('_@@@_' , $fila_tipos_producto->roles_tipo_producto))){
                // el tipo producto tiene un rol que corresponde al usuario logueado
            }else{
                // el tipo producto tiene un rol que corresponde al usuario logueado, por lo
                // tanto este tipo de producto no debe aparecer en el gridview del usuario:
                unset($tipos_producto[$key]);
            }
        }        
// dd($tipos_producto);        
        return view(
            'livewire.pedidos.formu.escoger-tipo' , 
            compact('tipos_producto')
        );
    }

    // public function mostrar_modal_tipoproducto(){
    //     $this->reset(['combo_tipoproducto' ]);
    //     $this->resetValidation();        
    //     //16oct2021 $this->modal_visible_tipoproducto = true;
    // }      

    // public function cerrar_modal_tipoproducto(){
    //     // 16oct2021 $this->modal_visible_tipoproducto = false;
    //     return redirect(url('/dashboard'));
    // }    

    // public function submit_tipoproducto(){
    //     // llamara el componente livewire correspondiente al Tipo de 
    //     // productos que se haya escogido 
    //     return redirect(url('ver-formu' , [
    //         'tipoproducto_id' => $this->combo_tipoproducto,
    //     ]));
       
    // }
}
