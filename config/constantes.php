<?php
    return [

        // 21ago2021
        // Los accesos se manejan con Spatie, el cual guarda los roles en una
        // tabla llamada roles, para no modificar la estructura de esa tabla
        // propia de spatie, se maneja el siguiente array cuyas claves son 
        // los nombres cortos de los roles grabados en la tabla de Spatie y
        // cuyo valor de cada clave es el nombre completo del rol: 
        'roles_nombres_largos' => [
            'admin' => 'Administrador',
            'contab' => 'Contabilidad',
            'comer' => 'Comercial',
            'produ' => 'Producción',
            'disen' => 'Diseño',
            'oper' => 'Operario',
        ],

        // Para verificar si la foto existe para un recaudo determinado:

        // localhost: 
        // 'path_foto_compte' => '/var/www/html/markka/public',
        // 'path_foto_compte_https' => 'https://???markka-pruebas22.tavohen.com',
        
        // hosting PRUEBAS22:   
        'path_foto_compte' => '/home/u306294386/domains/tavohen.com/public_html/markka-pruebas22',
        'path_foto_compte_https' => 'https://markka-pruebas22.tavohen.com',

        // hosting PRUEBAS: 
        // 'path_foto_compte' => '/home/u306294386/domains/tavohen.com/public_html/markka-pruebas',
        // 'path_foto_compte_https' => 'https://markka-pruebas.tavohen.com',

        
        // hosting PRODUCCIÓN: 
        // 'path_foto_compte' => '/home/tavohenc/public_html/markka',
        // 'path_foto_compte_https' => 'https://markka.tavohen.com',


        // Para mostrar la foto en un modal: 
        
        // localhost: 
        // 'path_inicial_foto_modal' => 'http://localhost/markka/public/storage/',
        
        // hosting PRUEBAS22: 
        'path_inicial_foto_modal' => 'https://markka-pruebas22.tavohen.com/storage/',

        // hosting PRUEBAS: 
        // 'path_inicial_foto_modal' => 'https://markka-pruebas.tavohen.com/storage/',
        
        // hosting PRODUCCIÓN: 
        // 'path_inicial_foto_modal' => 'https://tavohen.com/markka/storage/',




        // 01may2021
        // Para el manejo de formularios configurables (listas de selección) en 
        // el módulo de pedidos:
            // 'tablas_campos' => [
            //     'clientes' => [
            //         'alias' => 'cli',
            //         'titulo' => 'Clientes',
            //         'campos' => [
            //             'id' => [
            //                 'interno' => 'cli.id',
            //                 'titulo' => 'Id',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => false,
            //             ],
            //             'nit' => [
            //                 'interno' => 'cli.nit',
            //                 'titulo' => 'Nit',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],
            //             'nombre_cliente' => [
            //                 'interno' => 'cli.nom_cliente',
            //                 'titulo' => 'Nombre del cliente',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],
            //             'comercial_nombre' => [
            //                 'interno' => 'usu1.name',
            //                 'titulo' => 'Comercial asignado',
            //                 'left_join' => ' users usu1 on usu1.id=cli.comercial_id ',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],
            //         ],
            //     ],
            //     'recaudos' => [
            //         'alias' => 'rec',
            //         'titulo' => 'Recaudos',
            //         'campos' => [
            //             'id' => [
            //                 'interno' => 'rec.id',
            //                 'titulo' => 'Número de recaudo',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],                    
            //             'estado' => [
            //                 'interno' => 'rec.estado',
            //                 'titulo' => 'Estado',
            //                 'left_join' => '',
            //                 'equivalentes' => ['1' => 'Nuevo', '2' => 'Aprobado', '3' => 'Asentado', '4' => 'Anulado'],
            //                 'visible' => true,
            //             ],                    
            //             'categoria' => [
            //                 'interno' => 'rec.categoria',
            //                 'titulo' => 'Categoria(Anticipo/Pago)',
            //                 'left_join' => '',
            //                 'equivalentes' => ['1' => 'Anticipo', '2' => 'Pago facturas'],
            //                 'visible' => true,
            //             ],                    
            //             'cliente_nombre' => [
            //                 'interno' => 'cli.nom_cliente',
            //                 'titulo' => 'Nombre del cliente',
            //                 'left_join' => ' clientes cli on cli.id=rec.cliente_id ',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],                    
            //             'comercial_nombre' => [
            //                 'interno' => 'usu2.name',
            //                 'titulo' => 'Nombre del comercial',
            //                 'left_join' => ' clientes cli2 on cli2.id=rec.cliente_id left join users usu2 on usu2.id = cli2.comercial_id ',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],   
            //             'valor_recaudo' => [
            //                 'interno' => 'rec.valor',
            //                 'titulo' => 'Valor recaudado',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],                                          
            //             'tipo_recaudo' => [
            //                 'interno' => 'rec.tipo',
            //                 'titulo' => 'Tipo(Efectivo/Consignación)',
            //                 'left_join' => '',
            //                 'equivalentes' => ['1' => 'Efectivo', '2' => 'Consignación'],
            //                 'visible' => true,
            //             ],                                          
            //             'fecha_recaudo' => [
            //                 'interno' => 'rec.fec_pago',
            //                 'titulo' => 'Fecha recaudo',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],    
            //             'creado_por_nombre' => [
            //                 'interno' => 'usu3.name',
            //                 'titulo' => 'Creado por',
            //                 'left_join' => ' users usu3 on usu3.id = rec.user_id ',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],                                                             
            //         ],                
            //     ],
            //     'consignaciones' => [
            //         'alias' => 'con',
            //         'titulo' => 'Consignaciones',
            //         'campos' => [
            //             'id' => [
            //                 'interno' => 'con.id',
            //                 'titulo' => 'Número de consignación',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => false,
            //             ],                        
            //             'fecha' => [
            //                 'interno' => 'con.fecha',
            //                 'titulo' => 'Fecha consignación',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],                        
            //             'valor' => [
            //                 'interno' => 'con.valor',
            //                 'titulo' => 'Valor',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],                        
            //             'estado' => [
            //                 'interno' => 'con.estado',
            //                 'titulo' => 'Estado(Sin asignar/Asignada)',
            //                 'left_join' => '',
            //                 'equivalentes' => ['1' => 'Sin asignar', '2' => 'Asignada'],
            //                 'visible' => true,
            //             ], 
            //             'recaudo_nro' => [
            //                 'interno' => 'con.recaudo_id',
            //                 'titulo' => 'Número de recaudo asignado',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],                                                 
            //             'referencia' => [
            //                 'interno' => 'con.referencia',
            //                 'titulo' => 'Referencia (Cliente)',
            //                 'left_join' => '',
            //                 'equivalentes' => [],
            //                 'visible' => true,
            //             ],                                                 
            //         ],                
            //     ],
            // ],            
    ];