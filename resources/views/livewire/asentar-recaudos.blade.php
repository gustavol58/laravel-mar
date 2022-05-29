<div>

    @livewire('menu-own')

{{-- @php
    echo "<pre>";
    print_r($recaudos_sin_asentar);
    exit;    
@endphp --}}

    <div class="bg-white border rounded border-gray-300 m-8 p-5 text-gray-600">

        <div class="flex mb-10 h-24 ">
            <div class="w-1/2">
                <h1 class="font-bold text-4xl uppercase mb-3">Asentar recaudos</h1>
            </div>

            <div class="w-1/2">
                <p class="bg-green-500 text-white text-center">Explicación de la columna "Asentar":</p>
                
                <div class="flex mb-2">
                    <div class="w-1/2 bg-white text-red-500 ">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        No serán asentados al grabar esta pantalla.
                    </div>
                    <div class="w-1/2 bg-white text-green-500 ">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Serán asentados al grabar esta pantalla.
                    </div>
                    
                </div>
            </div>

        </div>

        @if ($mensaje)
            <div class="mb-4 alert flex flex-row items-center bg-yellow-200 p-5 rounded border-b-2 border-yellow-300">
                <div class="alert-icon flex items-center bg-yellow-100 border-2 border-yellow-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
                    <span class="text-yellow-500">
                        <svg fill="currentColor"
                             viewBox="0 0 20 20"
                             class="h-6 w-6">
                            <path fill-rule="evenodd"
                                  d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </div>
                <div class="alert-content ml-4">
                    <div class="alert-title font-semibold text-lg text-yellow-800">
                        Algunos recaudos no pudieron ser asentados.
                    </div>
                    <div class="alert-description text-sm text-yellow-600">
                        {{ $mensaje }}
                    </div>
                </div>
            </div>
        @endif


        @if (count($recaudos_sin_asentar) == 0)
            <p class="text-center font-bold text-xl text-gray-600 pt-4 ">¡ Felicitaciones ! No existen recaudos pendientes por asentar.</p>    
        @else

            <div class="flex mb-4">
                <p class="w-1/2">
                    Actualmente hay {{count($recaudos_sin_asentar)}} recaudos sin asentar.
                </p>
                <button type="button" wire:click="grabar()"  class="w-1/2 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold"> Grabar</button>
            </div>

            {{-- <div class="overflow-hidden overflow-x-scroll"> --}}
            <div class="overflow-hidden overflow-x-scroll">
                
                <table class="table-fixed ">
                    <thead class="justify-between">
                        <tr class="bg-green-500">
                            <th class="">
                                <span class="text-white font-bold">Nro</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Categoria</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Cliente</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Fecha del comprobante</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Valor</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Foto</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Tipo</span>
                            </th>
                
                            <th class="">
                                <span class="text-white font-bold">Observaciones</span>
                            </th>
                
                            <th class="">
                                <span class="text-white font-bold">Ingresado por</span>
                            </th>
                            
                            <th class="">
                                <span class="text-white font-bold">Fecha ingreso</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Notas al asiento</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Valor del asiento</span>
                            </th>

                            <th class="">
                                <span class="text-white font-bold">Asentar</span>
                            </th>                            

                        </tr>
                    </thead>
                    <tbody class="bg-gray-200">
                        @foreach ($recaudos_sin_asentar as $recaudo)
                            @php
                                // $arr_recaudo = [];
                                $fila_asiento = array_search($recaudo->id , array_column($arr_asientos, 'id'));
                                $arr_recaudo['asentado'] = $arr_asientos[$fila_asiento]['asentado'];
                                $arr_recaudo['notas_asiento'] = $arr_asientos[$fila_asiento]['notas_asiento'];
                                $arr_recaudo['valor_asiento'] = $arr_asientos[$fila_asiento]['valor_asiento'];
                            @endphp
                            <tr class="bg-white border-4 border-gray-200">
                                <td class="border border-gray-300">
                                    <span class="text-center ml-2 font-semibold">{{$recaudo->id}}</span>
                                </td>

                                <td class="border border-gray-300">
                                    <span>{{$recaudo->categoria_texto}}</span>
                                </td>

                                <td class="border border-gray-300">
                                    <span class="text-center ml-2 font-semibold">{{$recaudo->cliente}}</span>
                                </td>

                                <td class="border border-gray-300">
                                    <span>{{$recaudo->fec_pago}}</span>
                                </td>

                                <td class="border border-gray-300 text-right">
                                    <span>{{number_format($recaudo->valor,0)}}</span>
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
                                    <span>{{$recaudo->obs}}</span>
                                </td>

                                <td class="border border-gray-300">
                                    <span>{{ucwords($recaudo->usu_creacion)}}</span>
                                </td>

                                <td class="border border-gray-300">
                                    <span>{{$recaudo->fec_creacion}}</span>
                                </td>


                                <td class="border border-gray-300">
                                    <input type="text" wire:model="arr_asientos.{{$fila_asiento}}.notas_asiento" class="w-full  shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                </td>

                                <td class="border border-gray-300">
                                    <input type="number" wire:model="arr_asientos.{{$fila_asiento}}.valor_asiento" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                </td>


                                <td class="border border-gray-300">
                                    @if ($arr_recaudo['asentado'])
                                        <button  wire:click="asentar({{$recaudo->id}})"   class="bg-white text-green-500  hover:bg-white  hover:text-green-700 ">
                                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button> 
                                    @else 
                                        <button wire:click="asentar({{$recaudo->id}})"  class="bg-white text-red-500  hover:bg-white  hover:text-red-700 ">
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

    {{-- Modal --}}
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


