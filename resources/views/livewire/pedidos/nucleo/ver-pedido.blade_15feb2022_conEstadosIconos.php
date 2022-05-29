<div>
{{-- {{dd($registros)}}     --}}
    @livewire('menu-own')
    <div class="bg-white border rounded border-gray-300 m-2 p-2 text-gray-600">
        {{-- título y botones  --}}
        <div class="flex mb-2">
            <div class="w-4/5">
                <h1 class="font-black text-3xl ">Pedidos</h1>
            </div>
            {{-- botones  --}}
            <div class="flex w-1/5 items-end justify-end">
                {{-- botón NUEVO  --}}
                <a href="{{route('crear-pedido' , [
                        'operacion' => 'crear' ,
                    ])}}">
                    <button type="button"  
                        class="mr-8 h-8 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 font-semibold">
                        <svg class="inline-block align-bottom  text-white fill-current w-6 h-6 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/></svg>
                        Nuevo
                    </button>
                </a>

                {{-- botón REGRESAR  --}}
                <a href="{{route('dashboard')}}" >                
                    <button type="button"  
                        class="h-8 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 font-semibold">
                        <svg class="inline-block align-bottom  text-white fill-current w-6 h-6 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill="currentColor" d="M13.34,8.17C12.41,8.17 11.65,7.4 11.65,6.47A1.69,1.69 0 0,1 13.34,4.78C14.28,4.78 15.04,5.54 15.04,6.47C15.04,7.4 14.28,8.17 13.34,8.17M10.3,19.93L4.37,18.75L4.71,17.05L8.86,17.9L10.21,11.04L8.69,11.64V14.5H7V10.54L11.4,8.67L12.07,8.59C12.67,8.59 13.17,8.93 13.5,9.44L14.36,10.79C15.04,12 16.39,12.82 18,12.82V14.5C16.14,14.5 14.44,13.67 13.34,12.4L12.84,14.94L14.61,16.63V23H12.92V17.9L11.14,16.21L10.3,19.93M21,23H19V3H6V16.11L4,15.69V1H21V23M6,23H4V19.78L6,20.2V23Z" />
                        </svg> Regresar
                    </button>
                </a>  
            </div> 
        </div>
        {{-- fin de título y botones  --}}

        {{-- Registros: --}}
        @if ($registros->hasPages())
            {{ $registros->links() }}   
        @else 
            Mostrando {{count($registros)}} registros
        @endif  
        <br>  

        {{-- 24oct2021 Si la longitud del nombre del tipo de producto cabe en una 
        sola linea (65 caracteres o menos), el tamaño vh para poder ver 
        el scroll horizontal será de 58vh. Si es mayor el tamaño debe
        reducirse a 52vh, y asi sucesivamente: --}}
        <div class="overflow-scroll " style="height: 58vh;">
            <table class="table-fixed ">
                {{-- títulos de columna y botón ordenar en cada una --}}
                <thead class="justify-between">
                    <tr class="bg-green-500">
                        {{--pedido_conse  --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('concat(peddet.pedido_encab_id,\'_\',peddet.conse)')">
                                        <span class="px-2">Id.</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == "concat(peddet.pedido_encab_id,'_',peddet.conse)" && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == "concat(peddet.pedido_encab_id,'_',peddet.conse)" && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- estado  --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('peddet.estado_id')">
                                        <span class="px-2">Estado</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'peddet.estado_id' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'peddet.estado_id' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- nombre del cliente --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.nom_cliente')">
                                         <span class="px-2">Cliente</span>
                                      </button>
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
                        {{-- dirección entrega  --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('pedenc.dir_entrega')">
                                         <span class="px-2">Dirección de entrega</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'pedenc.dir_entrega' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'pedenc.dir_entrega' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                         </th>                        
                        {{-- nombre del tipo de producto  --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('fti.tipo_producto_nombre')">
                                        <span class="px-2">Tipo de producto</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'fti.tipo_producto_nombre' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'fti.tipo_producto_nombre' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- código del producto  --}}
                        {{-- 
                            NOTA: El código del producto, por la forma tan especial que tuvo para 
                            ser incluido en esta consulta, no podrá tener por ahora las funcionalidades
                            de ordenar ni filtrar. Habrá que mirar como se implementan luego. 
                        --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1 text-white font-bold bg-green-500  border-green-500 rounded">
                                    <span class="px-2">Código de producto</span>
                                </div>
                           </div>
                        </th>
                        {{-- categoria  --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('peddet.categoria')">
                                        <span class="px-2">Categoria</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'peddet.categoria' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'peddet.categoria' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- cantidad --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('peddet.canti')">
                                        <span class="px-2">Cantidad</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'peddet.canti' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'peddet.canti' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- precio --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('peddet.precio')">
                                        <span class="px-2">Precio</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'peddet.precio' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'peddet.precio' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>

                        {{-- observaciones --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('peddet.obs_producto')">
                                         <span class="px-2">Observaciones</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'peddet.obs_producto' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'peddet.obs_producto' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                         </th>                        
                        {{-- usuario creo --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('usu2.name')">
                                        <span class="px-2">Ingresado por</span>
                                     </button>
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
                        {{-- fecha creo --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.created_at')">
                                        <span class="px-2">Fecha creación</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'cli.created_at' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'cli.created_at' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- usuario modifico --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('usu3.name')">
                                        <span class="px-2">Modificado por</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'usu3.name' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'usu3.name' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- fecha modifico --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.updated_at')">
                                        <span class="px-2">Fecha modificación</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'cli.updated_at' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'cli.updated_at' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>

                    </tr>
                </thead>

                {{-- filtros  --}}
                <tr>
                    {{-- pedido_conse  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="pedido_conse">
                    </td>                     
                    {{-- estado  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="estado_id">
                    </td>
                    {{-- nombre cliente --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="cliente_nombre">
                    </td>                                          
                    {{-- dirección de entrega --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="dir_entrega">
                    </td> 
                    {{-- nombre tipo de producto  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="tipo_producto_nombre">
                    </td>                     
                    {{-- código del producto  --}}
                        {{-- 
                            NOTA: El código del producto, por la forma tan especial que tuvo para 
                            ser incluido en esta consulta, no podrá tener por ahora las funcionalidades
                            de ordenar ni filtrar. Habrá que mirar como se implementan luego. 
                        --}}                    
                    <td>
                        &nbsp;
                    </td>                      
                    {{-- categoria --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="categoria">
                    </td>                     
                    {{-- cantidad --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="canti">
                    </td>                     
                    {{-- precio  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="precio">
                    </td>                     
                    {{-- observaciones  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="obs_producto">
                    </td>                                           
                    {{-- usuario creo --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="usuario_creo">
                    </td>                     
                    {{-- fecha creación --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fecha_creo">
                    </td>                     
                    {{-- usuario modifico --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="usuario_modifico">
                    </td>                     
                    {{-- fecha modificación --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fecha_modifico">
                    </td>                     
                </tr>

                {{-- cuerpo de la tabla: registros  --}}
                @foreach ($registros as $registro)
{{-- {{dd($registro)}}                 --}}
                    <tr class="bg-white border-4 border-gray-200">
                        <td class="border border-gray-300 text-center">
                            {{$registro->pedido_conse}}
                        </td>   

                        {{-- estado: se muestra con iconos:                         --}}
                        <td class="border border-gray-300 text-center ">
                            {{-- Este div es el EXTERNO para mostrar el mensaje emergente:  --}}
                            <div class="group relative w-full text-center">
                                @if ($registro->estado_id == 1)
                                    @if (Auth::user()->roles[0]->name == 'admin')
                                        {{-- Muestra el botón para el estado pendiente:  --}}
                                        <a href="#" wire:click="aprobar_pedido('{{$registro->pedido_conse}}')" class="underline text-blue-500 pr-2 text-center">
                                            <svg class="inline-block w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 10.5V15.5C3 20.19 6.81 24 11.5 24S20 20.19 20 15.5V7C20 5.62 18.88 4.5 17.5 4.5C17.33 4.5 17.16 4.5 17 4.55V4C17 2.62 15.88 1.5 14.5 1.5C14.27 1.5 14.04 1.53 13.83 1.59C13.46 .66 12.56 0 11.5 0C10.27 0 9.25 .89 9.04 2.06C8.87 2 8.69 2 8.5 2C7.12 2 6 3.12 6 4.5V8.05C5.84 8 5.67 8 5.5 8C4.12 8 3 9.12 3 10.5M5 10.5C5 10.22 5.22 10 5.5 10S6 10.22 6 10.5V15C7.66 15 9 16.34 9 18H11C11 15.95 9.77 14.19 8 13.42V4.5C8 4.22 8.22 4 8.5 4S9 4.22 9 4.5V11H11V2.5C11 2.22 11.22 2 11.5 2S12 2.22 12 2.5V11H14V4C14 3.72 14.22 3.5 14.5 3.5S15 3.72 15 4V12H17V7C17 6.72 17.22 6.5 17.5 6.5S18 6.72 18 7V15.5C18 19.09 15.09 22 11.5 22S5 19.09 5 15.5V10.5Z" />
                                            </svg>
                                        </a>                                    
                                    @else
                                        {{-- Muestra el ícono para el estado pendiente:  --}}
                                        <svg class="inline-block w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 10.5V15.5C3 20.19 6.81 24 11.5 24S20 20.19 20 15.5V7C20 5.62 18.88 4.5 17.5 4.5C17.33 4.5 17.16 4.5 17 4.55V4C17 2.62 15.88 1.5 14.5 1.5C14.27 1.5 14.04 1.53 13.83 1.59C13.46 .66 12.56 0 11.5 0C10.27 0 9.25 .89 9.04 2.06C8.87 2 8.69 2 8.5 2C7.12 2 6 3.12 6 4.5V8.05C5.84 8 5.67 8 5.5 8C4.12 8 3 9.12 3 10.5M5 10.5C5 10.22 5.22 10 5.5 10S6 10.22 6 10.5V15C7.66 15 9 16.34 9 18H11C11 15.95 9.77 14.19 8 13.42V4.5C8 4.22 8.22 4 8.5 4S9 4.22 9 4.5V11H11V2.5C11 2.22 11.22 2 11.5 2S12 2.22 12 2.5V11H14V4C14 3.72 14.22 3.5 14.5 3.5S15 3.72 15 4V12H17V7C17 6.72 17.22 6.5 17.5 6.5S18 6.72 18 7V15.5C18 19.09 15.09 22 11.5 22S5 19.09 5 15.5V10.5Z" />
                                        </svg>                                    
                                    @endif
                                    {{-- Este es el div INTERNO para mostrar el mensaje emergente:  --}}
                                    <div class="opacity-0 w-full text-center bg-blue-400 text-white font-bold  text-xs absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                        Pendiente por aprobar.
                                        <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                    </div>
                                @else
                                    @if (Auth::user()->roles[0]->name == 'admin')
                                        {{-- Muestra el botón para el estado aprobado:  --}}
                                        <a href="#" wire:click="desaprobar_pedido('{{$registro->pedido_conse}}')" class="underline text-blue-500 pr-2 text-center">
                                            <svg class="inline-block w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                                            </svg>
                                        </a>                                    
                                    @else
                                        {{-- Muestra el ícono para el estado aprobado:  --}}
                                        <svg class="inline-block w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                                        </svg>           
                                        {{-- Este es el div INTERNO para mostrar el mensaje emergente:  --}}
                                        <div class="opacity-0 w-full text-center bg-blue-400 text-white font-bold  text-xs absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                            Aprobado.
                                            <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                        </div>                                         
                                    @endif
                               
                                @endif
                            </div>
                            {{-- Fin del div EXTERNO para mostrar el mensaje emergente:  --}}
                        </td> 
                        {{-- Fin de la columna ESTADOS  --}}
                        
                        <td class="border border-gray-300 text-center">
                            {{$registro->cliente_nombre}}
                        </td>
                        <td class="border border-gray-300 text-center">
                            {{$registro->dir_entrega}}
                        </td>
                        <td class="border border-gray-300 text-center">
                            {{$registro->tipo_producto_nombre}}
                        </td> 
                           
                        {{-- Código del producto: es una column action  --}}
                        <td class="border border-gray-300 px-4 text-blue-500">
                            {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                            <div class="group relative  text-center">
                                <a href="{{route('crear-formu' , [
                                        'tipo_producto_recibido_id' => $registro->tipo_producto_id , 
                                        'tipo_producto_recibido_nombre' => $registro->tipo_producto_nombre ,
                                        'tipo_producto_recibido_slug' => $registro->tipo_producto_slug ,
                                        'operacion' => 'ver',
                                        'tipo_producto_recibido_prefijo' => $registro->tipo_producto_prefijo ,
                                        'formu__id' => $registro->producto_id ,                              
                                        'formu__codigo_producto' => $registro->codigo_producto ,                              
                                        'formu__estado_nombre' => null,                             
                                    ])}}"  
                                    target="_blank" 
                                    onclick="alert('La info del producto aparecerá en una nueva pestaña del navegador.')" >{{$registro->codigo_producto}}
                                </a>
                                {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                <div class="opacity-0 text-center bg-blue-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                    Ver info del producto.
                                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                </div>                                     
                            </div>
                        </td>     

                        {{-- categoria: se muestra con iconos:                         --}}
                        <td class="border border-gray-300 text-center ">
                            {{-- Este div es el EXTERNO para mostrar el mensaje emergente:  --}}
                            <div class="group relative w-full text-center">
                                @if ($registro->categoria == 1)
                                    {{-- Muestra el ícono para la categoria nuevo:  --}}
                                    <svg class="inline-block w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                                    </svg>                                    
                                    {{-- Este es el div INTERNO para mostrar el mensaje emergente:  --}}
                                    <div class="opacity-0 w-full text-center bg-gray-500 text-white font-bold  text-xs absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                        Nuevo.
                                        <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                    </div>
                                @else
                                    {{-- Muestra el ícono para la categoria reprogramación:  --}}
                                    <svg class="inline-block w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M16 0H8C6.9 0 6 .9 6 2V18C6 19.1 6.9 20 8 20H20C21.1 20 22 19.1 22 18V6L16 0M20 18H8V2H15V7H20V18M4 4V22H20V24H4C2.9 24 2 23.1 2 22V4H4Z" />
                                    </svg>           
                                    {{-- Este es el div INTERNO para mostrar el mensaje emergente:  --}}
                                    <div class="opacity-0 w-full text-center bg-gray-500 text-white font-bold  text-xs absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                        Reprogramación.
                                        <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                    </div>                                
                                @endif
                            </div>
                            {{-- Fin del div EXTERNO para mostrar el mensaje emergente:  --}}
                        </td> 
                        {{-- Fin de la columna categoria  --}}                        

                        <td class="border border-gray-300 text-center">
                            {{$registro->canti}}
                        </td>                            
                        <td class="border border-gray-300 text-center">
                            {{$registro->precio}}
                        </td>                            

                        <td class="border border-gray-300 text-center">
                            {{$registro->obs_producto}}
                        </td>                                                        
                        <td class="border border-gray-300 text-center">
                            {{$registro->usuario_creo}}
                        </td>                            
                        <td class="border border-gray-300 text-center">
                            {{$registro->fecha_creo}}
                        </td>                             
                        <td class="border border-gray-300 text-center">
                            {{$registro->usuario_modifico}}
                        </td>                            
                        <td class="border border-gray-300 text-center">
                            {{$registro->fecha_modifico}}
                        </td>    
                        {{-- botón modificar pedido_conse o registrar producción  --}}
                        @if(Auth::user()->roles[0]->name == 'admin')
                            <td class="border border-gray-300 px-4 text-yellow-500">
                                @php
                                    // Al descomentariar, sobrará el @php....
                                    // <a href="{{route('crear-pedido' , [
                                    //     'operacion' => 'modificar',
                                    //     'modificar_pedido_conse' => $registro->pedido_conse,                              
                                    // ])}}"> 
                                    
                                @endphp
                                <a href=""
                                    onclick="alert('Opción en construcción.')" >
                                    <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                </a>
                            </td> 
                        @elseif(Auth::user()->roles[0]->name == 'comer')
                            {{-- Si el usuario es 'comer', depende del estado que se pueda modificar o no:  --}}
                            @if ($registro->estado_id == 1)
                                <td class="border border-gray-300 px-4 text-yellow-500">
                                    @php 
                                        // Al descomentariar, sobrará el @php....
                                        // <a href="{{route('crear-pedido' , [
                                        //     'operacion' => 'modificar',
                                        //     'modificar_pedido_conse' => $registro->pedido_conse,                              
                                        // ])}}"> 
                                    @endphp
                                    <a href=""
                                        onclick="alert('Opción en construcción.')" >
                                        <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                    </a>
                                </td>                                     
                            @endif
                        @elseif(Auth::user()->roles[0]->name == 'produ')
                            {{-- Debe mostrar el ícono 'Registrar producción'  --}}
                            <td class="border border-gray-300 px-4 text-blue-500">
                                {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                <div class="group relative  text-center">
                                    <button type="button" 
                                            wire:click="mostrar_modal_registrar_produccion('{{$registro->pedidos_detalle_id}}' , '{{$registro->pedido_conse}}' , '{{$registro->estado_id}}' , '{{$registro->estado_nombre}}' , '{{$registro->cliente_nombre}}' , '{{htmlentities($registro->dir_entrega)}}' , '{{$registro->tipo_producto_nombre}}' , '{{$registro->codigo_producto}}' , '{{$registro->canti}}')">
                                        <span>
                                            <svg class="w-6 h-6" fill="currentColor"  xmlns="http://www.w3.org/2000/svg"><path d="M9 3C5.69 3 3.14 5.69 3 9V21H12V13.46C13.1 14.45 14.5 15 16 15C19.31 15 22 12.31 22 9C22 5.69 19.31 3 16 3H9M9 5H11.54C10.55 6.1 10 7.5 10 9V12H9V13H10V19H5V13H6V12H5V9C5 6.79 6.79 5 9 5M16 5C18.21 5 20 6.79 20 9C20 11.21 18.21 13 16 13C13.79 13 12 11.21 12 9C12 6.79 13.79 5 16 5M16 7.25C15.03 7.25 14.25 8.03 14.25 9C14.25 9.97 15.03 10.75 16 10.75C16.97 10.75 17.75 9.97 17.75 9C17.75 8.03 16.97 7.25 16 7.25M7 12V13H8V12H7Z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    </button>
                                    {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                    <div class="opacity-0 text-center bg-blue-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                        Registrar producción.
                                        <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                    </div>  
                                </div>                                
                            </td>
                        @endif
                    </tr>
                @endforeach                      
            </table>
        </div> 

   </div>
   {{-- fin del marco principal --}}

    {{-- Modal para registrar la producción --}}
    <x-jet-dialog-modal wire:model="registrar_produccion_modal_visible">
        <x-slot name="title">
            <span class="text-gray-500">REGISTRO DE PRODUCCIÓN PARA EL PEDIDO {{$this->modal_produccion_pedido_conse}}</span>
            <br><br>
            <table>
                <tr>
                    <td>Estado: </td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_produccion_estado_nombre}}</td>
                </tr>
                <tr>
                    <td>Cliente</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_produccion_cliente_nombre}}</td>
                </tr>
                <tr>
                    <td>Dirección de entrega</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_produccion_dir_entrega}}</td>
                </tr>
                <tr>
                    <td>Tipo de producto</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_produccion_tipo_producto_nombre}}</td>
                </tr>
                <tr>
                    <td>Código del producto</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_produccion_codigo_producto}}</td>
                </tr>
                <tr>
                    <td>Cantidad pedida</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_produccion_canti}}</td>
                </tr>
            </table>
        </x-slot>
    
        <x-slot name="content"> 
            <form wire:submit.prevent="submit_grabar_produccion()">   
                {{-- Pedir fecha y cantidad:  --}}
                <div class="flex">
                    {{-- Fecha de producción: label e input date: --}}
                    <div class="mr-2">
                        <label class="w-full font-bold text-base my-auto  mb-2 ml-1">
                            Fecha de producción:
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input 
                                x-data
                                x-ref="fec_modal_produccion_input_fecha"
                                x-init="new Pikaday({
                                    field: $refs.fec_modal_produccion_input_fecha, 
                                    i18n: {
                                        previousMonth : 'Anterior',
                                        nextMonth     : 'Siguiente',
                                        months        : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                                        weekdays      : ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
                                        weekdaysShort : ['Dom','Lun','Mar','Mie','Jue','Vie','Sáb']
                                    },
                                    format: 'YYYY-MM-DD',
                                    onSelect: function(date) {

                                    }
                                })"
                                type="text"
                                wire:model.lazy = "modal_produccion_input_fecha"
                                id="idmodalfecha" 
                                class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                            >
                            {{-- ícono fecha:  --}}
                            <span class="absolute right-0 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md">
                                <svg class="inline-block align-text-top w-6 h-6">
                                    <path fill="currentColor" d="M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M9,14V16H7V14H9M13,14V16H11V14H13M17,14V16H15V14H17Z" />
                                </svg>
                            </span>  
                            @error('modal_produccion_input_fecha') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>                
                    </div>                

                    {{-- Cantidad --}}
                    <div class="mr-2">
                        <label class="w-full font-bold text-base my-auto  mb-2 ml-1">
                            Cantidad producida:
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input type="text" wire:model="modal_produccion_input_canti" id="idmodalcanti" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>                            
                        @error('modal_produccion_input_canti') <span class="text-red-500"><br>{{ $message }}</span> @enderror   
                    </div>                

                </div>

                {{-- Buttons --}}
                <div class="flex mx-auto">
                    <div class="mx-auto w-1/8 mt-6">
                        <button type="submit" class=" w-full bg-blue-600 hover:bg-blue-400 focus:bg-blue-400  text-white rounded-lg px-3 py-3 font-semibold"> Grabar producción</button>
                    </div>
                    <div class="mx-auto w-1/4 mt-6">
                        {{-- <button type="button" class="w-full bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold" wire:click="$set('registrar_produccion_modal_visible', false)"> Cancelar</button> --}}
                        <button type="button" wire:click="cerrar_modal_registrar_produccion()" class="w-full bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold" > Cancelar</button>
                    </div>
                </div>                 
            </form>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>


</div>
