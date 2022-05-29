<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Recaudo;


class AsentarRecaudos extends Component
{
    public $arr_asientos;
    public $fila_asiento;
    public $mensaje;
    public $foto_existe_nombre; 
    public $foto_modal_visible;   
    public $foto_modal_src;   
 

    // protected $listeners = ['refreshProducts' => '$refresh'];
    // protected $listeners = ['sectionAdded' => 'render'];


    public function mount(){
        $this->foto_modal_visible = false;   

        // la razón de ser del mount() es inicializar el arr_asientos, el cual lleva cuenta 
        // de los movimientos de asientos que repetidamente se hacen a medida que el usuario
        // asiente recaudos antes de presionar el botón grabar.
        $sql1 = "SELECT rec.id,
                rec.categoria,
                cli.nom_cliente cliente, 
                rec.fec_pago, 
                rec.valor, 
                rec.tipo,
                rec.obs, 
                usu.name usu_creacion, 
                rec.created_at fec_creacion 
            FROM recaudos rec 
                left join clientes cli on cli.id=rec.cliente_id 
                left join users usu on usu.id=rec.user_id 
            where rec.estado = 2";
        $recaudos_sin_asentar = DB::select($sql1);
        foreach($recaudos_sin_asentar as $recaudo){
            $arr_['id'] = $recaudo->id;
            $arr_['categoria'] = $recaudo->categoria;
            $arr_['valor_recaudo'] = $recaudo->valor;
            $arr_['asentado'] = false;
            $arr_['notas_asiento'] = '';
            $arr_['valor_asiento'] = '';

            $this->arr_asientos[] = $arr_;
        }        
    }

    public function render()
    {
        $sql1 = "SELECT rec.id, rec.categoria, cli.nom_cliente cliente, 
                rec.fec_pago, 
                rec.valor, 
                rec.tipo,
                rec.obs, 
                usu.name usu_creacion, 
                rec.created_at fec_creacion 
            FROM recaudos rec 
                left join clientes cli on cli.id=rec.cliente_id 
                left join users usu on usu.id=rec.user_id 
            where rec.estado = 2";
        $recaudos_sin_asentar = DB::select($sql1);

        // 08mar2021: 
        // para determinar las categorias texto  y tipos texto  de cada recaudo, 
        // de modo que en el blade no se tengan que hacer ifs, adicionalmente
        // para determinar por cada recaudo si tiene o no foto en el hosting:
        foreach($recaudos_sin_asentar as $un_recaudo){
            switch ($un_recaudo->categoria) {
                case 1:
                    $un_recaudo->categoria_texto = 'Anticipo';
                    break;
                case 2:
                    $un_recaudo->categoria_texto = 'Pago facturas';
                    break;
                default:
                    $un_recaudo->categoria_texto = '';
                    break;                    
            }             
            switch ($un_recaudo->tipo) {
                case 1:
                    $un_recaudo->tipo_texto = 'Efect';
                    break;
                case 2:
                    $un_recaudo->tipo_texto = 'Consig';
                    break;
                default:
                    $un_recaudo->tipo_texto = '';
                    break;                    
            }   

            // 07mar2021 determinar si al recaudo se le subio foto o no: 
            $this->foto_existe_nombre = '';
            $ruta = '';
            if (Storage::disk('public')->exists('comptes/'.$un_recaudo->id)){
                // en el locahost public_path() quedará con /var/www/html/markka/public 
                // pero en un hosting compartido quedará con /home/tavohenc/markka_pr/public y debido 
                // a que en el hosting compartido la carpeta publica va en un lado (public_html/markka) y los demás 
                // archivos van en otro (markka), entonces lo que sirve para el localhost no sirve para el hosting 
                //      para el localhost sirve /var/www/html/markka/public 
                //      para el hosting compartido sirve /home/tavohenc/public_html/markka_pr
                // 07mar2021:
                // Todo lo anterior se solucionó usando constantes laravel:
                $public_path = config('constantes.path_foto_compte');
                $ruta = $public_path.'/storage/comptes/'.$un_recaudo->id.'/';
     
                $filehandle = opendir($ruta);
                while ($file = readdir($filehandle)) {
                    if ($file != "." && $file != ".." ) {
                        $foto_existe_nombre_completo = $ruta.$file;
                    }
                }
                closedir($filehandle);  
                if($foto_existe_nombre_completo !== ''){
                    $arr_foto_existe_nombre_completo = explode('/' , $foto_existe_nombre_completo);
                    $this->foto_existe_nombre = 'comptes/' . $un_recaudo->id . '/' . end( $arr_foto_existe_nombre_completo )  ;
                }
            }  
            // si existe foto, enseguida se grabará su ruta y nombre, en caso 
            // contrario la variable seguira teniendo '' :
            $un_recaudo->foto_existe_nombre = $this->foto_existe_nombre;             
            $un_recaudo->foto_existe_nombre_path = config('constantes.path_inicial_foto_modal') . $this->foto_existe_nombre;             
        }

        return view('livewire.asentar-recaudos' , compact('recaudos_sin_asentar'));
    }

    public function asentar($recaudo_id){
        $key = array_search($recaudo_id , array_column($this->arr_asientos, 'id'));
        $this->arr_asientos[$key]['asentado'] = !$this->arr_asientos[$key]['asentado'];
    }

    public function grabar(){
// echo "en grabar, este es arr_asientos: ";
// echo "<pre>";
// print_r($this->arr_asientos);
// exit;
        // Esta función actualiza en 'estado' , 'notas_asiento' 
        // y 'valor_asiento' de la tabla recaudos, la información que se encuentra en el 
        // array de asientos. Luego vuelve a la pantalla de asentar recaudos.
        $this->mensaje = '';
        foreach($this->arr_asientos as $recaudo_nuevo){
        
            if($recaudo_nuevo['asentado']){
                if($recaudo_nuevo['valor_asiento'] !== ''){
                    if($recaudo_nuevo['valor_recaudo'] == $recaudo_nuevo['valor_asiento']){
                        $grabar = true;
                    }else{
                        if(strlen(trim($recaudo_nuevo['notas_asiento'])) !== 0){
                            $grabar = true;
                        }else{
                            $grabar = false;
                        }
                    }
                    if($grabar){
                        $recaudo = Recaudo::find($recaudo_nuevo['id']);
                        $recaudo->estado = 3;
                        $recaudo->notas_asiento = $recaudo_nuevo['notas_asiento'];
                        $recaudo->valor_asiento = $recaudo_nuevo['valor_asiento'];
                        $recaudo->user_asiento_id = Auth::user()->id;
                        $recaudo->created_asiento_at = date('Y-m-d H:i:s');
                        $recaudo->save();
                    }else{
                        $this->mensaje = $this->mensaje . "Si el valor de asiento es diferente al valor del recaudo, obligatoriamente se debe escribir la explicación en la columna: Notas al asiento. ";
                    }
                }else{
                    $this->mensaje = $this->mensaje . "Los valores de asiento no se pueden dejar en blanco. ";
                }

            }

        }
    }

    public function mostrar_modal_foto($foto){
        $this->foto_modal_visible = true;
        $this->foto_modal_src = $foto;
    }
}
