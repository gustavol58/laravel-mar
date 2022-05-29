<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class InfoRecaudos extends Component
{

    public $nro;
    public $estado;
    public $categoria;
    public $nom_cliente; 
    public $comercial;
    public $valor_recaudo;
    public $tipo;
    public $fec_pago;
    public $obs;
    public $valor_asiento;
    public $notas_asiento;
    public $valor_diferencia;
    public $fec_creado;
    public $usu_creado;
    public $fec_aprobado;
    public $usu_aprobado;
    public $fec_asentado;
    public $usu_asentado;
    public $fec_anulado;
    public $usu_anulado;
    public $foto_existe_nombre; 
    public $foto_modal_visible;   
    public $foto_modal_src;     

    public $ordenar_campo;
    public $ordenar_tipo;    

    public $fec_ini;
    public $fec_fin;

    public $arr_dias_semana;
    public $arr_meses;

    public $formu_cambiar_fechas;
    public $fec_desde;
    public $fec_hasta;

    public $sql1;

    public function mount(){
        $this->foto_modal_visible = false;  

        $this->estado = '';
        $this->categoria = '';
        $this->tipo = '';
        $this->obs = '';
        $this->notas_asiento = '';
        $this->ordenar_campo = 'rec.id';
        $this->ordenar_tipo = ' desc';

        $this->fec_ini = date('Y')."-".date('m')."-01";
        $this->fec_fin = date('Y-m-d');
        $this->arr_dias_semana = ['Domingo', 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
        $this->arr_meses=['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

        $this->formu_cambiar_fechas = false;
// echo "<pre>";
// print_r(Auth::user()->can('modificar-recaudos'));
// echo "rol del usuario_";
// print_r(Auth::user()->hasRole('admin'));
// echo "toda la info_";
// print_r(Auth::user());
// exit;        

    }

    public function render()
    {
        // Para implementación de filtros, ordenar y paginar un mysql, ver 
        // el estándar en: ExtractosBancarios.php
        $arr_params =[];

        // Para las columnas que manejan códigos en vez de texto: 
        if($this->estado == ''){
            $where_estado = "";
        }else{
            $pos_nuevo = strpos('nuevo', strtolower($this->estado));
            $pos_aprobado = strpos('aprobado', strtolower($this->estado));
            $pos_asentado = strpos('asentado', strtolower($this->estado));
            $pos_anulado = strpos('anulado', strtolower($this->estado));

            $cad = "";
            if($pos_nuevo !== false){
                $cad = $cad . "1, ";
            }
            if($pos_aprobado !== FALSE){
                $cad = $cad . "2, ";
            }
            if($pos_asentado !== FALSE){
                $cad = $cad . "3, ";
            }
            if($pos_anulado !== FALSE){
                $cad = $cad . "4, ";
            }

            if($cad == ""){
                $where_estado = " and rec.estado = 9 ";   // para que no muestre nada
            }else{
                $cad = trim($cad , ', ');
                $where_estado = " and rec.estado in (" . $cad . ") ";
            }
        }

        if($this->categoria == ''){
            $where_categoria = "";
        }else{
            $pos_anticipo = strpos('anticipo', strtolower($this->categoria));
            $pos_pago_facturas = strpos('pago facturas', strtolower($this->categoria));

            if($pos_anticipo !== false && $pos_pago_facturas !== FALSE){
                $where_categoria = " and rec.categoria in (1,2) ";
            }elseif($pos_anticipo !== FALSE){
                $where_categoria = " and rec.categoria = 1 ";
            }elseif($pos_pago_facturas !== FALSE){
                $where_categoria = " and rec.categoria = 2 ";
            }else{
                $where_categoria = " and rec.categoria = 9 ";
            }
        }

        if($this->tipo == ''){
            $where_tipo = "";
        }else{
            $pos_efect = strpos('efect', strtolower($this->tipo));
            $pos_consig = strpos('consig', strtolower($this->tipo));

            if($pos_efect !== false && $pos_consig !== FALSE){
                $where_tipo = " and rec.tipo in (1,2) ";
            }elseif($pos_efect !== FALSE){
                $where_tipo = " and rec.tipo = 1 ";
            }elseif($pos_consig !== FALSE){
                $where_tipo = " and rec.tipo = 2 ";
            }else{
                $where_tipo = " and rec.tipo = 9 ";
            }
        }        

        // Para las columnas que pueden tener valores null (vacios):
        if($this->obs == ''){
            $where_obs = "";
        }else{
            $where_obs = " and rec.obs like :obs ";
        }

        if($this->notas_asiento == ''){
            $where_notas_asiento = "";
        }else{
            $where_notas_asiento = " and rec.notas_asiento like :notas_asiento ";
        }

        $this->sql1 = "SELECT rec.id nro, 
                rec.estado, 
                rec.categoria,  
                cli.nom_cliente, 
                usucom.id comercial_id,   
                usucom.name comercial, 
                rec.valor valor_recaudo,  
                rec.tipo, 
                rec.fec_pago, 
                rec.obs,
                rec.valor_asiento,
                rec.notas_asiento,
                rec.valor - rec.valor_asiento valor_diferencia,
                rec.created_at fec_creado,
                usuing.name usu_creado,
                rec.created_aprobo_at fec_aprobado,
                usuapro.name usu_aprobado,
                rec.created_asiento_at fec_asentado,
                usuasi.name usu_asentado,
                rec.created_anulo_at fec_anulado,
                usuanu.name usu_anulado
            FROM recaudos rec 
                left join clientes cli on cli.id=rec.cliente_id 
                left join users usucom on usucom.id=cli.comercial_id 
                left join users usuing on usuing.id=rec.user_id
                left join users usuapro on usuapro.id=rec.user_aprobo_id
                left join users usuasi on usuasi.id=rec.user_asiento_id 
                left join users usuanu on usuanu.id=rec.user_anulo_id 
            where rec.id like :nro and rec.estado <> 4   -- 25ago2021: Para que no muestre ni sume anulados en el informe
                " . $where_estado . "
                " . $where_categoria . "            
                and cli.nom_cliente like :nom_cliente
                and usucom.name like :comercial
                and rec.valor like :valor_recaudo
                " . $where_tipo . "
                and rec.fec_pago like :fec_pago
                " . $where_obs . "
                and (rec.valor_asiento like :valor_asiento or rec.valor_asiento is null)
                " . $where_notas_asiento . "
                and ((rec.valor - rec.valor_asiento) like :valor_diferencia or (rec.valor - rec.valor_asiento) is null)
                and rec.created_at like :fec_creado
                and usuing.name like :usu_creado
                and (rec.created_aprobo_at like :fec_aprobado or rec.created_aprobo_at is null)
                and (usuapro.name like :usu_aprobado or usuapro.name is null)
                and (rec.created_asiento_at like :fec_asentado or rec.created_asiento_at is null)
                and (usuasi.name like :usu_asentado or usuasi.name is null)
                and (rec.created_anulo_at like :fec_anulado or rec.created_anulo_at is null)
                and (usuanu.name like :usu_anulado or usuanu.name is null)
                and rec.fec_pago >= :fec_ini
                and rec.fec_pago <= :fec_fin ";
        // if(Auth::user()->hasRole(['admin' , 'contab'])){
        if(Auth::user()->hasRole('comer')){
            $this->sql1 = $this->sql1 . " and usucom.id = " . Auth::user()->id;
        }
        $this->sql1 = $this->sql1 . " order by " . $this->ordenar_campo . $this->ordenar_tipo;
        $arr_params = [
            'nro' => '%' . $this->nro . '%',
            'nom_cliente' => '%' . $this->nom_cliente . '%',
            'comercial' => '%' . $this->comercial . '%',
            'valor_recaudo' => '%' . $this->valor_recaudo . '%',
            'fec_pago' => '%' . $this->fec_pago . '%',
            'valor_asiento' => '%' . $this->valor_asiento . '%',
            'valor_diferencia' => '%' . $this->valor_diferencia . '%',
            'usu_creado' => '%' . $this->usu_creado . '%',
            'fec_creado' => '%' . $this->fec_creado . '%',
            'usu_aprobado' => '%' . $this->usu_aprobado . '%',
            'fec_aprobado' => '%' . $this->fec_aprobado . '%',
            'usu_asentado' => '%' . $this->usu_asentado . '%',
            'fec_asentado' => '%' . $this->fec_asentado . '%',
            'usu_anulado' => '%' . $this->usu_anulado . '%',
            'fec_anulado' => '%' . $this->fec_anulado . '%',
            'fec_ini' => $this->fec_ini,
            'fec_fin' => $this->fec_fin,
        ]; 

        // Para las columnas que pueden tener valores null (vacios):        
        if($this->obs !== ''){
            $arr_params['obs'] = '%' . $this->obs . '%';
        }

        if($this->notas_asiento !== ''){
            $arr_params['notas_asiento'] = '%' . $this->notas_asiento . '%';
        }

        // Por fin, ejecutar el mysql: 
        $recaudos = collect(DB::select($this->sql1, $arr_params));

        // 08mar2021: 
        // para determinar los estados texto, las categorias texto  y tipos texto  de cada recaudo, 
        // de modo que en el blade no se tengan que hacer ifs, adicionalmente
        // para determinar por cada recaudo si tiene o no foto en el hosting:
        foreach($recaudos as $un_recaudo){
            switch ($un_recaudo->estado) {
                case 1:
                    $un_recaudo->estado_texto = 'Nuevo';
                    break;
                case 2:
                    $un_recaudo->estado_texto = 'Aprobado';
                    break;
                case 3:
                    $un_recaudo->estado_texto = 'Asentado';
                    break;
                case 4:
                    $un_recaudo->estado_texto = 'Anulado';
                    break;
                default:
                    $un_recaudo->estado_texto = '';
                    break;
            }      
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
            if (Storage::disk('public')->exists('comptes/'.$un_recaudo->nro)){
                // en el locahost public_path() quedará con /var/www/html/markka/public 
                // pero en un hosting compartido quedará con /home/tavohenc/markka_pr/public y debido 
                // a que en el hosting compartido la carpeta publica va en un lado (public_html/markka) y los demás 
                // archivos van en otro (markka), entonces lo que sirve para el localhost no sirve para el hosting 
                //      para el localhost sirve /var/www/html/markka/public 
                //      para el hosting compartido sirve /home/tavohenc/public_html/markka_pr
                // 07mar2021:
                // Todo lo anterior se solucionó usando constantes laravel:
                $public_path = config('constantes.path_foto_compte');
                $ruta = $public_path.'/storage/comptes/'.$un_recaudo->nro.'/';
     
                $filehandle = opendir($ruta);
                while ($file = readdir($filehandle)) {
                    if ($file != "." && $file != ".." ) {
                        $foto_existe_nombre_completo = $ruta.$file;
                    }
                }
                closedir($filehandle);  
                if($foto_existe_nombre_completo !== ''){
                    $arr_foto_existe_nombre_completo = explode('/' , $foto_existe_nombre_completo);
                    $this->foto_existe_nombre = 'comptes/' . $un_recaudo->nro . '/' . end( $arr_foto_existe_nombre_completo )  ;
                }
            }  
            // si existe foto, enseguida se grabará su ruta y nombre, en caso 
            // contrario la variable seguira teniendo '' :
            $un_recaudo->foto_existe_nombre = $this->foto_existe_nombre;             
            $un_recaudo->foto_existe_nombre_path = config('constantes.path_inicial_foto_modal') . $this->foto_existe_nombre;             
        }        
    
        return view('livewire.info-recaudos' , compact('recaudos'));
    }

    public function ordenar($campo){
        if($this->ordenar_campo == $campo){
            if($this->ordenar_tipo == ' asc'){
                $this->ordenar_tipo = ' desc';
            }else{
                $this->ordenar_tipo = ' asc';
            }
        }else{
            $this->ordenar_campo = $campo;
            $this->ordenar_tipo = ' asc';
        }
    }

    public function format_fecha($fec){
        $dia_num =  date('d' , strtotime($fec));
        $mes_num =  date('n' , strtotime($fec));
        $mes = $this->arr_meses[$mes_num];
        $an = date('Y' , strtotime($fec));
        return $dia_num . "-" . $mes . "-" . $an;
    }

    public function format_fecha_condiasem($fec){

        $dia_semana_num = date('w',strtotime($fec));  // 0 es domingo 6 es sábado
        $dia_semana = $this->arr_dias_semana[$dia_semana_num];

        $dia_num =  date('d' , strtotime($fec));
        $mes_num =  date('n' , strtotime($fec));
        $mes = $this->arr_meses[$mes_num];
        $an = date('Y' , strtotime($fec));

        return $dia_semana . " " . $dia_num . "-" . $mes . "-" . $an;
    }

    public function cambiar_fechas(){
        // Hace visible el modal para cambiar fechas:
        $this->fec_desde = $this->fec_ini;
        $this->fec_hasta = $this->fec_fin;
        $this->formu_cambiar_fechas = true;
    }

    public function submit($fec_desde , $fec_hasta){
        // Cambia el rango de fechas para filtrar en la base de datos
        if($fec_hasta >= $fec_desde){
            $this->fec_ini = $fec_desde;   
            $this->fec_fin = $fec_hasta;   
            $this->formu_cambiar_fechas = false; 
        }
        else{
            session()->flash('message', 'La fecha inicial debe ser menor o igual a la fecha final.');
        }
    }

    public function cancelar(){
        $this->formu_cambiar_fechas = false; 
    }

    // public function exportar_hoja_electronica(){
    //     session()->flash('mensaje_exportar', 'Proceso en construcción...');
    // }

    public function mostrar_modal_foto($foto){
        $this->foto_modal_visible = true;
        $this->foto_modal_src = $foto;
    }    

}
