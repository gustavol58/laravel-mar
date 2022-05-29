<?php

namespace App\Http\Livewire;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Recaudo;

use Livewire\Component;

class AprobarRecaudos extends Component
{
    public $arr_aprobados;
    public $fila_aprobado;
    public $foto_existe_nombre; 
    public $foto_modal_visible;   
    public $foto_modal_src;      

    public function mount(){
        $this->foto_modal_visible = false;          
        // la razón de ser del mount() es inicializar el arr_aprobados, el cual lleva cuenta 
        // de las aprobaciones que repetidamente se hacen a medida que el usuario
        // aprueba recaudos y presiona el botón grabar.
        $sql1 = "SELECT rec.id,
                rec.categoria,
                cli.nom_cliente cliente, 
                rec.fec_pago, 
                rec.valor, 
                rec.tipo,
                rec.obs, 
                usu.name usu_creacion, 
                rec.created_at fec_creacion,
                rec.estado
            FROM recaudos rec 
                left join clientes cli on cli.id=rec.cliente_id 
                left join users usu on usu.id=rec.user_id 
            where rec.estado in (1 , 2)";
        $recaudos_para_aprobar = DB::select($sql1);
        foreach($recaudos_para_aprobar as $recaudo){
            $arr_['id'] = $recaudo->id;
            $arr_['estado'] = $recaudo->estado;
            $this->arr_aprobados[] = $arr_;
        }    
// echo "<pre>";
// print_r($this->arr_aprobados);
// exit;            
    }

    public function render()
    {
        $sql1 = "SELECT rec.id,
                rec.categoria,
                cli.nom_cliente cliente, 
                rec.fec_pago, 
                rec.valor, 
                rec.tipo,
                rec.obs, 
                usu.name usu_creacion, 
                rec.created_at fec_creacion,
                rec.estado
            FROM recaudos rec 
                left join clientes cli on cli.id=rec.cliente_id 
                left join users usu on usu.id=rec.user_id 
            where rec.estado in (1 , 2)";
        $recaudos_para_aprobar = DB::select($sql1);

        if(count($recaudos_para_aprobar) == 0){
            $nuevos = 0;
            $aprobados = 0;
        }else{
            $arr_resumen = array_count_values(array_column($this->arr_aprobados, 'estado'));
            $nuevos = 0;
            $aprobados = 0;
            if(isset($arr_resumen[1])){
                $nuevos = $arr_resumen[1];
            }
            if(isset($arr_resumen[2])){
                $aprobados = $arr_resumen[2];
            }
        }

        // 08mar2021: 
        // para determinar las categorias texto  y tipos texto  de cada recaudo, 
        // de modo que en el blade no se tengan que hacer ifs, adicionalmente
        // para determinar por cada recaudo si tiene o no foto en el hosting:
        foreach($recaudos_para_aprobar as $un_recaudo){
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

        return view('livewire.aprobar-recaudos'  , compact('recaudos_para_aprobar' , 'nuevos' , 'aprobados'));      
    }

    public function aprobar($recaudo_id , $nuevo_estado){
        $key = array_search($recaudo_id , array_column($this->arr_aprobados, 'id'));
        $this->arr_aprobados[$key]['estado'] = $nuevo_estado;
    }    

    public function grabar_aprobar(){
        // Esta función actualiza en 'estado' de la tabla recaudos, la información 
        // que se encuentra en el 
        // array de aprobados. Luego vuelve a la pantalla inicial.
        // $this->mensaje = '';
        foreach($this->arr_aprobados as $recaudo_nuevo){
            $recaudo = Recaudo::find($recaudo_nuevo['id']);
            $recaudo->estado = $recaudo_nuevo['estado'];
            $recaudo->user_aprobo_id = Auth::user()->id;
            $recaudo->created_aprobo_at = date('Y-m-d H:i:s');
            $recaudo->save();
        }
        // por alguna razón la siguiente instrucción no funcionó correctamente,
        // (nunca hacia el redireccionamiento, tal vez lo hacia pero volvia 
        // a renderizar el blade), la solución fue usar href en conjunción 
        // con button, como se puede ver en el blade.php
        // return redirect()->route('dashboard');
    }  
    
    public function mostrar_modal_foto($foto){
        $this->foto_modal_visible = true;
        $this->foto_modal_src = $foto;
    }    
}
