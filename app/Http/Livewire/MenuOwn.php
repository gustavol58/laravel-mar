<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class MenuOwn extends Component
{
    public function render()
    {
        // 25abr2022:
        // Para mostrar en el menú "Ayuda" la lista de pdfs: 
        $sql = "SELECT titulo,nombre_interno,permisos FROM ayudas_pdfs where activo order by orden";
        $arr_ayudas_pdfs = (array)(DB::select($sql));        
        return view('livewire.menu-own' , ['arr_ayudas_pdfs' => $arr_ayudas_pdfs]);
    }


}
