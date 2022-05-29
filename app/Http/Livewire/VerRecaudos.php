<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;
use App\Models\Recaudo;

class VerRecaudos extends Component
{
    use WithPagination;

    public $nro; 
    public $estado;
    public $categoria;
    public $cliente;
    public $valor;
    public $tipo;
    public $fec_pago;
    public $obs;
    public $usu_ingreso;   
    public $foto_existe_nombre; 
    public $foto_modal_visible;   
    public $foto_modal_src;     

    public $anular_modal_visible;     
    public $recaudo_id_anular;
    public $fecha_pago_anular;
    public $cliente_anular;
    public $valor_recaudo_anular;
    public $estado_anular; 
    public $notas_anulado; 

    
    public $ordenar_campo;
    public $ordenar_tipo;
    public $filas_por_pagina;
    public $titulo;

    protected $rules = [
        'notas_anulado' => 'required|min:8',
    ];    

    protected $messages = [
        'notas_anulado.required' => 'Es obligatorio escribir la causa de la anulación.',
        'notas_anulado.min' => 'En la causa de la anulación se deben escribir mínimo 8 caracteres.',
    ];     
    
    public function mount(){
        $this->foto_modal_visible = false;          
        $this->anular_modal_visible = false;          
        $this->filas_por_pagina = 50;          
        $this->estado = '';
        $this->categoria = '';
        $this->tipo = '';
        $this->obs = '';
        $this->ordenar_campo = 'rec.id';
        $this->ordenar_tipo = ' desc';

        if(Auth::user()->hasRole('admin')){
            $this->titulo = "Crear - Modificar - Anular recaudos";
        }else{
            $this->titulo = "Crear - Modificar recaudos";
        }        
    }    

    public function render(){
        $arr_params =[];

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

        if($this->obs == ''){
            $where_obs = "";
        }else{
            $where_obs = " and rec.obs like :obs ";
        }

        // nota a sentencias preparadas: En el siguiente select se usan 4 variables 
        // directamente pero esto no implica vulnerabilidad ante inyección sql ya 
        // que las 4variables no son llenadas por el usuario, sino directamente
        // desde el código fuente
        $sql1 = "SELECT rec.id nro, 
                rec.estado,
                rec.categoria,
                cli.nom_cliente, 
                rec.valor , 
                rec.tipo , 
                rec.fec_pago, 
                rec.obs,
                usu.name usu_ingreso
            FROM recaudos rec 
                left join clientes cli on cli.id = rec.cliente_id 
                left join users usu on usu.id = rec.user_id
            where rec.id like :nro
                " . $where_estado . "
                " . $where_categoria . "
                and cli.nom_cliente like :cliente
                and rec.valor like :valor
                " . $where_tipo . "
                and rec.fec_pago like :fec_pago
                " . $where_obs . "
                and usu.name like :usu_ingreso ";

        if(Auth::user()->hasRole(['contab' , 'comer'])){
            $sql1 = $sql1 . " and usu.id = " . Auth::user()->id . " and rec.estado=1 ";
        }
        $sql1 = $sql1 . " order by " . $this->ordenar_campo . $this->ordenar_tipo;                
            
        $arr_params = [
            'nro' => '%' . $this->nro . '%',
            'cliente' => '%' . $this->cliente . '%',
            'valor' => '%' . $this->valor . '%',
            'fec_pago' => '%' . $this->fec_pago . '%',
            'usu_ingreso' => '%' . $this->usu_ingreso . '%',
        ]; 

        if($this->obs !== ''){
            $arr_params['obs'] = '%' . $this->obs . '%';
        }        
        
        $perPage = $this->filas_por_pagina;
        $collection = collect(DB::select($sql1 , $arr_params));
        $items = $collection->forPage($this->page, $perPage);
        $paginator = new LengthAwarePaginator($items, $collection->count(), $perPage, $this->page);

        // 08mar2021: 
        // para determinar los estados texto, las categorias texto  y tipos texto  de cada recaudo, 
        // de modo que en el blade no se tengan que hacer ifs, adicionalmente
        // para determinar por cada recaudo si tiene o no foto en el hosting:
        foreach($paginator as $un_recaudo){
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
// dd($public_path);                
                $ruta = $public_path.'/storage/comptes/'.$un_recaudo->nro.'/';
// dd($ruta);                
     
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

        return view('livewire.ver-recaudos', ['recaudos' => $paginator]);        
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

    public function mostrar_modal_foto($foto){
        $this->foto_modal_visible = true;
        $this->foto_modal_src = $foto;
    }     

    public function mostrar_modal_anular($recaudo_id , $fecha_pago , $cliente , $valor_recaudo , $estado){

        $this->anular_modal_visible = true;
        $this->recaudo_id_anular = $recaudo_id;
        $this->fecha_pago_anular = $fecha_pago;
        $this->cliente_anular = $cliente;
        $this->valor_recaudo_anular = $valor_recaudo;
        $this->estado_anular = $estado;
        $this->reset(['notas_anulado']);
        $this->resetValidation();
        
    } 

    public function submit_delete(){
        $this->validate();

        // Actualización en la base de datos: 
        $recaudo = Recaudo::find($this->recaudo_id_anular);

        $recaudo->estado = 4;
        $recaudo->notas_anulado = $this->notas_anulado;
        $recaudo->user_anulo_id = Auth::user()->id;
        $recaudo->created_anulo_at = date('Y-m-d H:i:s');
        $recaudo->save();
        $this->reset(['notas_anulado']);
        $this->resetValidation();
        $this->anular_modal_visible = false;

    }    

    // ganchos para cada variable de búsqueda, de tal manera que se evite el problema
    // que consistia en que después de grabar, si la página actual era una en la que 
    // la nueva búsqueda no dejaba registros, entonces no aparecia nada en la pantalla (a 
    // pesar de que habian registros que concordaban con la búsqueda). Para evitar este 
    // problema, cada que se hace una búsqueda se muestra la primera página de los
    // resultados hallados.
    public function updatingNro(){
        $this->gotoPage(1);
        // $this->resetPage();
    }

    public function updatingCategoria(){
        $this->gotoPage(1);
    }    

    public function updatingCliente(){
        $this->gotoPage(1);
    }

    public function updatingValor(){
        $this->gotoPage(1);
    }

    public function updatingTipo(){
        $this->gotoPage(1);
    }

    public function updatingFecPago(){
        $this->gotoPage(1);
    }

    public function updatingObs(){
        $this->gotoPage(1);
    }

    public function updatingUsuIngreso(){
        $this->gotoPage(1);
    }

   
}
