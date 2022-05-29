<div>

    @livewire('menu-own')

    <div class="bg-white border rounded border-gray-300 m-8 p-5 text-gray-600">

        <div class="">
            <h1 class="font-black text-4xl uppercase">Aprobar recaudos (para asentar)</h1>
        </div>

        @if (count($recaudos_para_aprobar) == 0)
            <p class="text-center font-bold text-xl text-gray-600 pt-4 ">No existen recaudos pendientes por aprobar.</p>    
        @else
            <div class="flex  my-4">
                <div class="w-1/4">
                    <div class="font-black text-2xl text-center">
                        Recaudos sin aprobar
                    </div>
                    <div class="font-black text-2xl text-center">
                        {{$nuevos}}
                    </div>
                </div>
                <div class="w-1/4">
                    <div class="font-black text-2xl text-center">
                        Recaudos aprobados
                    </div>
                    <div class="font-black text-2xl text-center">
                        {{$aprobados}}
                    </div>
                </div>
                <div class="w-1/4 ml-4">
                    <a href="{{route('dashboard')}}" class=""> 
                        <button type="button" wire:click="grabar_aprobar"  class="w-full bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3 font-semibold"> Grabar</button>
                    </a>
                </div>
                <div class="w-1/4 ml-4">
                    <a href="{{route('dashboard')}}" class="">                
                        <button type="button"  class="w-full bg-red-600 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold"> Cancelar</button>
                    </a>    
                </div>
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
                                <span class="text-white font-bold">Aprobar</span>
                            </th>                            

                        </tr>
                    </thead>
                    <tbody class="bg-gray-200">
                        @foreach ($recaudos_para_aprobar as $recaudo)
                            @php
                                // $arr_recaudo = [];
                                $fila_aprobado = array_search($recaudo->id , array_column($arr_aprobados, 'id'));
                                $arr_recaudo['estado'] = $arr_aprobados[$fila_aprobado]['estado'];
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
                                    @if ($arr_recaudo['estado'] == 2)
                                        {{-- el recaudo tiene estado 2, o sea APROBADO --}}
                                        <button  wire:click="aprobar({{$recaudo->id}} , 1)"   class="bg-white text-green-500  hover:bg-white  hover:text-green-700 ">
                                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button> 
                                    @else 
                                        <button wire:click="aprobar({{$recaudo->id}} , 2)"  class="bg-white text-red-500  hover:bg-white  hover:text-red-700 ">
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
  
