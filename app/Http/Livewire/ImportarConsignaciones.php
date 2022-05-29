<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Consignacion;
use Storage;

class ImportarConsignaciones extends Component
{

    public $arr_consignaciones;
    public $arr_no_cargadas;
    public $archivo_extracto_nombre;
    public $archivo_provisional_nombre;
    public $no_cargadas_visible;

    public function mount($arr_consignaciones_param , $arr_no_cargadas_param, $extracto_nombre_param, $extracto_provisional_param){
        $this->arr_consignaciones = json_decode($arr_consignaciones_param);
        // Debido a que $arr_no_cargadas_param tenia caracteres que 
        // al ser pasados por url confundian al navegador:
        $arr_no_cargadas_url = json_decode(str_replace('_@_' , '/' , $arr_no_cargadas_param));
        $this->arr_no_cargadas = $arr_no_cargadas_url;

        $this->archivo_extracto_nombre = $extracto_nombre_param;
        $this->archivo_provisional_nombre = $extracto_provisional_param;
        $this->no_cargadas_visible = false;

        // llenado de la columna 'estado' en el array de consignaciones (si existen 
        // observaciones, al estado se le lleva false). Significado: false: no se 
        // debe importar, true: sí se debe importar:
        $fila_contador = 0;
        foreach($this->arr_consignaciones as $fila){
            if(strlen(trim($fila[7])) == 0){
                $this->arr_consignaciones[$fila_contador][8] = true;
            }else{
                $this->arr_consignaciones[$fila_contador][8] = false;
            }
            $fila_contador++;
        }  
    }

    public function render()
    {
        return view('livewire.importar-consignaciones' , [
            'consignaciones' => $this->arr_consignaciones,
            'arr_no_cargadas' => $this->arr_no_cargadas,
            'archivo_extracto_nombre' => $this->archivo_extracto_nombre,
        ]);
    }

    public function importar($consignacion_id , $nuevo_estado){
        // cambia la última columna del gridview entre si/no importar 
        $key = array_search($consignacion_id , array_column($this->arr_consignaciones, 9));
        $this->arr_consignaciones[$key][8] = $nuevo_estado;
    }   

    public function grabar_importar(){
        // Esta función inserta en la tabla 'consignaciones', la información 
        // del gridview ($this->arr_consignaciones) que corresponda a estados: 'importar'
        // $this->mensaje = ''. 
        // Después de grabar en la tabla, graba el archivo de excel en la carpeta
        // storage, en markka-pruebas\storage\app\public\extractos con  
        // el formato: aaaammdd_hhmmss_<nombre del archivo excel>
        // Y por último, redirige al componente ExtractosBancarios

        // graba en la tabla consignaciones: 
        $grabo_consignacion = false;
        foreach($this->arr_consignaciones as $nueva_consignacion){
            if($nueva_consignacion[8]){
                $grabo_consignacion = true;
                $nuevo = Consignacion::create([
                    'original' => $this->archivo_extracto_nombre,
                    'fecha' => $nueva_consignacion[1],
                    'valor' => $nueva_consignacion[6],
                    'estado' => 1,
                    // 'recaudo_id' => ,
                    'user_importo_id' => Auth::user()->id,
                    'created_importo_at' => date('Y-m-d H:i:s'),
                    'documento' => $nueva_consignacion[2],
                    'oficina' => $nueva_consignacion[3],
                    'descripcion' => $nueva_consignacion[4],
                    'referencia' => $nueva_consignacion[5],
                ]);
            }
        }

        if($grabo_consignacion){
            // pasa el archivo excel desde la carpeta temp hacia la verdadera y le 
            // cambia el nombre para que quede con el tiempo (segundos) actual: 
            $archivo_origen = 'public/extractos/temp/' . $this->archivo_provisional_nombre;
            $archivo_destino = 'public/extractos/' . date('Ymdhis') . substr($this->archivo_provisional_nombre , 14);
            Storage::move($archivo_origen , $archivo_destino);
        }
     
        return redirect(url('extractos-bancarios'));


    }  
    
    public function mostrar_modal_no_cargadas(){

        $this->no_cargadas_visible = true;
        // $this->consignacion_id_eliminar = $consignacion_id;
        // $this->fecha_eliminar = $fecha;
        // $this->valor_eliminar = $valor;
        // $this->importo_eliminar = $importo;
        // $this->reset(['archivo_extracto']);
        // $this->resetValidation();   
    }      

}
