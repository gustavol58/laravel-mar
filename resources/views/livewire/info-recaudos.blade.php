<div>
    @livewire('menu-own')
    <div class="bg-white  text-gray-600 h-full w-full" x-data="{formu_fechas: @entangle('formu_cambiar_fechas')}">
        <div class="flex pt-2 ">
            <div class="w-2/4 ml-4 ">
                <p class="pl-4 w-full font-bold text-4xl text-center">Informe de recaudos</p>
                <center>(No se incluyen recaudos anulados)</center>
            </div>

            {{-- el botón de generar excel debe ir dentro de un form para poder 
            enviar la instrucción sql actual, en un campo hidden, al método NO-LIVEWIRE: --}}
            <div class="w-1/4 ml-4 ">
                <form action="{{route('hoja-info-recaudo')}}" method="post">
                    @csrf
                    {{-- objeto con toda la info:  --}}
                    <input type="hidden" name="recaudos" value="{{$recaudos}}">
                    {{-- rango de fechas  --}}
                    <input type="hidden" name="fec_ini" value="{{$this->format_fecha($fec_ini)}}">
                    <input type="hidden" name="fec_fin" value="{{$this->format_fecha($fec_fin)}}">
                    {{-- filtros:  --}}
                    <input type="hidden" name="filtro_nro" value="{{$nro}}">
                    <input type="hidden" name="filtro_estado" value="{{$estado}}">
                    <input type="hidden" name="filtro_categoria" value="{{$categoria}}">
                    <input type="hidden" name="filtro_nom_cliente" value="{{$nom_cliente}}">
                    <input type="hidden" name="filtro_comercial" value="{{$comercial}}">
                    <input type="hidden" name="filtro_valor_recaudo" value="{{$valor_recaudo}}">
                    <input type="hidden" name="filtro_tipo" value="{{$tipo}}">
                    <input type="hidden" name="filtro_fec_pago" value="{{$fec_pago}}">
                    <input type="hidden" name="filtro_obs" value="{{$obs}}">
                    <input type="hidden" name="filtro_valor_asiento" value="{{$valor_asiento}}">
                    <input type="hidden" name="filtro_notas_asiento" value="{{$notas_asiento}}">
                    <input type="hidden" name="filtro_valor_diferencia" value="{{$valor_diferencia}}">
                    <input type="hidden" name="filtro_fec_creado" value="{{$fec_creado}}">
                    <input type="hidden" name="filtro_usu_creado" value="{{$usu_creado}}">
                    <input type="hidden" name="filtro_fec_aprobado" value="{{$fec_aprobado}}">
                    <input type="hidden" name="filtro_usu_aprobado" value="{{$usu_aprobado}}">
                    <input type="hidden" name="filtro_fec_asentado" value="{{$fec_asentado}}">
                    <input type="hidden" name="filtro_usu_asentado" value="{{$usu_asentado}}">
                    <input type="hidden" name="filtro_fec_anulado" value="{{$fec_anulado}}">
                    <input type="hidden" name="filtro_usu_anulado" value="{{$usu_anulado}}">

                    <button type="submit"  class=" bg-green-500 hover:bg-green-400 focus:bg-green-400 text-white rounded-lg px-3 py-3">
                        <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 3H18C19.11 3 20 3.9 20 5V12.08C18.45 11.82 16.92 12.18 15.68 13H12V17H13.08C12.97 17.68 12.97 18.35 13.08 19H4C2.9 19 2 18.11 2 17V5C2 3.9 2.9 3 4 3M4 7V11H10V7H4M12 7V11H18V7H12M4 13V17H10V13H4M15.94 18.5H17.94V14.5H19.94V18.5H21.94L18.94 21.5L15.94 18.5" ></path>
                        </svg>
                        <span class="align-text-bottom font-semibold">Exportar a hoja electrónica</span>
                    </button>
                </form>
            </div>

            {{-- Botón cambiar rango fechas  --}}
            <div class="w-1/4 ml-4 pr-4">
                <button type="button"   wire:click="cambiar_fechas"   class=" bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3">
                    <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg> 
                    <span class="align-text-bottom font-semibold">Cambiar rango fechas</span>
                </button>
            </div>
        
        </div>

        <div class="flex  my-2 ">
            <div class="w-1/5">
                <div class="font-black text-2xl text-center">
                    Desde
                </div>
                <div class="font-black text-2xl text-center">
                    {{$this->format_fecha($fec_ini)}} 
                </div>
            </div>
            <div class="w-1/5">
                <div class="font-black text-2xl text-center">
                    Hasta
                </div>
                <div class="font-black text-2xl text-center">
                    {{$this->format_fecha($fec_fin)}} 
                </div>
            </div>
            <div class="w-1/5">
                <div class="font-black text-2xl text-center">
                    Recaudos
                </div>
                <div class="font-black text-2xl text-center">
                    {{number_format(count($recaudos),0,'.',',')}}
                </div>
            </div>
            <div class="w-1/5">
                <div class="font-black text-2xl text-center">
                    Valor recaudado
                </div>
                <div class="font-black text-2xl text-center">
                    $ {{number_format($recaudos->sum('valor_recaudo'),0,'.',',')}}
                </div>
            </div>
            <div class="w-1/5">
                <div class="font-black text-2xl text-center">
                    Valor asentado
                </div>
                <div class="font-black text-2xl text-center">
                    $ {{number_format($recaudos->sum('valor_asiento'),0,'.',',')}}
                </div>
            </div>

        </div>

        @if($formu_cambiar_fechas)

            <div class="my-2  border-4 rounded border-gray-300 "  x-show="formu_fechas">
                <form  x-on:click.away="formu_fechas=false" wire:submit.prevent="submit(document.getElementById('idfec_desde').value , document.getElementById('idfec_hasta').value)">

                    <div class="mt-3 text-center ">
                        <p class="text-gray-600 font-bold text-xl">CAMBIAR RANGO DE FECHAS</p>
                        
                        @if (session()->has('message'))
                            <div class="alert alert-success text-red-500">
                                {{ session('message') }}
                            </div>                            
                        @endif
            
                        {{-- Fecha desde --}}
                        <div class="flex mb-4">

                            <div class="w-1/4">
                                <label class="font-bold text-base my-auto mb-2 ml-1">Fecha inicial:</label>
                                {{-- debido a que el datapicker es de una libreria externa, se debe 
                                indicar a livewire que no lo actualice como los demás, para eso
                                son wire_ignore y wire:key --}}
                                <div wire:ignore wire:key="a">
                                    <input
                                        x-data
                                        x-ref="fec_desde_provis"
                                        x-init="new Pikaday({
                                            field: $refs.fec_desde_provis, 
                                            // defaultDate: moment().toDate(), 
                                            // setDefaultDate: true,
                                            i18n: {
                                                previousMonth : 'Anterior',
                                                nextMonth     : 'Siguiente',
                                                months        : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                                                weekdays      : ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
                                                weekdaysShort : ['Dom','Lun','Mar','Mie','Jue','Vie','Sáb']
                                            },
                                            format: 'YYYY-MM-DD',
                                            onSelect: function(date) {
                                                // alert('hola...');
                                                // field.value = this.getMoment().format('YYYY-MM-DD');
                                            }
                                        })"
                                        type="text"
                                        id="idfec_desde" 
                                        value="{{$fec_ini}}"
                                        class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                                    >
                                </div>
                            </div>

                            <div class="w-1/4">
                                <label class="font-bold text-base my-auto mb-2 ml-1">Fecha final:</label>
                                    {{-- Fecha hasta --}}
                                    {{-- debido a que el datapicker es de una libreria externa, se debe 
                                    indicar a livewire que no lo actualice como los demás, para eso
                                    son wire_ignore y wire:key --}}
                                    <div wire:ignore wire:key="b">
                                        <input
                                            x-data
                                            x-ref="fec_hasta_provis"
                                            x-init="new Pikaday({
                                                field: $refs.fec_hasta_provis, 
                                                // defaultDate: moment().toDate(), 
                                                // setDefaultDate: true,
                                                i18n: {
                                                    previousMonth : 'Anterior',
                                                    nextMonth     : 'Siguiente',
                                                    months        : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                                                    weekdays      : ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
                                                    weekdaysShort : ['Dom','Lun','Mar','Mie','Jue','Vie','Sáb']
                                                },
                                                format: 'YYYY-MM-DD',
                                                onSelect: function(date) {
                                                    // alert('hola...');
                                                    // field.value = this.getMoment().format('YYYY-MM-DD');
                                                }
                                            })"
                                            type="text"
                                            id="idfec_hasta" 
                                            value="{{$fec_hasta}}"
                                            class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                                        >
                                    </div>
                            </div>

                            <div class="w-1/4 ml-4 ">
                                {{-- botón cambiar fechas: --}}
                                <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3">
                                    <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="currentColor" d="M14,2A8,8 0 0,0 6,10A8,8 0 0,0 14,18A8,8 0 0,0 22,10H20C20,13.32 17.32,16 14,16A6,6 0 0,1 8,10A6,6 0 0,1 14,4C14.43,4 14.86,4.05 15.27,4.14L16.88,2.54C15.96,2.18 15,2 14,2M20.59,3.58L14,10.17L11.62,7.79L10.21,9.21L14,13L22,5M4.93,5.82C3.08,7.34 2,9.61 2,12A8,8 0 0,0 10,20C10.64,20 11.27,19.92 11.88,19.77C10.12,19.38 8.5,18.5 7.17,17.29C5.22,16.25 4,14.21 4,12C4,11.7 4.03,11.41 4.07,11.11C4.03,10.74 4,10.37 4,10C4,8.56 4.32,7.13 4.93,5.82Z" />
                                    </svg>
                                    
                                    <span class="align-text-bottom font-semibold">Modificar fechas</span>
                                </button>
                            </div>

                            <div class="w-1/4 ml-4 pr-4">
                                {{-- botón cancelar cambiar fechas: --}}
                                <button type="button"  wire:click="cancelar" class="w-full mt-4 bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3">
                                    <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="align-text-bottom font-semibold">Cancelar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endif        

        {{-- Registros: --}}
        <div class="overflow-scroll " style="height: 68vh;">
            <table class="table-fixed ">
                {{-- títulos de columna y botones para ordenar --}}
                <thead class="justify-between">
                    <tr class="bg-green-500">
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.id')">Nro</button>
                                </div>
                                @if($ordenar_campo == 'rec.id' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.id' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>                    

                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('rec.estado')">Estado</button>
                                </div>
                                @if($ordenar_campo == 'rec.estado' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.estado' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.categoria')">Categoria</button>
                                </div>
                                @if($ordenar_campo == 'rec.categoria' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.categoria' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                  
                            </div>
                        </th>

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('cli.nom_cliente')">Cliente</button>
                                </div>
                                @if($ordenar_campo == 'cli.nom_cliente' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'cli.nom_cliente' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>   

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('usucom.name')">Comercial</button>
                                </div>
                                @if($ordenar_campo == 'usucom.name' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'usucom.name' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>   

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.valor')">Valor recaudo</button>
                                </div>
                                @if($ordenar_campo == 'rec.valor' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.valor' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>   

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <span class="text-white font-bold bg-green-500 border-green-500 rounded">Foto</span>
                                </div>
                            </div>
                        </th>

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.tipo')">Tipo</button>
                                </div>
                                @if($ordenar_campo == 'rec.tipo' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.tipo' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>   

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.fec_pago')">Fec&nbsp;pago</button>
                                </div>
                                @if($ordenar_campo == 'rec.fec_pago' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.fec_pago' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>  
                        
                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.obs')">Observaciones</button>
                                </div>
                                @if($ordenar_campo == 'rec.obs' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.obs' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.valor_asiento')">Valor asiento</button>
                                </div>
                                @if($ordenar_campo == 'rec.valor_asiento' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.valor_asiento' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>   

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.notas_asiento')">Notas asiento</button>
                                </div>
                                @if($ordenar_campo == 'rec.notas_asiento' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.notas_asiento' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>  

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.valor - rec.valor_asiento')">Diferencia valores</button>
                                </div>
                                @if($ordenar_campo == 'rec.valor - rec.valor_asiento' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.valor - rec.valor_asiento' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>                          

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.created_at')">Fec&nbsp;creado</button>
                                </div>
                                @if($ordenar_campo == 'rec.created_at' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.created_at' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>                      

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('usuing.name')">Usu&nbsp;creó</button>
                                </div>
                                @if($ordenar_campo == 'usuing.name' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'usuing.name' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>                      

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.created_aprobo_at')">Fec&nbsp;aprobado</button>
                                </div>
                                @if($ordenar_campo == 'rec.created_aprobo_at' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.created_aprobo_at' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>                      

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('usuapro.name')">Usu&nbsp;aprobó</button>
                                </div>
                                @if($ordenar_campo == 'usuapro.name' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'usuapro.name' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>                      

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.created_asiento_at')">Fec&nbsp;asentado</button>
                                </div>
                                @if($ordenar_campo == 'rec.created_asiento_at' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.created_asiento_at' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>                      

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('usuasi.name')">Usu&nbsp;asentó</button>
                                </div>
                                @if($ordenar_campo == 'usuasi.name' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'usuasi.name' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>   

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('rec.created_anulo_at')">Fec&nbsp;anulado</button>
                                </div>
                                @if($ordenar_campo == 'rec.created_anulo_at' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.created_anulo_at' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>                      

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('usuanu.name')">Usu&nbsp;anuló</button>
                                </div>
                                @if($ordenar_campo == 'usuanu.name' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'usuanu.name' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>                      
                    </tr>
                </thead>

                {{-- filtros y cuerpo de la tabla  --}}
                <tbody class="bg-gray-200">
                    {{-- filtros  --}}
                    <tr>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="nro">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="estado">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="categoria">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="nom_cliente">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="comercial">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="valor_recaudo">
                        </td>
                        <td>
                            &nbsp;
                        </td>                        
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="tipo">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fec_pago">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="obs">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="valor_asiento">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="notas_asiento">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="valor_diferencia">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fec_creado">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="usu_creado">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fec_aprobado">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="usu_aprobado">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fec_asentado">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="usu_asentado">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fec_anulado">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="usu_anulado">
                        </td>
                    </tr>

                    {{-- cuerpo de la tabla  --}}
                    @foreach ($recaudos as $recaudo)
                        @if($recaudo->estado == 4)
                        <tr class="bg-yellow-400 border-4 border-gray-200">
                        @else 
                        <tr class="bg-white border-4 border-gray-200">
                        @endif                    
                            <td class="border border-gray-300">
                                <span>{{$recaudo->nro}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$recaudo->estado_texto}}</span>
                            </td>   

                            <td class="border border-gray-300">
                                <span>{{$recaudo->categoria_texto}}</span>
                            </td>                              

                            <td class="border border-gray-300">
                                <span>{{$recaudo->nom_cliente}}</span>
                            </td>   

                            <td class="border border-gray-300">
                                <span>{{$recaudo->comercial}}</span>
                            </td>   

                            <td class="border border-gray-300 text-right">
                                <span>{{number_format($recaudo->valor_recaudo,0)}}</span>
                            </td>

                            <td class="border border-gray-300 text-center">
                                @if ($recaudo->foto_existe_nombre == '')
                                    <span>&nbsp;</span>
                                @else
                                    <button type="button" wire:click="mostrar_modal_foto('{{$recaudo->foto_existe_nombre_path}}')" >
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg> 
                                    </button>
                                @endif
                            </td>                            
                            
                            <td class="border border-gray-300">
                                <span>{{$recaudo->tipo_texto}}</span>
                            </td>                            

                            <td class="border border-gray-300">
                                <span>{{$recaudo->fec_pago}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$recaudo->obs}}</span>
                            </td>
                            
                            <td class="border border-gray-300 text-right">
                                <span>{{number_format($recaudo->valor_asiento,0)}}</span>
                            </td>
                            
                            <td class="border border-gray-300">
                                <span>{{$recaudo->notas_asiento}}</span>
                            </td>

                            <td class="border border-gray-300 text-right">
                                <span>{{number_format($recaudo->valor_diferencia , 0)}}</span>
                            </td>                            

                            <td class="border border-gray-300">
                                <span>{{$recaudo->fec_creado}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$recaudo->usu_creado}}</span>
                            </td>  

                            <td class="border border-gray-300">
                                <span>{{$recaudo->fec_aprobado}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$recaudo->usu_aprobado}}</span>
                            </td>  

                            <td class="border border-gray-300">
                                <span>{{$recaudo->fec_asentado}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$recaudo->usu_asentado}}</span>
                            </td>  

                            <td class="border border-gray-300">
                                <span>{{$recaudo->fec_anulado}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$recaudo->usu_anulado}}</span>
                            </td>  
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal para mostrar la foto --}}
    <x-jet-dialog-modal wire:model="foto_modal_visible">
        <x-slot name="title">
            {{-- Botón cerrar --}}
            <div class="flex w-full justify-end">
                <button wire:click="$set('foto_modal_visible', false)"  
                class="bg-white text-red-500  hover:bg-white  hover:text-red-700 ">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
            </button>             

            </div>
        </x-slot>
    
        <x-slot name="content">
            {{-- <img src="{{$recaudo->foto_existe_nombre_path}}?{{ rand() }}" alt=""> --}}
            <img src="{{$foto_modal_src}}" alt="">
        </x-slot>
    
        <x-slot name="footer">
            {{-- <x-jet-secondary-button wire:click="$toggle('foto_modal_visible')" wire:loading.attr="disabled">
                Nevermind
            </x-jet-secondary-button>
    
            <x-jet-danger-button class="ml-2" wire:click="deleteUser" wire:loading.attr="disabled">
                Delete Account
            </x-jet-danger-button> --}}
        </x-slot>
    </x-jet-confirmation-modal>
    
</div>
