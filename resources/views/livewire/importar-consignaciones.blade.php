<div>
    @livewire('menu-own')

    {{-- <div class="bg-white border rounded border-gray-300 m-8 p-5 text-gray-600"> --}}
        <div class="bg-white  text-gray-600 h-full w-full" >
        <div class="flex mb-10 h-24 ">
            <div class="w-1/2">
                <h1 class="text-center font-bold text-4xl uppercase mb-3">Importar consignaciones</h1>
                <h2 class=" text-2xl mb-3 ml-2">{{count($consignaciones)}} consignaciones cargadas desde el archivo:<br> <i><center>{{$archivo_extracto_nombre}}</center></i></h2>
            </div>

            <div class="w-1/2">
                <p class="bg-green-500 text-white text-center">Explicación de la columna "Importar":</p>
                
                <div class="flex mb-2 border border-green-500">
                    <div class="w-1/2 bg-white text-green-500 ">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Serán importados al presionar el botón "Grabar".
                    </div>
                    <div class="w-1/2 bg-white text-red-500 ">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        No serán importados al presionar el botón "Grabar".
                    </div>                    
                </div>
            </div>            
        </div>

        {{-- PENDIENTE determinar si aqui va el bloque @if ($mensaje) --}}

        @if (count($consignaciones) == 0)
            <p class="text-center font-bold text-xl text-gray-600 pt-4 ">No existen consignaciones cargadas pendientes por importar.</p>    
        @else
            {{-- nro de consignaciones cargadas y botones:  --}}
            <div class="flex mb-4">
                {{-- Botón ver no cargadas:  --}}
                <div class="w-1/2">
                    <td class="border border-gray-300 px-4 text-red-500">
                        <button type="button" wire:click="mostrar_modal_no_cargadas()"  
                            class="w-full ml-2  bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3 font-semibold"> 
                            Click aquí para ver {{count($arr_no_cargadas)}} filas que no fueron cargadas
                        </button>
                    </td>
                </div>
                {{-- Botón grabar:  --}}
                <div class="w-1/4 ml-4">
                    {{-- <a href="{{route('extractos-bancarios')}}" class="">  --}}
                        <button type="button" wire:click="grabar_importar()"  class="w-full bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3 font-semibold"> Grabar</button>
                    {{-- </a> --}}
                </div>
                {{-- Botón cancelar:  --}}
                <div class="w-1/4 ml-4">
                    <a href="{{route('extractos-bancarios')}}" class="">                
                        <button type="button"  class="w-full bg-red-600 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold"> Cancelar</button>
                    </a>    
                </div>            
            </div>

            {{-- tabla con los registros:  --}}
            {{-- <div class="overflow-hidden overflow-x-scroll"> --}}
            <div class="overflow-scroll " style="height: 68vh;">
                <table class="table-fixed ">
                    <thead class="justify-between">
                        <tr class="bg-green-500">
                            <th class="">
                                <span class="text-white font-bold">Fecha</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Documento</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Oficina</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Descripción</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Referencia</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Valor</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Observaciones</span>
                            </th>
                
                            <th class="">
                                <span class="text-white font-bold">Importar</span>
                            </th>                            
                        </tr>
                    </thead>
                    
                    <tbody class="bg-gray-200">
                        @foreach ($consignaciones as $consignacion)
                           
                            <tr class="bg-white border-4 border-gray-200">
                                <td class="border border-gray-300">
                                    <span>{{$consignacion[1]}}</span>
                                </td>


                                <td class="border border-gray-300">
                                    <span>{{$consignacion[2]}}</span>
                                </td>

                                <td class="border border-gray-300">
                                    <span>{{$consignacion[3]}}</span>
                                </td>

                                <td class="border border-gray-300">
                                    <span>{{$consignacion[4]}}</span>
                                </td>

                                <td class="border border-gray-300">
                                    <span>{{$consignacion[5]}}</span>
                                </td>

                                <td class="border border-gray-300 text-right">
                                    <span>{{number_format($consignacion[6],2,'.',',')}}</span>
                                </td> 

                                <td class="border border-gray-300">
                                    <span>{{$consignacion[7]}}</span>
                                </td>

                                <td class="border border-gray-300">
                                    @if ($consignacion[8])
                                        {{-- consignación se debe importar: --}}
                                        <button  wire:click="importar({{$consignacion[9]}} , false)"   class="bg-white text-green-500  hover:bg-white  hover:text-green-700 ">
                                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button> 
                                    @else 
                                        <button wire:click="importar({{$consignacion[9]}} , true)"  class="bg-white text-red-500  hover:bg-white  hover:text-red-700 ">
                                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>                                                                                
                                    @endif
                                </td>                                

                            </tr>                            
                        @endforeach
                    </tbody>                    
                </table>
            </div>

        @endif        


    </div>


    {{-- Modal para mostrar las filas no cargadas --}}
    <x-jet-dialog-modal wire:model="no_cargadas_visible">
        <x-slot name="title">
            <span class="text-gray-500">Filas que no fueron cargadas del archivo: {{$archivo_extracto_nombre}}</span>
        </x-slot>
    
        <x-slot name="content">  
            <div class="flex justify-center">
                <table class="table-fixed">
                    <thead class="justify-between">
                        <tr class="bg-green-500">
                            <th class="">
                                <span class="px-2 text-white font-bold">Número de fila</span>
                            </th>
                            <th class="">
                                <span class="text-white font-bold">Dato</span>
                            </th>
                            <th class="">
                                <span class="text-white font-bold">Causas</span>
                            </th>
                        </tr>
                    </thead>
    
                    <tbody class="bg-gray-200">
                        @foreach ($arr_no_cargadas as $item)
                            <tr class="bg-white border-4 border-gray-200">
                                <td class="text-center border border-gray-300">
                                    <span>{{$item[0]}}</span>
                                </td>
                                <td class="text-right  px-2 border border-gray-300">
                                    <span>{{$item[2]}}</span>
                                </td>
                                <td class="px-2 border border-gray-300">
                                    <span>{{$item[1]}}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>   
            </div>
        </x-slot>
    
        <x-slot name="footer">
            <div class="mx-auto w-1/4 mt-2">
                <button type="button" wire:click="$set('no_cargadas_visible', false)" class="w-full bg-green-500 hover:bg-green-400 focus:bg-green-400 text-white rounded-lg px-3 py-3 font-semibold" > Cerrar</button>
            </div>
        </x-slot>
    </x-jet-confirmation-modal>

</div>
