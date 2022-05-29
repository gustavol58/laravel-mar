<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Recaudo;

class GenerarHojaElectronicaController extends Controller
{

    public function exportar_info_recaudo(Request $request) {
        $recaudos_string = $request->recaudos;
        $fec_ini = $request->fec_ini;
        $fec_fin = $request->fec_fin;

        // recepción de filtros: 
        $arr_filtros = [];
        if($request->filtro_nro !== null){
            $arr_filtros['nro'] = $request->filtro_nro;
        }
        if($request->filtro_estado !== null){
            $arr_filtros['estado'] = $request->filtro_estado;
        }
        if($request->filtro_categoria !== null){
            $arr_filtros['categoria'] = $request->filtro_categoria;
        }
        if($request->filtro_nom_cliente !== null){
            $arr_filtros['nom_cliente'] = $request->filtro_nom_cliente;
        }
        if($request->filtro_comercial !== null){
            $arr_filtros['comercial'] = $request->filtro_comercial;
        }
        if($request->filtro_valor_recaudo !== null){
            $arr_filtros['valor_recaudo'] = $request->filtro_valor_recaudo;
        }
        if($request->filtro_tipo !== null){
            $arr_filtros['tipo'] = $request->filtro_tipo;
        }
        if($request->filtro_fec_pago !== null){
            $arr_filtros['fec_pago'] = $request->filtro_fec_pago;
        }
        if($request->filtro_obs !== null){
            $arr_filtros['obs'] = $request->filtro_obs;
        }
        if($request->filtro_valor_asiento !== null){
            $arr_filtros['valor_asiento'] = $request->filtro_valor_asiento;
        }
        if($request->filtro_notas_asiento !== null){
            $arr_filtros['notas_asiento'] = $request->filtro_notas_asiento;
        }
        if($request->filtro_valor_diferencia !== null){
            $arr_filtros['valor_diferencia'] = $request->filtro_valor_diferencia;
        }
        if($request->filtro_fec_creado !== null){
            $arr_filtros['fec_creado'] = $request->filtro_fec_creado;
        }
        if($request->filtro_usu_creado !== null){
            $arr_filtros['usu_creado'] = $request->filtro_usu_creado;
        }
        if($request->filtro_fec_aprobado !== null){
            $arr_filtros['fec_aprobado'] = $request->filtro_fec_aprobado;
        }
        if($request->filtro_usu_aprobado !== null){
            $arr_filtros['usu_aprobado'] = $request->filtro_usu_aprobado;
        }
        if($request->filtro_fec_asentado !== null){
            $arr_filtros['fec_asentado'] = $request->filtro_fec_asentado;
        }
        if($request->filtro_usu_asentado !== null){
            $arr_filtros['usu_asentado'] = $request->filtro_usu_asentado;
        }
        if($request->filtro_fec_anulado !== null){
            $arr_filtros['fec_anulado'] = $request->filtro_fec_anulado;
        }
        if($request->filtro_usu_anulado !== null){
            $arr_filtros['usu_anulado'] = $request->filtro_usu_anulado;
        }
// print_r(count($arr_filtros));
// print_r($arr_filtros);
// exit;

        // El valor que llego por el post form es un string, debe ser 
        // convertido a array con esta instrucción (en realidad 
        // convierte a json, pero el segundo parámetro convierte a array)
        $recaudos = json_decode($recaudos_string, true);

		// El programa no regresará de la siguiente función ya que en ella pueden 
		// suceder una de dos cosas: 
		// 	    Si el archivo se graba en el hosting: la función hará un redirect 
		// 	    Si el archivo se descarga al compu del usuario: El programa no ejecutará 
		// 	    ninguna linea mas.
		$this->pintar_info_recaudo($recaudos , $fec_ini , $fec_fin , $arr_filtros);
    }

	private function pintar_info_recaudo($recaudos , $fec_ini , $fec_fin , $arr_filtros){
        // Crear objeto spreadsheet 
		$spreadsheet = new Spreadsheet();
        // activar la hoja actual 
        $sheet = $spreadsheet->getActiveSheet();

        // titulo
        $sheet->setCellValue('A1', 'MARKKA S.A.S.');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // subtitulo 
        $sheet->setCellValue('A2', 'Informe de recaudos');
        $sheet->mergeCells('A2:F2');        
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:A2")->getFont()->setBold(true)->setName('Calibri')->setSize(20);        

        // rango de fechas 
        $sheet->setCellValue('A3', 'Del   ' . $fec_ini . '   al   ' . $fec_fin);
        $sheet->mergeCells('A3:F3');        
        $sheet->getStyle('A3')->getFont()->setBold(true)->setName('Calibri')->setSize(16);        
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Alturas de fila para los font size 20 y 16: no se pudo usar autosize ya que 
        // libre office no lo maneja bien, en ese caso se hubieran utilizado estas 
        // dos instrucciones:
        //      $sheet->getRowDimension('1')->setRowHeight(-1);
        //      $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
        // Debido al problema del libre office, se tuvo que usar un height fijo, en este caso25:
        foreach (range('1','3') as $fil){
            $sheet->getRowDimension($fil)->setRowHeight(25);
        }   

        // color azul para títulos y encabezados de columna:        
        $sheet->getStyle('A1:T6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);        

        // encabezados de columna mostrando en rojo las que tengan filtro del usuario, con 
        // su correspondiente valor de filtro en la linea nro 5:
        $sheet->setCellValue('A6', 'Nro');
        if(array_key_exists ('nro' , $arr_filtros)){
            $sheet->getStyle('A6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('A5', $arr_filtros['nro']);
            $sheet->getStyle('A5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }
        $sheet->setCellValue('B6', 'Estado');
        if(array_key_exists ('estado' , $arr_filtros)){
            $sheet->getStyle('B6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('B5', $arr_filtros['estado']);
            $sheet->getStyle('B5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }
		$sheet->setCellValue('C6', 'Categoria');
        if(array_key_exists ('categoria' , $arr_filtros)){
            $sheet->getStyle('C6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('C5', $arr_filtros['categoria']);
            $sheet->getStyle('C5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('D6', 'Cliente');
        if(array_key_exists ('nom_cliente' , $arr_filtros)){
            $sheet->getStyle('D6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('D5', $arr_filtros['nom_cliente']);
            $sheet->getStyle('D5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
        $sheet->setCellValue('E6', 'Comercial');
        if(array_key_exists ('comercial' , $arr_filtros)){
            $sheet->getStyle('E6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('E5', $arr_filtros['comercial']);
            $sheet->getStyle('E5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('F6', 'Valor recaudo');
        if(array_key_exists ('valor_recaudo' , $arr_filtros)){
            $sheet->getStyle('F6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('F5', $arr_filtros['valor_recaudo']);
            $sheet->getStyle('F5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('G6', 'Tipo');
        if(array_key_exists ('tipo' , $arr_filtros)){
            $sheet->getStyle('G6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('G5', $arr_filtros['tipo']);
            $sheet->getStyle('G5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('H6', 'Fecha pago');
        if(array_key_exists ('fec_pago' , $arr_filtros)){
            $sheet->getStyle('H6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('H5', $arr_filtros['fec_pago']);
            $sheet->getStyle('H5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('I6', 'Observaciones');
        if(array_key_exists ('obs' , $arr_filtros)){
            $sheet->getStyle('I6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('I5', $arr_filtros['obs']);
            $sheet->getStyle('I5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('J6', 'Valor asiento');
        if(array_key_exists ('valor_asiento' , $arr_filtros)){
            $sheet->getStyle('J6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('J5', $arr_filtros['valor_asiento']);
            $sheet->getStyle('J5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('K6', 'Notas asiento');
        if(array_key_exists ('notas_asiento' , $arr_filtros)){
            $sheet->getStyle('K6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('K5', $arr_filtros['notas_asiento']);
            $sheet->getStyle('K5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }
		$sheet->setCellValue('L6', 'Diferencia valores');
        if(array_key_exists ('valor_asiento' , $arr_filtros)){
            $sheet->getStyle('L6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('L5', $arr_filtros['valor_asiento']);
            $sheet->getStyle('L5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('M6', 'Fec creado');
        if(array_key_exists ('fec_creado' , $arr_filtros)){
            $sheet->getStyle('M6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('M5', $arr_filtros['fec_creado']);
            $sheet->getStyle('M5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('N6', 'Usu creó');
        if(array_key_exists ('usu_creado' , $arr_filtros)){
            $sheet->getStyle('N6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('N5', $arr_filtros['usu_creado']);
            $sheet->getStyle('N5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('O6', 'Fec aprobado');
        if(array_key_exists ('fec_aprobado' , $arr_filtros)){
            $sheet->getStyle('O6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('O5', $arr_filtros['fec_aprobado']);
            $sheet->getStyle('O5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('P6', 'Usu aprobó');
        if(array_key_exists ('usu_aprobado' , $arr_filtros)){
            $sheet->getStyle('P6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('P5', $arr_filtros['usu_aprobado']);
            $sheet->getStyle('P5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('Q6', 'Fec asentado');
        if(array_key_exists ('fec_asentado' , $arr_filtros)){
            $sheet->getStyle('Q6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('Q5', $arr_filtros['fec_asentado']);
            $sheet->getStyle('Q5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('R6', 'Usu asentó');
        if(array_key_exists ('usu_asentado' , $arr_filtros)){
            $sheet->getStyle('R6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('R5', $arr_filtros['usu_asentado']);
            $sheet->getStyle('R5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        } 
		$sheet->setCellValue('S6', 'Fec anulado');
        if(array_key_exists ('fec_anulado' , $arr_filtros)){
            $sheet->getStyle('S6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('S5', $arr_filtros['fec_anulado']);
            $sheet->getStyle('S5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        }        
		$sheet->setCellValue('T6', 'Usu anuló');
        if(array_key_exists ('usu_anulado' , $arr_filtros)){
            $sheet->getStyle('T6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
            $sheet->setCellValue('T5', $arr_filtros['usu_anulado']);
            $sheet->getStyle('T5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);        
        } 
        $sheet->getStyle("A6:T6")->getFont()->setBold(true);        

        // registros 
		$rows = 7;
		foreach($recaudos as $un_recaudo){ 
			$sheet->setCellValue('A' . $rows, $un_recaudo['nro']);
            // decodificar estado: 
            $estado = $un_recaudo['estado'];
            if($estado == 1){
                $estado_texto = "Nuevo";
            }elseif($estado == 2){
                $estado_texto = "Aprobado";
            }elseif($estado == 3){
                $estado_texto = "Asentado";
            }elseif($estado == 4){
                $estado_texto = "Anulado";
            }else{
                $estado_texto = "";
            }
            $sheet->setCellValue('B' . $rows, $estado_texto);
            // decodificar categoria: 
            $categoria = $un_recaudo['categoria'];
            if($categoria == 1){
                $categoria_texto = "Anticipo";
            }elseif($categoria == 2){
                $categoria_texto = "Pago facturas";
            }else{
                $categoria_texto = "";
            }
            $sheet->setCellValue('C' . $rows, $categoria_texto);
            $sheet->setCellValue('D' . $rows, $un_recaudo['nom_cliente']);
            $sheet->setCellValue('E' . $rows, $un_recaudo['comercial']);
            $sheet->setCellValue('F' . $rows, $un_recaudo['valor_recaudo']);
            // decodificar tipo: 
            $tipo = $un_recaudo['tipo'];
            if($tipo == 1){
                $tipo_texto = "Efect";
            }elseif($tipo == 2){
                $tipo_texto = "Consig";
            }else{
                $tipo_texto = "";
            }
            $sheet->setCellValue('G' . $rows, $tipo_texto);
            $sheet->setCellValue('H' . $rows, $un_recaudo['fec_pago']);
            $sheet->setCellValue('I' . $rows, $un_recaudo['obs']);
            $sheet->setCellValue('J' . $rows, $un_recaudo['valor_asiento']);
            $sheet->setCellValue('K' . $rows, $un_recaudo['notas_asiento']);
            $sheet->setCellValue('L' . $rows, $un_recaudo['valor_diferencia']);
            $sheet->setCellValue('M' . $rows, $un_recaudo['fec_creado']);
            $sheet->setCellValue('N' . $rows, $un_recaudo['usu_creado']);
            $sheet->setCellValue('O' . $rows, $un_recaudo['fec_aprobado']);
            $sheet->setCellValue('P' . $rows, $un_recaudo['usu_aprobado']);
            $sheet->setCellValue('Q' . $rows, $un_recaudo['fec_asentado']);
            $sheet->setCellValue('R' . $rows, $un_recaudo['usu_asentado']);
            $sheet->setCellValue('S' . $rows, $un_recaudo['fec_anulado']);
            $sheet->setCellValue('T' . $rows, $un_recaudo['usu_anulado']);
            $rows++;		
		}	   

        // Anchos de columna autosize: 
        // no hay necesidad de hacer columna por columna, todas las siguientes
        // instrucciones fueron reemplazadas por el foreach:
        // $sheet->getColumnDimension('A')->setAutoSize(true);
        // $sheet->getColumnDimension('B')->setAutoSize(true);
        // $sheet->getColumnDimension('C')->setAutoSize(true);
        // $sheet->getColumnDimension('D')->setAutoSize(true);
        // $sheet->getColumnDimension('E')->setAutoSize(true);
        // $sheet->getColumnDimension('F')->setAutoSize(true);
        // $sheet->getColumnDimension('G')->setAutoSize(true);
        // $sheet->getColumnDimension('H')->setAutoSize(true);
        // $sheet->getColumnDimension('I')->setAutoSize(true);
        // $sheet->getColumnDimension('J')->setAutoSize(true);
        // $sheet->getColumnDimension('K')->setAutoSize(true);
        // $sheet->getColumnDimension('L')->setAutoSize(true);
        // $sheet->getColumnDimension('M')->setAutoSize(true);
        // $sheet->getColumnDimension('N')->setAutoSize(true);
        // $sheet->getColumnDimension('O')->setAutoSize(true);
        // $sheet->getColumnDimension('P')->setAutoSize(true);
        // $sheet->getColumnDimension('Q')->setAutoSize(true);
        // $sheet->getColumnDimension('R')->setAutoSize(true);
        foreach (range('B','T') as $col){
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }        
        // $sheet->getColumnDimension('A')->setWidth(20);  si se quiere un ancho fijo

		// para guardar el archivo en la carpeta public del localhost/hosting: 
	    // $fileName = "emp.xlsx";
		// $writer = new Xlsx($spreadsheet);
		// $writer->save($fileName);
		// header("Content-Type: application/vnd.ms-excel");
        // return redirect()->route('dashboard');    

		// para descargar el archivo donde quiera el usuario: 
		// tener en cuenta que en este caso, al contrario de cuando se graba en la carpeta
		// public, desdues del save() el programa no ejecutará ninguna linea mas:
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="info-recaudos.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');		
	}
}
