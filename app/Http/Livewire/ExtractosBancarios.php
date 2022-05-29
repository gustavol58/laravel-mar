<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;
use App\Models\Consignacion;
// use App\Http\Controllers\DateTime;

class ExtractosBancarios extends Component
{
    use WithFileUploads;
    use WithPagination;
    // use DateTime;

    public $nro_consignacion;
    public $archivo_original;
    public $fecha;
    public $valor;
    public $referencia;
    public $estado;
    public $recaudo_id;
    public $fec_importo;
    public $importo;
    public $fec_asigno;
    public $asigno;
    public $archivo_extracto;

    public $eliminar_modal_visible;     
    public $consignacion_id_eliminar;
    public $fecha_eliminar;
    public $valor_eliminar;
    public $importo_eliminar;

    public $cargar_archivo_modal_visible;  
 
    public $ordenar_campo;
    public $ordenar_tipo;
    public $filas_por_pagina;
  

    protected $rules = [
        'archivo_extracto' => 'required|file|mimes:xls,xlsx',
    ];  

    protected $messages = [
        'archivo_extracto.required' => 'Es obligatorio seleccionar un archivo.',
        'archivo_extracto.mimes' => 'Debe ser un archivo excel.', 
    ];        
    
    public function mount(){
        $this->eliminar_modal_visible = false;   
        $this->cargar_archivo_modal_visible = false;   
        $this->estado = '';
        $this->filas_por_pagina = 50;          
        $this->ordenar_campo = 'con.id';
        $this->ordenar_tipo = ' desc';        
    }

    public function render(){
        $arr_params =[];

        // Para las columnas que manejan códigos en vez de texto: 
        if($this->estado == ''){
            $where_estado = "";
        }else{
            $pos_sinasignar = strpos('sin asignar', strtolower($this->estado));
            $pos_asignada = strpos('asignada', strtolower($this->estado));

            $cad = "";
            if($pos_sinasignar !== false){
                $cad = $cad . "1, ";
            }
            if($pos_asignada !== FALSE){
                $cad = $cad . "2, ";
            }

            if($cad == ""){
                $where_estado = " and con.estado = 9 ";   // para que no muestre nada
            }else{
                $cad = trim($cad , ', ');
                $where_estado = " and con.estado in (" . $cad . ") ";
            }
        }

        // Para las columnas que pueden tener valores null (vacios):
        if($this->recaudo_id == ""){
            $where_recaudo_id = "";
        }else{
            $where_recaudo_id = " and con.recaudo_id like :recaudo_id ";
        }    

        if($this->referencia == ""){
            $where_referencia = "";
        }else{
            $where_referencia = " and con.referencia like :referencia ";
        }  

        if($this->fec_asigno == ""){
            $where_fec_asigno = "";
        }else{
            $where_fec_asigno = " and con.created_asigno_at like :fec_asigno ";
        }        

        if($this->asigno == ""){
            $where_asigno = "";
        }else{
            $where_asigno = " and usu2.name like :asigno ";
        }        

        $sql1 = "SELECT con.id, con.original, con.fecha, con.valor, con.referencia, con.estado, con.recaudo_id, 
                con.created_importo_at fec_importo , usu1.name importo, 
                con.created_asigno_at fec_asigno , usu2.name asigno
            FROM consignaciones con
                left join users usu1 on usu1.id=con.user_importo_id
                left join users usu2 on usu2.id=con.user_asigno_id
                where con.id like :nro
                and con.original like :original
                and con.fecha like :fecha
                and con.valor like :valor
                " . $where_referencia . "
                " . $where_estado . "
                " . $where_recaudo_id . "
                and con.created_importo_at like :fec_importo
                and usu1.name like :importo
                " . $where_fec_asigno . "
                " . $where_asigno
            ;
        $sql1 = $sql1 . " order by " . $this->ordenar_campo . $this->ordenar_tipo;  
        $arr_params = [
            'nro' => '%' . $this->nro_consignacion . '%',
            'original' => '%' . $this->archivo_original . '%',
            'fecha' => '%' . $this->fecha . '%',
            'valor' => '%' . $this->valor . '%',
            'fec_importo' => '%' . $this->fec_importo . '%',
            'importo' => '%' . $this->importo . '%',
        ]; 
        // Para las columnas que pueden tener valores null (vacios):        
        if($where_recaudo_id !== ""){
            $arr_params['recaudo_id'] = '%' . $this->recaudo_id . '%';
        }        
        if($where_referencia !== ""){
            $arr_params['referencia'] = '%' . $this->referencia . '%';
        }        
        if($where_fec_asigno !== ""){
            $arr_params['fec_asigno'] = '%' . $this->fec_asigno . '%';
        }        
        if($where_asigno !== ""){
            $arr_params['asigno'] = '%' . $this->asigno . '%';
        }        
        // Por fin, ejecutar el mysql con paginación:
        $perPage = $this->filas_por_pagina;
        $collection = collect(DB::select($sql1, $arr_params));
// echo "<pre>";
// print_r($collection);
// exit;
        $items = $collection->forPage($this->page, $perPage);
        $paginator = new LengthAwarePaginator($items, $collection->count(), $perPage, $this->page);

        // 08mar2021: 
        // para los campos que manejan códigos en vez de textos, 
        // de modo que en el blade no se tengan que hacer ifs:
        foreach($paginator as $una_consignacion){
            switch ($una_consignacion->estado) {
                case 1:
                    $una_consignacion->estado_texto = 'Sin asignar';
                    break;
                case 2:
                    $una_consignacion->estado_texto = 'Asignada';
                    break;
                default:
                    $una_consignacion->estado_texto = '';
                    break;
            } 
        }        
        return view('livewire.extractos-bancarios' , ['consignaciones' => $paginator]);
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

    public function mostrar_modal_eliminar($consignacion_id , $fecha , $valor , $importo){

        $this->eliminar_modal_visible = true;
        $this->consignacion_id_eliminar = $consignacion_id;
        $this->fecha_eliminar = $fecha;
        $this->valor_eliminar = $valor;
        $this->importo_eliminar = $importo;
        // $this->reset(['archivo_extracto']);
        // $this->resetValidation();   
    }  
    
    public function submit_delete(){
        // $this->validate();

        // Actualización en la base de datos: 
        $consignacion = Consignacion::find($this->consignacion_id_eliminar);

        // $recaudo->notas_anulado = $this->notas_anulado;
        // $recaudo->user_anulo_id = Auth::user()->id;
        // $recaudo->created_anulo_at = date('Y-m-d H:i:s');
        $consignacion->delete();
        // $this->reset(['notas_anulado']);
        // $this->resetValidation();
        $this->eliminar_modal_visible = false;

    }     
    
    public function mostrar_modal_cargar_archivo(){
        $this->cargar_archivo_modal_visible = true;
        $this->reset(['archivo_extracto']);
        $this->resetValidation();
    }     

    public function submit_cargar(){
        // Se llega aquí cuando el usuario presione el botón 'Cargar archivo' en el modal 
        // 1) Valida que se haya escogido un archivo excel 
        // 2) Crea el datareader excel con PhpOffice
        // 3) A partir del datareader crear un array que tendrá 10 columnas: 
        //         fecha como datetimestamp (ejemplo: 42150)
        //         fecha formateada (ejemplo: 15/03/2021)
        //         documento 
        //         oficina 
        //         descripcion
        //         referencia
        //         valor
        //         observaciones
        //         estado
        //         consignacion_id
        //      las 7 primeras son las que llegaron en el excel, la octava será armada 
        //      a partir de lo que se verifique contra la tabla consignaciones de la 
        //      base de datos, las otras dos se llenarán por el componente importar-asignaciones
        //    Notas: en este array solo quedarán grabados valores (ultima columna del archivo
        //           original) que sean positivos
        //           las fechas quedaran con formato aaaa-mm-dd
        //           los valores no tendrán separador de miles
        // 4) Llamada al componente ImportarConsignaciones, enviando el array armado.

        // Validar input type file 
        $this->validate();

        // Crear array a partir del datareader 
        $inputFileName = $this->archivo_extracto->getRealPath();
        /** Create a new Xls Reader  **/
        // lo primero es determinar si el inputFileName es xls o xlsx y actuar en consecuencia: 
        $extension = substr($inputFileName , -5);
        if($extension == ".xlsx"){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }else{
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        // Creación del datareader:
        $spreadsheet = $reader->load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();

        // creación del array desde el datareader:
        $arr_archivo = [];
        $arr_no_cargadas = [];
        $id_consignacion = 0;
        foreach ($worksheet->getRowIterator() as $row) {
            $arr_fila = [];
            $num_col_archivo_original = 1;
            $cellIterator = $row->getCellIterator();
            foreach ($cellIterator as $cell) {
                if($num_col_archivo_original == 1){
                    // 15abr2021
                    // es la fecha, en la columna 0 del array debe quedar la fecha timestamp y 
                    // en la columna 2 debe quedar la fecha como yyyy-mm-dd
                    $arr_fila[] = $cell->getValue();
                    $arr_fila[] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($cell->getValue()))->format('Y-m-d');  
                    $num_col_archivo_original++;
                }elseif($num_col_archivo_original <= 6){
                    $arr_fila[] = $cell->getValue(); 
                    $num_col_archivo_original++;
                }else{
                    break;
                }
            }
            $arr_fila[] = '';  // la columna 7 del array: para observaciones b.d.
            $arr_fila[] = '';  // la columna 8 del array: para estado de la consignación
            $arr_fila[] = $id_consignacion;  // la columna 9 del array: para id de la consignación
            $id_consignacion++;
            array_push($arr_archivo , $arr_fila);
        }
// dd($arr_archivo);
        // el siguiente ciclo: 
        //     valida las fechas y valores
        //     si todo es correcto formatea, sino elimina las filas en las que están 
        //      las equivocadas
        //     elimina también la primer fila (títulos de columna), cuyo id_consignacion es cero
        $arr_archivo_aux = [];
        $arr_errores_contador = 1;
        foreach($arr_archivo as $fila){
            if($fila[9] !== 0){
                // 15abr2021 
                // no es la fila de títulos, por lo tanto procede a validar: 
                // Validar: 
                //      que la fecha timestamp sea un número. 
                //      que la fecha formateada (aaaa-mm-dd) sea correcta 
                //      que el valor sea numérico y positivo:
                if(is_int($fila[0])){
                    // el datetime es un número entero, puede proceder con las siguientes 
                    // validaciones: 
                    $arr_fecha_partida = explode('-', $fila[1]);
                    // los parametros que recibe checkdate son: mes, dia, año; en ese orden.
                    if(count($arr_fecha_partida) == 3 && checkdate($arr_fecha_partida[1], $arr_fecha_partida[2], $arr_fecha_partida[0])){
                        // validar valor: 
                        if(is_numeric(str_replace(',' , '' , $fila[6]))){
                            if(floatval($fila[6]) >= 0){
                                $validar_fecha_valor = true; 
                            }else{
                                $validar_fecha_valor = false;
                                array_push($arr_no_cargadas , [$arr_errores_contador , 'No es un valor positivo.', $fila[6] ]);
                                // No se pudo usar el array asociativo por los problemas que 
                                // dá al enviarlo a otro componente
                                // array_push($arr_no_cargadas , [
                                //     'fila' => $arr_errores_contador , 
                                //     'mensaje' => 'No es un valor positivo.',
                                //     'dato' => $fila[6],
                                // ]);
                            }                      
                        }else{
                            $validar_fecha_valor = false;
                            array_push($arr_no_cargadas , [$arr_errores_contador , 'Valor incorrecto.', $fila[6] ]);

                            // array_push($arr_no_cargadas , [
                            //     'fila' => $arr_errores_contador , 
                            //     'mensaje' => 'Valor incorrecto.',
                            //     'dato' => $fila[6],
                            // ]);
                        }
                    }else{
                        $validar_fecha_valor = false;
                        array_push($arr_no_cargadas , [$arr_errores_contador , 'No es un dato tipo fecha.', $fila[1] ]);
                        
                        // array_push($arr_no_cargadas , [
                        //     'fila' => $arr_errores_contador , 
                        //     'mensaje' => 'No es un dato tipo fecha.',
                        //     'dato' => $fila[1],
                        // ]);
                    }
                    if($validar_fecha_valor){
                        // formatear valor para que no tenga separador de miles:
                        $valor_ant = $fila[6];
                        $valor_nue = str_replace(',' , '' , $valor_ant);
                        $fila[6] = floatval($valor_nue);      
                        
                        // pasa la info al array provisional:
                        array_push($arr_archivo_aux , $fila);                     
                    }
                }else{
                    $validar_fecha_valor = false;
                    array_push($arr_no_cargadas , [$arr_errores_contador , 'Fecha incorrecta.', $fila[0] ]);

                    // array_push($arr_no_cargadas , [
                    //     'fila' => $arr_errores_contador , 
                    //     'mensaje' => 'Fecha incorrecta.',
                    //     'dato' => $fila[0],
                    // ]);
                }
            }
            $arr_errores_contador++;
        }
        // recordar que el array con los mensajes de error, será enviado 
        // al componente 'importarconsignaciones', junto con el 
        // array de filas cargadas.

        $arr_archivo = $arr_archivo_aux; 

// echo "<pre>";
// print_r($arr_no_cargadas);
// $aaa = htmlentities(json_encode($arr_no_cargadas));
// print_r($aaa);
// $bbb = json_decode(html_entity_decode($aaa));   
// echo "BBBBBB ES: <br>";
// print_r($bbb); 


// dd($arr_no_cargadas);
        // buscar en la tabla consignaciones si hay fechas-valores existentes: 
        $fila_contador = 0;
        foreach($arr_archivo as $fila){
            // busca si en la tabla consignaciones se encuentra esta fila: 
            $sql1 = "SELECT con.id, 
                    con.created_importo_at fec_importo, 
                    usu.name importo, 
                    (case when con.estado=1 then 'Sin asignar' 
                        when con.estado=2 then 'Asignada' 
                        else '...' end) estado_texto , 
                    con.recaudo_id 
                FROM  consignaciones con 
                    left join users usu on usu.id=con.user_importo_id
                where con.fecha=:fecha and con.valor=:valor";
            $arr_params = [
                'fecha' => $fila[1],
                'valor' => $fila[6],
            ];
            // $consignaciones_halladas = collect(DB::select($sql1, $arr_params));
  
            $consignaciones_halladas = collect(DB::select($sql1, $arr_params))->toArray();
            // Si la fila está en la b.d.,  llenará la octava columna del array (observaciones): 
            if(count($consignaciones_halladas) >= 1){
                // la consignación que llegó en el archivo cargado, ya existe en la b.d., 
                // debe colocar las observaciones en la columna 7 del arr_archivo: 
                // Nota: para el mensaje explicativo en las observaciones, solamente 
                //       se tendrá en cuenta el primer registro encontrado en el mysql anterior
                $observaciones = "Esta fecha-valor corresponde a la consignación nro " . $consignaciones_halladas[0]->id . " importada el " . $consignaciones_halladas[0]->fec_importo . " por: " . $consignaciones_halladas[0]->importo . ". Actualmente con estado " . $consignaciones_halladas[0]->estado_texto;
                if($consignaciones_halladas[0]->estado_texto == 'Asignada'){
                    $observaciones = $observaciones . ". (Recaudo número " . $consignaciones_halladas[0]->recaudo_id . ")";
                }
                $arr_archivo[$fila_contador][7] = $observaciones;
            }
            $fila_contador++;
        }

        // Para que las fechas del array $arr_no_cargadas no generen problema 
        // cuando su contenido vaya por la url:
        $arr_no_cargadas_para_url = str_replace('\/' , '_@_' ,json_encode($arr_no_cargadas));

        // Al componente importarconsignaciones se le debe enviar el archivo excel 
        // para que él lo grabe en el hosting, pero debido al problema que implica 
        // enviar un archivo completo por url (sobretodo por los caracteres y json)
        // entonces se decidió grabar el archivo desde aca en la carpeta 
        // storage\app\public\extractos, con un nombre que comienza con un 
        // random de 3 cifras, este nombre provisional es el que se le envia al 
        // componente ImportarConsignaciones, y allá se decidirá si este archivo 
        // provisional se debe dejar en el hosting o se debe borrar. Para eso 
        // son estas dos variables:
        $nom_excel_original = $this->archivo_extracto->getClientOriginalName();
        $nom_excel_provisional = date('Ymdhis') . "_" . $nom_excel_original;

        // Grabación del excel en el hosting, en la carpeta temp y con el nombre provisional:
        $this->archivo_extracto->storeAs('public/extractos/temp/' , $nom_excel_provisional);

        return redirect(url('importar-consignaciones' , [          
            'arr_consignaciones_param' => json_encode($arr_archivo),
            'arr_no_cargadas_param' => $arr_no_cargadas_para_url,
            'extracto_nombre_param' => $nom_excel_original,
            'extracto_provisional_param' => $nom_excel_provisional,
        ]));

        // return view('livewire.importar-consignaciones' , ['cargues' => $arr_archivo]);
    }
}
