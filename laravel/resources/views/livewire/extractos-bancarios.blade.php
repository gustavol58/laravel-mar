<div>
    @livewire('menu-own')

    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
        {{-- título  --}}
        <div class="">
            <h1 class="font-bold text-xl uppercase">Extractos bancarios</h1>
        </div>

        {{-- botones  --}}
        <div class="flex  my-4">
            <div class="w-1/4">
                <button type="button"  wire:click="mostrar_modal_cargar_archivo()"
                    class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6" viewBox="0 0 24 24"><path fill="currentColor" d="M12,3C8.59,3 5.69,4.07 4.54,5.57L9.79,10.82C10.5,10.93 11.22,11 12,11C16.42,11 20,9.21 20,7C20,4.79 16.42,3 12,3M3.92,7.08L2.5,8.5L5,11H0V13H5L2.5,15.5L3.92,16.92L8.84,12M20,9C20,11.21 16.42,13 12,13C11.34,13 10.7,12.95 10.09,12.87L7.62,15.34C8.88,15.75 10.38,16 12,16C16.42,16 20,14.21 20,12M20,14C20,16.21 16.42,18 12,18C9.72,18 7.67,17.5 6.21,16.75L4.53,18.43C5.68,19.93 8.59,21 12,21C16.42,21 20,19.21 20,17" /></svg> Importar consignaciones</button>
            </div>
            <div class="w-1/4 ml-12">
                <a href="{{route('dashboard')}}" >                
                    <button type="button"  
                    class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg> Regresar</button>
                </a>    
            </div>   
        </div>

        {{-- Registros --}}
        <div class="overflow-scroll " style="height: 65vh;">
            {{ $consignaciones->links() }}
            <table class="table-fixed ">
                {{-- títulos de columna y botones para ordenar --}}
                <thead class="justify-between">
                    <tr class="bg-green-500">
                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('con.id')">Nro</button>
                                </div>
                                @if($ordenar_campo == 'con.id' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'con.id' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('con.original')">Archivo original</button>
                                </div>
                                @if($ordenar_campo == 'con.original' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'con.original' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('con.fecha')">Fecha</button>
                                </div>
                                @if($ordenar_campo == 'con.fecha' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'con.fecha' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                  
                            </div>
                        </th>

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('con.valor')">Valor</button>
                                </div>
                                @if($ordenar_campo == 'con.valor' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'con.valor' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>   

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('con.referencia')">Referencia</button>
                                </div>
                                @if($ordenar_campo == 'con.referencia' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'con.referencia' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>                           

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1 ">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('con.estado')">Estado</button>
                                </div>
                                @if($ordenar_campo == 'con.estado' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'con.estado' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif 
                            </div>
                        </th>

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('con.recaudo_id')">Asignada&nbsp;al recaudo</button>
                                </div>
                                @if($ordenar_campo == 'con.recaudo_id' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'con.recaudo_id' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>
                        
                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('con.created_importo_at')">Fec importación</button>
                                </div>
                                @if($ordenar_campo == 'con.created_importo_at' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'con.created_importo_at' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                   
                            </div>
                        </th>   

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('usu1.name')">Importó</button>
                                </div>
                                @if($ordenar_campo == 'usu1.name' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'usu1.name' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                   
                            </div>
                        </th>  

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('con.created_asigno_at')">Fec asignación</button>
                                </div>
                                @if($ordenar_campo == 'con.created_asigno_at' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'con.created_asigno_at' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                   
                            </div>
                        </th>                           

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500 border-green-500 rounded" type="button" wire:click="ordenar('usu2.name')">Asignó</button>
                                </div>
                                @if($ordenar_campo == 'usu2.name' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'usu2.name' && $ordenar_tipo == ' desc') 
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
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="nro_consignacion">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="archivo_original">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fecha">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="valor">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="referencia">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="estado">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="recaudo_id">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fec_importo">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="importo">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fec_asigno">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="asigno">
                        </td>
                    </tr>

                    {{-- cuerpo de la tabla  --}}
                    @foreach ($consignaciones as $consignacion)
                        <tr class="bg-white border-4 border-gray-200">
                            <td class="border border-gray-300">
                                <span>{{$consignacion->id}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$consignacion->original}}</span>
                            </td>   

                            <td class="border border-gray-300">
                                <span>{{$consignacion->fecha}}</span>
                            </td>                              

                            <td class="border border-gray-300 text-right">
                                <span>{{number_format($consignacion->valor,2,'.',',')}}</span>
                            </td>  

                            <td class="border border-gray-300">
                                <span>{{$consignacion->referencia}}</span>
                            </td>                                 

                            <td class="border border-gray-300">
                                <span>{{$consignacion->estado_texto}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$consignacion->recaudo_id}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$consignacion->fec_importo}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$consignacion->importo}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$consignacion->fec_asigno}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$consignacion->asigno}}</span>
                            </td>

                            {{-- botón anular  --}}
                            @if($consignacion->estado == 2)
                                <td>&nbsp;</td>
                            @else
                                <td class="border border-gray-300 px-4 text-red-500">
                                    <button type="button" wire:click="mostrar_modal_eliminar({{$consignacion->id}} , '{{$consignacion->fecha}}' ,  {{$consignacion->valor}} , '{{$consignacion->importo}}')">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>                                        
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
        
    </div>

    {{-- Modal para mostrar la confirmación de anular un recaudo --}}
    <x-jet-dialog-modal wire:model="eliminar_modal_visible">
        <x-slot name="title">
            <span class="text-gray-500">CONFIRMACIÓN PARA ELIMINAR UNA CONSIGNACIÓN.</span>
            <br><br>
            ¿Está seguro de eliminar la siguiente consignación?<br>
            Esta acción no puede devolverse.
            <table>
                <tr>
                    <td class="w-28"></td>
                    <td>Número de consignación</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->consignacion_id_eliminar}}</td>
                </tr>
                <tr>
                    <td class="w-28"></td>
                    <td>Fecha</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->fecha_eliminar}}</td>
                </tr>
                <tr>
                    <td class="w-28"></td>
                    <td>Valor</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{number_format($this->valor_eliminar,2,'.',',')}}</td>
                </tr>
                <tr>
                    <td class="w-28"></td>
                    <td>Importado por</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->importo_eliminar}}</td>
                </tr>
            </table>
        </x-slot>
    
        <x-slot name="content"> 
            <form wire:submit.prevent="submit_delete()">                
                {{-- Buttons --}}
                <div class="flex mx-auto">
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="submit" class=" w-full bg-red-600 hover:bg-red-400 focus:bg-red-400  text-white rounded-lg px-3 py-3 font-semibold"> Anular</button>
                    </div>
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" class="w-full bg-green-500 hover:bg-green-400 focus:bg-green-400 text-white rounded-lg px-3 py-3 font-semibold" wire:click="$set('eliminar_modal_visible', false)"> Cancelar</button>
                    </div>
                </div>                 
            </form>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>

    {{-- Modal para seleccionar el archivo a cargar --}}
    <x-jet-dialog-modal wire:model="cargar_archivo_modal_visible">
        <x-slot name="title">
            <span class="text-gray-500">CARGAR ARCHIVO DE EXTRACTOS (BANCOLOMBIA)</span>
            <br><br>

        </x-slot>
    
        <x-slot name="content"> 
            <form wire:submit.prevent="submit_cargar()" enctype="multipart/form-data">
                {{-- pedir archivo a cargar  --}}
                <label class="m-auto w-64 flex  items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide  border border-blue cursor-pointer hover:bg-blue hover:text-blue-400">
                    <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                    </svg>
                    <span class="mt-2 ml-4 text-base leading-normal">Seleccione archivo...</span>
                    <input type="file" 
                        wire:model="archivo_extracto" 
                        id="idextracto" 
                        class="hidden"
                    >
                </label>   
                {{-- mensaje 'cargando...'  --}}
                <div class="text-center">                 
                    <div wire:loading wire:target="archivo_extracto" class="text-sm text-gray-500 italic">Cargando...</div>
                </div>
                {{-- mostrar el nombre del archivo que acaba de ser cargado:  --}}
                <div class="text-center">
                    @if($archivo_extracto)
                        {{$archivo_extracto->getClientOriginalName()}}
                    @endif
                </div>
                @error('archivo_extracto') <span class="text-red-500"><br>{{ $message }}</span> @enderror   
                
                <br><br>Recuerde que el archivo que va a cargar debe cumplir con los siguientes requisitos:<br>
                <li>La información a cargar debe estar en la primera hoja del libro excel.</li>
                <li>Se tomara la información de las 6 primeras columnas, en la columna 1 debe estar la fecha y en la columna 6 el valor del recaudo.</li>
                <li>En la primera fila de la hoja debe estar el nombre de cada columna, lo que significa que la información propiamente dicha comenzará a partir de la fila número 2.</li>
                <li>Las fechas (primera columna) deben estar en cualquier formato válido de fecha de excel. </li>
                <li>Los valores (última columna) deben tener el formato #,###.## (El separador de miles es coma, el separador decimal es punto. Dos decimales, si hay más se hará redondeo al céntimo.)</li>
                <li>Las filas que tengan fechas o valores con formatos incorrectos, no serán cargadas.</li>
                {{-- Buttons --}}
                <div class="flex mx-auto">
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="submit" class=" w-full bg-blue-600 hover:bg-blue-400 focus:bg-blue-400  text-white rounded-lg px-3 py-3 font-semibold"> Cargar archivo</button>
                    </div>
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" class="w-full bg-green-500 hover:bg-green-400 focus:bg-green-400 text-white rounded-lg px-3 py-3 font-semibold" wire:click="$set('cargar_archivo_modal_visible', false)"> Cancelar</button>
                    </div>
                </div>                 
            </form>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>

</div>


