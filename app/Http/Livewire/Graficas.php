<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\Auth;

class Graficas extends Component
{
    public $arr_meses_nombres;

    public function mount(){
        $this->arr_meses_nombres = ['' , 'Ene' , 'Feb' , 'Mar' , 'Abr' , 'May' , 'Jun' , 'Jul' , 'Ago' , 'Sep' , 'Oct' , 'Nov' , 'Dic'];
    }

    public function render()
    {
        $fec_6mesesatras = $this->obtener_6mesesatras();
        $fec_dia1 = date('Y')."-".date('m')."-01";

        // 17mar2021 
        // Markka pidió cambiar la primera gráfica, se debe poner una que 
        // muestre el valor ($) de las últimas 12 semanas: 
        $sql1 = "select subcon.* 
            from (
                    SELECT yearweek(fec_pago) semana, 
                        sum(valor) ingresados, 
                        sum(case when estado=3 then valor_asiento else 0 end) asentados 
                    FROM `recaudos` 
                       where estado <> 4
                    group by yearweek(fec_pago) 
                    order by yearweek(fec_pago) desc 
                    limit 12) subcon 
            order by subcon.semana";
        $recaudos1 = DB::select($sql1);
      
        $arr_semanas = [];
        $arr_ingresados = [];
        $arr_asentados = [];
        $arr_grafico = [];
        foreach($recaudos1 as $recaudo){
            array_push($arr_semanas, $this->ini_fin_semana($recaudo->semana));
            array_push($arr_ingresados, $recaudo->ingresados);
            array_push($arr_asentados, $recaudo->asentados);
        };
        $arr_grafico[0]['name'] = 'Ingresados'; 
        $arr_grafico[0]['data'] = $arr_ingresados;
        $arr_grafico[1]['name'] = 'Asentados'; 
        $arr_grafico[1]['data'] = $arr_asentados;

        $chart1 = (new LarapexChart)->setTitle('Valor de recaudos')
            ->setSubtitle('Últimas 12 semanas')
            ->setType('area')
            ->setDataset($arr_grafico)
            ->setXAxis($arr_semanas)
            ->setGrid(true)
        ;

        // Primera gráfica para admin y contab, que fue cambiada por Markka el 17mar2021: 
        // $arr_params =[];
        // $sql1 = "SELECT month(fec_pago) mes , 
        //         sum(1) ingresados, 
        //         sum((case when estado = 3 then 1 else 0 end)) asentados,
        //         min(fec_pago)
        //     FROM recaudos 
        //     where fec_pago BETWEEN :fec_ini and curdate() 
        //     group by 1 order by 4";
        // $arr_params = [
        //     'fec_ini' => $fec_6mesesatras
        // ];
        // $recaudos1 = DB::select($sql1, $arr_params);

        // $arr_meses = [];
        // $arr_ingresados = [];
        // $arr_asentados = [];
        // $arr_grafico = [];
        // foreach($recaudos1 as $recaudo){
        //     array_push($arr_meses, $this->arr_meses_nombres[$recaudo->mes]);
        //     array_push($arr_ingresados, $recaudo->ingresados);
        //     array_push($arr_asentados, $recaudo->asentados);
        // };
        // $arr_grafico[0]['name'] = 'Ingresados'; 
        // $arr_grafico[0]['data'] = $arr_ingresados;
        // $arr_grafico[1]['name'] = 'Asentados'; 
        // $arr_grafico[1]['data'] = $arr_asentados;

        // $chart1 = (new LarapexChart)->setTitle('Cantidad de recaudos')
        //     ->setSubtitle('Últimos 6 meses')
        //     ->setType('area')
        //     ->setDataset($arr_grafico)
        //     ->setXAxis($arr_meses)
        //     ->setGrid(true)
        // ;

        // Segunda gráfica para admin y contab: Total recaudos Vs asentados en pesos, de los últimos 6 meses:
        $arr_params =[];
        $sql2 = "SELECT month(fec_pago) mes , 
                sum(valor) ingresados, 
                sum((case when estado = 3 then valor_asiento else 0 end)) asentados,
                min(fec_pago)
            FROM recaudos 
            where fec_pago BETWEEN :fec_ini 
                and curdate() 
                and estado <> 4
            group by 1 
            order by 4";
        $arr_params = [
            'fec_ini' => $fec_6mesesatras
        ];
        $recaudos2 = DB::select($sql2, $arr_params);

        $arr_meses = [];
        $arr_ingresados = [];
        $arr_asentados = [];
        $arr_grafico = [];
        foreach($recaudos2 as $recaudo){
            array_push($arr_meses, $this->arr_meses_nombres[$recaudo->mes]);
            array_push($arr_ingresados, $recaudo->ingresados);
            array_push($arr_asentados, $recaudo->asentados);
        };
        $arr_grafico[0]['name'] = 'Ingresados'; 
        $arr_grafico[0]['data'] = $arr_ingresados;
        $arr_grafico[1]['name'] = 'Asentados'; 
        $arr_grafico[1]['data'] = $arr_asentados;

        $chart2 = (new LarapexChart)->setTitle('Valor de recaudos')
            ->setSubtitle('Últimos 6 meses')
            ->setType('area')
            ->setDataset($arr_grafico)
            ->setXAxis($arr_meses)
            ->setGrid(true)
        ;

        // Tercera gráfica: Recaudos por usuario:
        $arr_params =[];
        $sql3 = "SELECT usu.user_name usuario, 
                sum(1) ingresados,
                sum((case when estado = 3 then 1 end)) asentados  
            FROM recaudos rec 
                left join users usu on usu.id=rec.user_id 
            where rec.fec_pago BETWEEN :fec_ini and curdate() 
                and estado <> 4
            group by 1 
            order by 2 desc";
        $arr_params = [
            'fec_ini' => $fec_dia1
        ];
        $recaudos3 = DB::select($sql3, $arr_params);

        $arr_usuarios = [];
        $arr_ingresados = [];
        $arr_asentados = [];
        $arr_grafico = [];
        foreach($recaudos3 as $recaudo){
            array_push($arr_usuarios, $recaudo->usuario);
            array_push($arr_ingresados, $recaudo->ingresados);
            array_push($arr_asentados, $recaudo->asentados);
        };
        $arr_grafico[0]['name'] = 'Ingresados'; 
        $arr_grafico[0]['data'] = $arr_ingresados;
        $arr_grafico[1]['name'] = 'Asentados'; 
        $arr_grafico[1]['data'] = $arr_asentados;

        $chart3 = (new LarapexChart)->setTitle("Cantidad de recaudos por usuario")
            ->setSubtitle('Mes actual')
            ->setType('bar')
            ->setDataset($arr_grafico)
            ->setXAxis($arr_usuarios)
            ->setGrid(true)
            ->setColors(['#dc3545', '#ffc73c'])
        ;

        // Cuarta gráfica admin: Recaudos por cliente:
        $arr_params =[];
        $sql4 = "SELECT min(cli.nom_cliente) cliente, 
                sum(rec.valor) ingresados, 
                sum((case when rec.estado = 3 then rec.valor_asiento end)) asentados 
            FROM recaudos rec 
                left join clientes cli on cli.id=rec.cliente_id 
            where rec.fec_pago BETWEEN :fec_ini and curdate() 
               and rec.estado <> 4
            group by rec.cliente_id 
            order by 2 desc";
        $arr_params = [
            'fec_ini' => $fec_dia1
        ];
        $recaudos4 = DB::select($sql4, $arr_params);

        $arr_clientes = [];
        $arr_ingresados = [];
        $arr_asentados = [];
        $arr_grafico = [];
        foreach($recaudos4 as $recaudo){
            array_push($arr_clientes, $recaudo->cliente);
            array_push($arr_ingresados, $recaudo->ingresados);
            array_push($arr_asentados, $recaudo->asentados);
        };
        $arr_grafico[0]['name'] = 'Ingresados'; 
        $arr_grafico[0]['data'] = $arr_ingresados;
        $arr_grafico[1]['name'] = 'Asentados'; 
        $arr_grafico[1]['data'] = $arr_asentados;

        $chart4 = (new LarapexChart)->setTitle("Valor de recaudos por cliente")
            ->setSubtitle('Mes actual')
            ->setType('bar')
            ->setDataset($arr_grafico)
            ->setXAxis($arr_clientes)
            ->setGrid(true)
            ->setColors(['#ff6384', '#5e72e4'])
        ; 
        
        // '#0077B5', , , ,


        // Quinta gráfica para admin y contab
        $arr_params =[];
        $sql5 = "SELECT usu.user_name usuario, 
                sum(rec.valor) ingresados,
                sum((case when estado = 3 then rec.valor_asiento end)) asentados  
            FROM recaudos rec 
                left join users usu on usu.id=rec.user_id 
            where rec.fec_pago BETWEEN :fec_ini and curdate() 
                and estado <> 4
            group by 1 
            order by 2 desc";
        $arr_params = [
            'fec_ini' => $fec_dia1
        ];
        $recaudos5 = DB::select($sql5, $arr_params);

        $arr_usuarios = [];
        $arr_ingresados = [];
        $arr_asentados = [];
        $arr_grafico = [];
        foreach($recaudos5 as $recaudo){
            array_push($arr_usuarios, $recaudo->usuario);
            array_push($arr_ingresados, $recaudo->ingresados);
            array_push($arr_asentados, $recaudo->asentados);
        };
        $arr_grafico[0]['name'] = 'Ingresados'; 
        $arr_grafico[0]['data'] = $arr_ingresados;
        $arr_grafico[1]['name'] = 'Asentados'; 
        $arr_grafico[1]['data'] = $arr_asentados;

        $chart5 = (new LarapexChart)->setTitle("Valor de recaudos por usuario")
            ->setSubtitle('Mes actual')
            ->setType('bar')
            ->setDataset($arr_grafico)
            ->setXAxis($arr_usuarios)
            ->setGrid(true)
            ->setColors(['#dc3545', '#ffc73c'])
        ;

        // Primera gráfica para rol "comer" (no se usa en blade.php): 
        $arr_params =[];
        $sql1_comer = "SELECT month(fec_pago) mes , 
                sum(1) ingresados,
                min(fec_pago)
            FROM recaudos 
            where fec_pago BETWEEN :fec_ini and curdate() 
                and user_id = :user_id
            group by 1 
            order by 3";
        $arr_params = [
            'fec_ini' => $fec_6mesesatras,
            'user_id' => Auth::user()->id,
        ];
        $recaudos1_comer = DB::select($sql1_comer, $arr_params);

        $arr_meses = [];
        $arr_ingresados = [];
        $arr_grafico = [];
        foreach($recaudos1_comer as $recaudo){
            array_push($arr_meses, $this->arr_meses_nombres[$recaudo->mes]);
            array_push($arr_ingresados, $recaudo->ingresados);
         };
        $arr_grafico[0]['name'] = 'Ingresados'; 
        $arr_grafico[0]['data'] = $arr_ingresados;

        $chart1_comer = (new LarapexChart)->setTitle('Cantidad de recaudos')
            ->setSubtitle('Últimos 6 meses')
            ->setType('area')
            ->setDataset($arr_grafico)
            ->setXAxis($arr_meses)
            ->setGrid(true)
        ;

        // Segunda gráfica para rol "comer": 
        $arr_params =[];
        $sql2_comer = "SELECT month(fec_pago) mes , 
                sum(valor) ingresados,
                min(fec_pago)
            FROM recaudos rec
                left join clientes cli on cli.id=rec.cliente_id
                left join users usu on usu.id=cli.comercial_id
            where fec_pago BETWEEN :fec_ini and curdate() 
                and usu.id = :user_id
                and rec.estado <> 4
            group by 1 
            order by 3";
        $arr_params = [
            'fec_ini' => $fec_6mesesatras,
            'user_id' => Auth::user()->id,
        ];
        $recaudos2_comer = DB::select($sql2_comer, $arr_params);

        $arr_meses = [];
        $arr_ingresados = [];
        $arr_grafico = [];
        foreach($recaudos2_comer as $recaudo){
            array_push($arr_meses, $this->arr_meses_nombres[$recaudo->mes]);
            array_push($arr_ingresados, $recaudo->ingresados);
         };
        $arr_grafico[0]['name'] = 'Ingresados'; 
        $arr_grafico[0]['data'] = $arr_ingresados;

        $chart2_comer = (new LarapexChart)->setTitle('Valor de recaudos')
            ->setSubtitle('Últimos 6 meses')
            ->setType('area')
            ->setDataset($arr_grafico)
            ->setXAxis($arr_meses)
            ->setGrid(true)
            ->setColors(['#007F0E'])
        ;

        // Tercera gráfica para el rol "comer": 
        $arr_params =[];
        $sql3_comer = "SELECT min(cli.nom_cliente) cliente, 
                sum(rec.valor) ingresados
            FROM recaudos rec 
                left join clientes cli on cli.id=rec.cliente_id 
                left join users usu on usu.id=cli.comercial_id
            where rec.fec_pago BETWEEN :fec_ini and curdate()
                and usu.id = :user_id
                and rec.estado <> 4
            group by rec.cliente_id 
            order by 2 desc";
        $arr_params = [
            'fec_ini' => $fec_dia1,
            'user_id' => Auth::user()->id,
        ];
        $recaudos3_comer = DB::select($sql3_comer, $arr_params);

        $arr_clientes = [];
        $arr_ingresados = [];
        $arr_grafico = [];
        foreach($recaudos3_comer as $recaudo){
            array_push($arr_clientes, $recaudo->cliente);
            array_push($arr_ingresados, $recaudo->ingresados);
        };
        $arr_grafico[0]['name'] = 'Ingresados'; 
        $arr_grafico[0]['data'] = $arr_ingresados;

        $chart3_comer = (new LarapexChart)->setTitle("Valor de recaudos por cliente")
            ->setSubtitle('Mes actual')
            ->setType('bar')
            ->setDataset($arr_grafico)
            ->setXAxis($arr_clientes)
            ->setGrid(true)
            ->setColors(['#dc3545'])
        ;        

        $arr_graficas = ['chart1' , 'chart2' , 'chart3' , 'chart4' , 'chart5', 'chart1_comer' , 'chart2_comer' , 'chart3_comer'];
        return view('livewire.graficas', compact($arr_graficas));
    }

    private function obtener_6mesesatras(){
        // devuelve la fecha correspondiente 
        // al primer dia de 6 meses atrás 

        $arr_6mesesatras = [
            ['mes_act' => '', 'mes_ant' =>  '', 'nom1' =>  '', 'nom2' => ''],
            ['mes_act' => '01', 'mes_ant' =>  '08', 'nom1' =>  'Ago', 'nom2' => 'Agosto'],
            ['mes_act' => '02', 'mes_ant' =>  '09', 'nom1' =>  'Sep', 'nom2' => 'Septiembre'],
            ['mes_act' => '03', 'mes_ant' =>  '10', 'nom1' =>  'Oct', 'nom2' => 'Octubre'],
            ['mes_act' => '04', 'mes_ant' =>  '11', 'nom1' =>  'Nov', 'nom2' => 'noviembre'],
            ['mes_act' => '05', 'mes_ant' =>  '12', 'nom1' =>  'Dic', 'nom2' => 'Diciembre'],
            ['mes_act' => '06', 'mes_ant' =>  '01', 'nom1' =>  'Ene', 'nom2' => 'Enero'],
            ['mes_act' => '07', 'mes_ant' =>  '02', 'nom1' =>  'Feb', 'nom2' => 'Febrero'],
            ['mes_act' => '08', 'mes_ant' =>  '03', 'nom1' =>  'Mar', 'nom2' => 'Marzo'],
            ['mes_act' => '09', 'mes_ant' =>  '04', 'nom1' =>  'Abr', 'nom2' => 'Abril'],
            ['mes_act' => '10', 'mes_ant' =>  '05', 'nom1' =>  'May', 'nom2' => 'Mayo'],
            ['mes_act' => '11', 'mes_ant' =>  '06', 'nom1' =>  'Jun', 'nom2' => 'Juni'],
            ['mes_act' => '12', 'mes_ant' =>  '07', 'nom1' =>  'Jul', 'nom2' => 'Julio'],
        ];

        $mes_hoy = intval(date('m')); 
        $mes_ant = $arr_6mesesatras[$mes_hoy]['mes_ant'];

        $mes_6atras = $mes_hoy - 6;
        if($mes_6atras <= -1){
            $an_hoy = date('Y') - 1;
        }else{
            $an_hoy = date('Y');
        };

        $fecha_6mesesatras = $an_hoy."-".$mes_ant."-01";
        return $fecha_6mesesatras;

    }

    private function ini_fin_semana($semana){
        // Recibe una cadena tipo '202105' en donde viene el año y el número de 
        // semana, y debe devolver el primer dia (lunes) de esa semana y el 
        // último dia (domingo) de esa semana en un formato como por 
        // ejemplo: '1 - 7 feb' o '29 - 4 abr' 
        $semana = substr($semana , 0 , 4) . 'W' . substr($semana , 4,2);
        $dia_ini = date('j',strtotime($semana));
        $dia_fin_aux = $semana . ' + 6 days';
        $dia_fin_dia = date('j',strtotime($dia_fin_aux));
        $dia_fin_mes = date('n',strtotime($dia_fin_aux));
        return $dia_ini . ' - ' . $dia_fin_dia . ' ' . $this->arr_meses_nombres[$dia_fin_mes];
    }

}
