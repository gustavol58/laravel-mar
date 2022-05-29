<div>
{{-- {{dd($registros)}}     --}}
{{-- @if ($errors->any())
    {{dd($errors)}}
@endif --}}
    @livewire('menu-own')
    <div class="bg-white border rounded border-gray-300 m-2 p-2 text-gray-600">
        {{-- 
            =====================================================================================
                Título y botones
            ===================================================================================== 
        --}}        
        <div class="flex mb-2">
            <div class="w-4/5">
                <h1 class="font-black text-3xl ">Pedidos</h1>
            </div>
            {{-- 
                =====================================================================================
                    Botones
                ===================================================================================== 
            --}}             
            <div class="flex w-1/5 items-end justify-end">
                {{-- botón NUEVO  --}}
                @if (Auth::user()->roles[0]->name == 'admin'
                        || Auth::user()->roles[0]->name == 'comer')                 
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
                @endif

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

        {{-- 
            =====================================================================================
                Registros
            ===================================================================================== 
        --}}        
        @if ($registros->hasPages())
            {{ $registros->links() }}   
        @else 
            Mostrando {{count($registros)}} registros
        @endif  
        <br>  

        {{-- 
            24oct2021 Si la longitud del nombre del tipo de producto cabe en una 
            sola linea (65 caracteres o menos), el tamaño vh para poder ver 
            el scroll horizontal será de 58vh. Si es mayor el tamaño debe
            reducirse a 52vh, y asi sucesivamente: 
        --}}
        <div class="overflow-scroll " style="height: 58vh;">
            <table class="table-fixed ">
                {{-- 
                    =====================================================================================
                        Títulos de columna y botón ordenar en cada una
                    ===================================================================================== 
                --}}             
                <thead class="justify-between">
                    <tr class="bg-green-500">
                        {{-- botón --}}
                        <th class="bg-white">
                            &nbsp;
                        </th>                        
                        {{--pedido_conse  --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('concat(lpad(peddet.pedido_encab_id , 5 , \'0\') ,\'_\',peddet.conse)')">
                                        <span class="px-2">Id.</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == "concat(lpad(peddet.pedido_encab_id , 5 , '0') ,'_',peddet.conse)" && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == "concat(lpad(peddet.pedido_encab_id , 5 , '0') ,'_',peddet.conse)" && $ordenar_tipo == ' desc') 
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
                        {{-- cantidad pedida --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('peddet.canti')">
                                        <span class="px-2">Canti PEDIDA</span>
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
                        {{-- cantidad producida --}}
                        {{-- 
                            NOTA: La cantidad producida, por ser una subconsulta, no podrá tener por ahora 
                            las funcionalidades de ordenar ni filtrar. Habrá que mirar como se implementan luego. 
                        --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1 text-white font-bold bg-green-500  border-green-500 rounded">
                                     <span class="px-2">Canti PRODUCIDA</span>
                                 </div>
                            </div>
                         </th>                                                
                        {{-- cantidad facturada --}}
                        {{-- 
                            NOTA: La cantidad facturada, por ser una subconsulta, no podrá tener por ahora 
                            las funcionalidades de ordenar ni filtrar. Habrá que mirar como se implementan luego. 
                        --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1 text-white font-bold bg-green-500  border-green-500 rounded">
                                     <span class="px-2">Canti FACTURADA</span>
                                 </div>
                            </div>
                         </th>                                                
                        {{-- precio --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                @if (Auth::user()->roles[0]->name == 'admin'
                                        || Auth::user()->roles[0]->name == 'comer')

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
                                @else 
                                    <div class="flex-1 text-white font-bold bg-green-500  border-green-500 rounded">
                                        <span class="px-2">Precio</span>
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
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('pedenc.creado_el')">
                                        <span class="px-2">Fecha creación</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'pedenc.creado_el' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'pedenc.creado_el' && $ordenar_tipo == ' desc') 
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
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('pedenc.modificado_el')">
                                        <span class="px-2">Fecha modificación</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'pedenc.modificado_el' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'pedenc.modificado_el' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>

                    </tr>
                </thead>

                {{-- 
                    =====================================================================================
                        Filtros
                    ===================================================================================== 
                --}}                
                <tr>
                    {{-- para el botón   --}}
                    <td>
                        &nbsp;
                    </td>                     
                    {{-- pedido_conse  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="pedido_conse">
                    </td>                     
                    {{-- estado  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="estado_nombre">
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
                    {{-- cantidad pedida --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="canti">
                    </td>               
                    {{-- cantidad producida  --}}
                    {{-- 
                        NOTA: La cantidad producida, por ser el resultado de una subconsulta,
                        no podrá tener por ahora las funcionalidades
                        de ordenar ni filtrar. Habrá que mirar como se implementan luego. 
                    --}}   
                    <td>
                        &nbsp;
                    </td>                  
                    {{-- cantidad facturada  --}}
                    {{-- 
                        NOTA: La cantidad facturada, por ser el resultado de una subconsulta,
                        no podrá tener por ahora las funcionalidades
                        de ordenar ni filtrar. Habrá que mirar como se implementan luego. 
                    --}}   
                    <td>
                        &nbsp;
                    </td>                  
                    {{-- precio  --}}
                    <td>
                        @if (Auth::user()->roles[0]->name == 'admin'
                                || Auth::user()->roles[0]->name == 'comer')
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="precio">
                        @endif
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

                {{-- 
                    =====================================================================================
                        Cuerpo de la tabla: registros
                    ===================================================================================== 
                --}}                
                @foreach ($registros as $registro)
                    <tr class="bg-white border-4 border-gray-200">
                        {{-- Column action (primer columna) que puede mostrar diversidad de acciones a 
                            ejecutar, todo depende del rol de usuario que esté logueado  --}}
                        @if(Auth::user()->roles[0]->name == 'admin')
                            <td class="flex border border-gray-300 px-4 text-yellow-500">

                                {{-- Primer botón: Modificar: Para aquellos estados
                                     diferentes de Anulado y de Cierre forzado  --}}
                                @if ($registro->estado_id == 8 || $registro->estado_id == 9)
                                    {{-- el estado del pedido_conse es anulado o cierre forzado,
                                         en estos casos el pedido_conse no debe tener ningún botón  --}}
                                    &nbsp;
                                @else
                                    {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                    <div class="group relative  text-center">
                                        <a href="{{route('crear-pedido' , [
                                                'operacion' => 'modificar',
                                                'modificar_pedido_encab_id' => $registro->pedido_encab_id,
                                            ])}}">
                                            <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                        </a>
                                        {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="opacity-0 text-center bg-yellow-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                            Modificar pedido.
                                        </div>                                     
                                    </div>
                                @endif     

                                &nbsp;&nbsp;&nbsp;                                

                                {{-- Segundo botón: Registrar producción:  --}}
                                @if ($registro->estado_id == 2 
                                    || $registro->estado_id == 3 
                                    )
                                        {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="group relative  text-center">
                                            <button type="button" 
                                                    wire:click="mostrar_modal_registrar_produccion('{{$registro->pedidos_detalle_id}}' , '{{$registro->pedido_conse}}' , '{{$registro->estado_id}}' , '{{$registro->estado_nombre}}' , '{{$registro->cliente_nombre}}' , '{{htmlentities($registro->dir_entrega)}}' , '{{$registro->tipo_producto_nombre}}' , '{{$registro->codigo_producto}}' , '{{$registro->canti}}' , '{{$registro->produ_canti}}' , '{{$registro->factu_canti}}')">
                                                <span>
                                                    <svg class="text-green-500 w-6 h-6" fill="currentColor"  xmlns="http://www.w3.org/2000/svg"><path d="M9 3C5.69 3 3.14 5.69 3 9V21H12V13.46C13.1 14.45 14.5 15 16 15C19.31 15 22 12.31 22 9C22 5.69 19.31 3 16 3H9M9 5H11.54C10.55 6.1 10 7.5 10 9V12H9V13H10V19H5V13H6V12H5V9C5 6.79 6.79 5 9 5M16 5C18.21 5 20 6.79 20 9C20 11.21 18.21 13 16 13C13.79 13 12 11.21 12 9C12 6.79 13.79 5 16 5M16 7.25C15.03 7.25 14.25 8.03 14.25 9C14.25 9.97 15.03 10.75 16 10.75C16.97 10.75 17.75 9.97 17.75 9C17.75 8.03 16.97 7.25 16 7.25M7 12V13H8V12H7Z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                            {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                            <div class="opacity-0 text-center bg-green-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                                Registrar producción.
                                            </div>  
                                        </div>                                
                                @endif 
                                
                                &nbsp;&nbsp;&nbsp;

                                {{-- Tercer botón: Registrar facturación:  --}}
                                {{-- 
                                    3,4,7,8
                                    Si el estado es 3,4 debe mostrar el 
                                    ícono 'Registrar facturación' siempre y
                                    cuando facturacion < produccion.
                                    Si el estado es 7 u 8 no lo debe mostrar
                                --}}
                                @if (($registro->estado_id == 3 
                                        || $registro->estado_id == 4) 
                                        && ($registro->factu_canti < $registro->produ_canti) 
                                        )
                                        {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="group relative  text-center">
                                            <button type="button" 
                                                    wire:click="mostrar_modal_registrar_facturacion('{{$registro->pedidos_detalle_id}}' , '{{$registro->pedido_conse}}' , '{{$registro->estado_id}}' , '{{$registro->estado_nombre}}' , '{{$registro->cliente_nombre}}' , '{{htmlentities($registro->dir_entrega)}}' , '{{$registro->tipo_producto_nombre}}' , '{{$registro->codigo_producto}}' , '{{$registro->canti}}' , '{{$registro->produ_canti}}' , '{{$registro->factu_canti}}')">
                                                <span>
                                                    <svg class="text-pink-500 w-6 h-6" fill="currentColor"  xmlns="http://www.w3.org/2000/svg"><path d="M5 19V5H12V12H19V13C19.7 13 20.37 13.13 21 13.35V9L15 3H5C3.89 3 3 3.89 3 5V19C3 20.1 3.89 21 5 21H13.35C13.13 20.37 13 19.7 13 19H5M14 4.5L19.5 10H14V4.5M22.5 17.25L17.75 22L15 19L16.16 17.84L17.75 19.43L21.34 15.84L22.5 17.25Z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                            {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                            <div class="opacity-0 text-center bg-pink-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                                Registrar facturación.
                                            </div>  
                                        </div>                                
                                @endif

                                &nbsp;&nbsp;&nbsp;

                                {{-- Cuarto botón: Cierre forzado:  --}}
                                @if ($registro->estado_id == 1 
                                    || $registro->estado_id == 2 
                                    || $registro->estado_id == 3 
                                    || $registro->estado_id == 4 
                                    )
                                        {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="group relative  text-center">
                                            <button type="button" 
                                                    wire:click="modificar_estado_admin_cierre_forzado('{{$registro->pedido_conse}}' , '{{$registro->estado_id}}')">
                                                <span>
                                                    <svg class="text-yellow-900 w-6 h-6" fill="currentColor"  xmlns="http://www.w3.org/2000/svg"><path d="M21 7C21 5.62 19.88 4.5 18.5 4.5C18.33 4.5 18.16 4.5 18 4.55V4C18 2.62 16.88 1.5 15.5 1.5C15.27 1.5 15.04 1.53 14.83 1.59C14.46 .66 13.56 0 12.5 0C11.27 0 10.25 .89 10.04 2.06C9.87 2 9.69 2 9.5 2C8.12 2 7 3.12 7 4.5V10.39C6.66 10.08 6.24 9.85 5.78 9.73L5 9.5C4.18 9.29 3.31 9.61 2.82 10.35C2.44 10.92 2.42 11.66 2.67 12.3L5.23 18.73C6.5 21.91 9.57 24 13 24C17.42 24 21 20.42 21 16V7M19 16C19 19.31 16.31 22 13 22C10.39 22 8.05 20.41 7.09 18L4.5 11.45L5 11.59C5.5 11.71 5.85 12.05 6 12.5L7 15H9V4.5C9 4.22 9.22 4 9.5 4S10 4.22 10 4.5V12H12V2.5C12 2.22 12.22 2 12.5 2S13 2.22 13 2.5V12H15V4C15 3.72 15.22 3.5 15.5 3.5S16 3.72 16 4V12H18V7C18 6.72 18.22 6.5 18.5 6.5S19 6.72 19 7V16Z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                            {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                            <div class="opacity-0 text-center bg-yellow-900 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                                Cierre forzado.
                                            </div>  
                                        </div>                                     
                                @endif 

                                &nbsp;&nbsp;&nbsp;

                                {{-- Quinto botón: Anular:  --}}
                                @if ($registro->estado_id == 1 
                                        || $registro->estado_id == 2)
                                    {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                    <div class="group relative  text-center">
                                        <button type="button" 
                                            wire:click="mostrar_modal_anular('{{$registro->pedidos_detalle_id}}' , '{{$registro->pedido_conse}}' , '{{$registro->estado_id}}' , '{{$registro->estado_nombre}}' , '{{$registro->cliente_nombre}}' , '{{htmlentities($registro->dir_entrega)}}' , '{{$registro->tipo_producto_nombre}}' , '{{$registro->codigo_producto}}' , '{{$registro->canti}}')">
                                            <span>
                                                <svg class="text-red-500 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        </button>
                                        {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="opacity-0 text-center bg-red-500 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                            Anular pedido.
                                        </div>  
                                    </div>
                                @endif                                 

                            </td> 
                        @elseif(Auth::user()->roles[0]->name == 'comer')
                            {{-- Si el usuario es 'comer', depende del estado que se pueda modificar o no:  --}}
                            @if ($registro->estado_id == 1)
                                <td class="flex border border-gray-300 px-4 text-yellow-500">

                                    {{-- Primer botón: Modificar:  --}}
                                    {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                    <div class="group relative  text-center">
                                        <a href="{{route('crear-pedido' , [
                                                'operacion' => 'modificar',
                                                'modificar_pedido_encab_id' => $registro->pedido_encab_id,
                                            ])}}">
                                            <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                        </a>
                                        {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="opacity-0 text-center bg-yellow-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                            Modificar pedido.
                                        </div>                                     
                                    </div>

                                    &nbsp;&nbsp;&nbsp;

                                    {{-- Segundo botón: Anular:  --}}
                                    @if ($registro->estado_id == 1 
                                            || $registro->estado_id == 2)
                                        {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="group relative  text-center">
                                            <button type="button" 
                                                    wire:click="mostrar_modal_anular('{{$registro->pedidos_detalle_id}}' , '{{$registro->pedido_conse}}' , '{{$registro->estado_id}}' , '{{$registro->estado_nombre}}' , '{{$registro->cliente_nombre}}' , '{{htmlentities($registro->dir_entrega)}}' , '{{$registro->tipo_producto_nombre}}' , '{{$registro->codigo_producto}}' , '{{$registro->canti}}' , '{{$registro->produ_canti}}' , '{{$registro->factu_canti}}')">
                                                <span>
                                                    <svg class="text-red-500 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                            {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                            <div class="opacity-0 text-center bg-red-500 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                                Anular pedido.
                                            </div>  
                                        </div>
                                    @endif                                     
                                </td>  
                            @else 
                                <td class="border border-gray-300 px-4 text-blue-500">
                                    &nbsp;
                                </td>                                                                    
                            @endif
                        @elseif(Auth::user()->roles[0]->name == 'produ')
                            {{-- 
                                Si el estado es 2,3 o 5 debe mostrar el 
                                ícono 'Registrar producción'
                                Si el estado es 4,6,7 u 8 no lo debe mostrar
                            --}}
                            @if ($registro->estado_id == 2 
                                    || $registro->estado_id == 3 
                                    )
                                <td class="border border-gray-300 px-4 text-green-500">
                                    {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                    <div class="group relative  text-center">
                                        <button type="button" 
                                                wire:click="mostrar_modal_registrar_produccion('{{$registro->pedidos_detalle_id}}' , '{{$registro->pedido_conse}}' , '{{$registro->estado_id}}' , '{{$registro->estado_nombre}}' , '{{$registro->cliente_nombre}}' , '{{htmlentities($registro->dir_entrega)}}' , '{{$registro->tipo_producto_nombre}}' , '{{$registro->codigo_producto}}' , '{{$registro->canti}}' , '{{$registro->produ_canti}}' , '{{$registro->factu_canti}}')">
                                            <span>
                                                <svg class="w-6 h-6" fill="currentColor"  xmlns="http://www.w3.org/2000/svg"><path d="M9 3C5.69 3 3.14 5.69 3 9V21H12V13.46C13.1 14.45 14.5 15 16 15C19.31 15 22 12.31 22 9C22 5.69 19.31 3 16 3H9M9 5H11.54C10.55 6.1 10 7.5 10 9V12H9V13H10V19H5V13H6V12H5V9C5 6.79 6.79 5 9 5M16 5C18.21 5 20 6.79 20 9C20 11.21 18.21 13 16 13C13.79 13 12 11.21 12 9C12 6.79 13.79 5 16 5M16 7.25C15.03 7.25 14.25 8.03 14.25 9C14.25 9.97 15.03 10.75 16 10.75C16.97 10.75 17.75 9.97 17.75 9C17.75 8.03 16.97 7.25 16 7.25M7 12V13H8V12H7Z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        </button>
                                        {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="opacity-0 text-center bg-green-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                            Registrar producción.
                                            <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                        </div>  
                                    </div>                                
                                </td>
                            @else 
                                <td class="border border-gray-300 px-4 text-blue-500">
                                    &nbsp;
                                </td>                            
                            @endif
                        @elseif(Auth::user()->roles[0]->name == 'disen')
                            <td class="border border-gray-300 px-4 text-blue-500">
                                &nbsp;
                            </td> 
                        @elseif(Auth::user()->roles[0]->name == 'contab')
                            {{-- 
                                3,4,7,8
                                Si el estado es 3,4 debe mostrar el 
                                ícono 'Registrar facturación' siempre y
                                cuando facturacion < produccion.
                                Si el estado es 7 u 8 no lo debe mostrar
                            --}}
                                @if (($registro->estado_id == 3 
                                        || $registro->estado_id == 4) 
                                        && ($registro->factu_canti < $registro->produ_canti) 
                                        )
                                <td class="border border-gray-300 px-4 text-pink-500">
                                    {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                    <div class="group relative  text-center">
                                        <button type="button" 
                                                wire:click="mostrar_modal_registrar_facturacion('{{$registro->pedidos_detalle_id}}' , '{{$registro->pedido_conse}}' , '{{$registro->estado_id}}' , '{{$registro->estado_nombre}}' , '{{$registro->cliente_nombre}}' , '{{htmlentities($registro->dir_entrega)}}' , '{{$registro->tipo_producto_nombre}}' , '{{$registro->codigo_producto}}' , '{{$registro->canti}}' , '{{$registro->produ_canti}}' , '{{$registro->factu_canti}}')">
                                            <span>
                                                <svg class="w-6 h-6" fill="currentColor"  xmlns="http://www.w3.org/2000/svg"><path d="M5 19V5H12V12H19V13C19.7 13 20.37 13.13 21 13.35V9L15 3H5C3.89 3 3 3.89 3 5V19C3 20.1 3.89 21 5 21H13.35C13.13 20.37 13 19.7 13 19H5M14 4.5L19.5 10H14V4.5M22.5 17.25L17.75 22L15 19L16.16 17.84L17.75 19.43L21.34 15.84L22.5 17.25Z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        </button>
                                        {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="opacity-0 text-center bg-pink-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                            Registrar facturación.
                                            <svg class="absolute text-pink-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                        </div>  
                                    </div>                                
                                </td>
                            @else 
                                <td class="border border-gray-300 px-4 text-blue-500">
                                    &nbsp;
                                </td>                            
                            @endif
                        @endif

                        <td class="border border-gray-300 text-center">
                            {{$registro->pedido_conse}}
                        </td>   

                        {{-- estado del pedido               --}}
                        {{-- 15feb2022: admin podra dar click si el pedido tiene uno de estos estados:
                            Pendiente por aprobar
                            Aprobado
                            Cierre forzado
                        --}}
                        <td class="border border-gray-300 text-center ">
                            @if (Auth::user()->roles[0]->name == 'admin'
                                    && ($registro->estado_id == 1 
                                        || $registro->estado_id == 2))                            
                                <a href="#" wire:click="modificar_estado_admin_aprobar_desaprobar('{{$registro->pedido_conse}}' , '{{$registro->estado_id}}')" class="text-blue-600 hover:text-blue-800 visited:text-purple-600">
                                    {{$registro->estado_nombre}}
                                </a>
                            @elseif(Auth::user()->roles[0]->name == 'admin'
                                            && $registro->estado_id == 9)
                                <a href="#" wire:click="modificar_estado_admin_quitar_forzado('{{$registro->pedido_conse}}' , '{{$registro->estado_anterior_id}}')" class="text-blue-600 hover:text-blue-800 visited:text-purple-600">
                                    {{$registro->estado_nombre}}
                                </a>
                            @else 
                                {{$registro->estado_nombre}}
                            @endif
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
                            {{number_format($registro->canti, 0, '.', ',')}}
                        </td>  

                        {{-- 
                            Cantidades producidas, columna de dos partes (cantidad e ícono), el ícono se
                            mostrará siempre y cuando:
                                     el pedido tenga producción registrada
                                     ó el estado del pedido_conse no sea ni 'Cierre forzado' ni 'Anulado'
                                     el usuario sea un administrador
                            a) La 'cantidad' es una column action para ver la producción registrada.
                            b) el ícono permitirá modificar la producción registrada. 
                        --}}
                        @if ($registro->produ_canti == 0)
                            <td class="border border-gray-300 px-4 text-center">
                                0
                            </td>
                        @elseif ($registro->estado_id == 8 || $registro->estado_id == 9)
                            <td class="border border-gray-300 px-4 text-center">
                                {{number_format($registro->produ_canti, 0, '.', ',')}}
                            </td>
                        @else
                            <td class="border border-gray-300 px-4 text-blue-500">
                                <div class="flex">
                                    {{-- a) Una column action para ver la producción registrada.:  --}}
                                    {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                    <div class="group relative  text-center">
                                        <button type="button" wire:click="mostrar_modal_ver_inventario('{{$registro->pedido_conse}}' ,  '{{$registro->estado_nombre}}' , '{{$registro->canti}}' , '{{$registro->pedidos_detalle_id}}' , '{{$registro->produ_canti}}' , '{{$registro->factu_canti}}')">
                                            {{number_format($registro->produ_canti, 0, '.', ',')}}
                                        </button>
                                        {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="opacity-0 text-center bg-blue-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                            Ver producción y facturación.
                                            <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                        </div> 
                                    </div>

                                    {{--b) Un ícono que permite modificar la producción/facturación.  --}}
                                    @if (Auth::user()->roles[0]->name == 'admin')
                                        {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="group relative  text-center flex">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <button class="text-yellow-500" type="button" wire:click="mostrar_modal_modificar_produ_factu('{{$registro->pedidos_detalle_id}}' , '{{$registro->pedido_conse}}' , '{{$registro->estado_id}}' , '{{$registro->estado_nombre}}' , '{{$registro->canti}}')">
                                                <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                            </button>                                        
                                            {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                            <div class="opacity-0 text-center bg-yellow-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                                Modificar producción y facturación.
                                                <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                            </div>     
                                        </div>
                                    @endif
                                </div>
                            </td>
                        @endif

                        {{-- 
                            Cantidades facturadas, columna de dos partes (cantidad e ícono), el ícono se
                            mostrará siempre y cuando:
                                     el pedido tenga facturación registrada
                                     ó el estado del pedido_conse no sea ni 'Cierre forzado' ni 'Anulado'
                                     el usuario logueado tenga el rol 'admin'
                            a) La 'cantidad' es una column action para ver la facturación registrada.
                            b) el ícono permitirá modificar la facturación registrada. 
                        --}}                            
                        @if ($registro->produ_canti == 0)
                            <td class="border border-gray-300 px-4 text-center">
                                0
                            </td>
                        @elseif ($registro->estado_id == 8 || $registro->estado_id == 9)
                            <td class="border border-gray-300 px-4 text-center">
                                {{number_format($registro->factu_canti, 0, '.', ',')}}
                            </td>
                        @else
                            <td class="border border-gray-300 px-4 text-blue-500">
                                <div class="flex">
                                    {{-- a) Una column action para ver la facturación registrada.:  --}}
                                    {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                    <div class="group relative  text-center">
                                        <button type="button" wire:click="mostrar_modal_ver_inventario('{{$registro->pedido_conse}}' ,  '{{$registro->estado_nombre}}' , '{{$registro->canti}}' , '{{$registro->pedidos_detalle_id}}' , '{{$registro->produ_canti}}' , '{{$registro->factu_canti}}')">
                                            {{number_format($registro->factu_canti, 0, '.', ',')}}
                                        </button>
                                        {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="opacity-0 text-center bg-blue-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                            Ver producción y facturación.
                                            <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                        </div>                                     
                                    </div>    
                                    
                                    {{--b) Un ícono que permite modificar la producción/facturación.  --}}
                                    @if (Auth::user()->roles[0]->name == 'admin')
                                        {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                        <div class="group relative  text-center flex">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <button class="text-yellow-500" type="button" wire:click="mostrar_modal_modificar_produ_factu('{{$registro->pedidos_detalle_id}}' , '{{$registro->pedido_conse}}' , '{{$registro->estado_id}}' , '{{$registro->estado_nombre}}' , '{{$registro->canti}}')">
                                                <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                            </button>   
                                            {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                            <div class="opacity-0 text-center bg-yellow-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                                Modificar producción y facturación.
                                                <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                            </div>     
                                        </div>                                    
                                    @endif
                                </div>
                            </td>
                        @endif

                        <td class="border border-gray-300 text-center">
                            @if (Auth::user()->roles[0]->name == 'admin'
                                    || Auth::user()->roles[0]->name == 'comer'
                                    || Auth::user()->roles[0]->name == 'contab')
                                {{number_format($registro->precio, 2, '.', ',')}}
                            @else
                                N.D.                                
                            @endif
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
                    </tr>
                @endforeach                      
            </table>
        </div> 
    </div>
    {{-- fin del marco principal --}}

    {{-- 
        =====================================================================================
            Modal para registrar la producción
        ===================================================================================== 
    --}}   
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
                    <td>{{$this->modal_produccion_pedida_canti}}</td>
                </tr>
                <tr>
                    <td>Cantidad producida a hoy</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_produccion_produ_canti}}</td>
                </tr>
                <tr>
                    <td>Cantidad facturada a hoy</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_produccion_factu_canti}}</td>
                </tr>                
            </table>
        </x-slot>
    
        <x-slot name="content"> 
            <form wire:submit.prevent="submit_grabar_produccion()" id="formproduccion">   
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

    {{-- 
        =====================================================================================
            Modal para registrar la facturación
        ===================================================================================== 
    --}}     
    <x-jet-dialog-modal wire:model="registrar_facturacion_modal_visible">
        <x-slot name="title">
            <span class="text-gray-500">REGISTRO DE FACTURACIÓN PARA EL PEDIDO {{$this->modal_facturacion_pedido_conse}}</span>
            <br><br>
            <table>
                <tr>
                    <td>Estado: </td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_facturacion_estado_nombre}}</td>
                </tr>
                <tr>
                    <td>Cliente</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_facturacion_cliente_nombre}}</td>
                </tr>
                <tr>
                    <td>Dirección de entrega</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_facturacion_dir_entrega}}</td>
                </tr>
                <tr>
                    <td>Tipo de producto</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_facturacion_tipo_producto_nombre}}</td>
                </tr>
                <tr>
                    <td>Código del producto</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_facturacion_codigo_producto}}</td>
                </tr>
                <tr>
                    <td>Cantidad pedida</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_facturacion_pedida_canti}}</td>
                </tr>
                <tr>
                    <td>Cantidad producida a hoy</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_facturacion_produ_canti}}</td>
                </tr>
                <tr>
                    <td>Cantidad facturada a hoy</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_facturacion_factu_canti}}</td>
                </tr>
            </table>
        </x-slot>
    
        <x-slot name="content"> 
            <form wire:submit.prevent="submit_grabar_facturacion()" id="formfacturacion">   
                {{-- Pedir fecha y cantidad:  --}}
                <div class="flex">
                    {{-- Fecha de facturación: label e input date: --}}
                    <div class="mr-2">
                        <label class="w-full font-bold text-base my-auto  mb-2 ml-1">
                            Fecha de facturación:
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input 
                                x-data
                                x-ref="fec_modal_facturacion_input_fecha"
                                x-init="new Pikaday({
                                    field: $refs.fec_modal_facturacion_input_fecha, 
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
                                wire:model.lazy = "modal_facturacion_input_fecha"
                                id="idmodalfacturacionfecha" 
                                class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                            >
                            {{-- ícono fecha:  --}}
                            <span class="absolute right-0 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md">
                                <svg class="inline-block align-text-top w-6 h-6">
                                    <path fill="currentColor" d="M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M9,14V16H7V14H9M13,14V16H11V14H13M17,14V16H15V14H17Z" />
                                </svg>
                            </span>  
                            @error('modal_facturacion_input_fecha') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>                
                    </div>                

                    {{-- Número de factura  --}}
                    <div class="mr-2">
                        <label class="w-full font-bold text-base my-auto  mb-2 ml-1">
                            Número de factura:
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input type="text" wire:model="modal_facturacion_input_numfactu" id="idmodalfacturacionnumfactu" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>                            
                        @error('modal_facturacion_input_numfactu') <span class="text-red-500"><br>{{ $message }}</span> @enderror   
                    </div>
                    {{-- Cantidad --}}
                    <div class="mr-2">
                        <label class="w-full font-bold text-base my-auto  mb-2 ml-1">
                            Cantidad facturada:
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input type="text" wire:model="modal_facturacion_input_canti" id="idmodalfacturacioncanti" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>                            
                        @error('modal_facturacion_input_canti') <span class="text-red-500"><br>{{ $message }}</span> @enderror   
                    </div>                

                </div>

                {{-- Buttons --}}
                <div class="flex mx-auto">
                    <div class="mx-auto w-1/8 mt-6">
                        <button type="submit" class=" w-full bg-blue-600 hover:bg-blue-400 focus:bg-blue-400  text-white rounded-lg px-3 py-3 font-semibold"> Grabar facturación</button>
                    </div>
                    <div class="mx-auto w-1/4 mt-6">
                        {{-- <button type="button" class="w-full bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold" wire:click="$set('registrar_produccion_modal_visible', false)"> Cancelar</button> --}}
                        <button type="button" wire:click="cerrar_modal_registrar_facturacion()" class="w-full bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold" > Cancelar</button>
                    </div>
                </div>                 
            </form>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>

    {{-- 
        =====================================================================================
            Modal para ver el historial de producción y facturación
        ===================================================================================== 
    --}} 
        <x-jet-dialog-modal wire:model="ver_inventario_modal_visible" maxWidth="4xl">
            <x-slot name="title">
                {{-- <span class="text-gray-500">HISTORIAL DE PRODUCCIÓN Y FACTURACIÓN PARA EL PEDIDO <span class="text-blue-500">{{$this->modal_inventario_pedido_conse}}</span></span> --}}
                HISTORIAL DE PRODUCCIÓN Y FACTURACIÓN<br>PARA EL PEDIDO: <span class="text-blue-500">{{$this->modal_inventario_pedido_conse}}</span>
                <br>
                Estado: <span class="text-blue-500">{{$this->modal_inventario_estado_nombre}}</span>
                <br>
                Cantidad pedida: <span class="text-blue-500">{{number_format($this->modal_inventario_canti_pedida, 0, '.', ',')}}</span>
     
            </x-slot>
        
            <x-slot name="content"> 
                <table class="border-collapse border border-slate-400">
                    <tr>
                        <th class="border border-slate-300">   
                            Fecha
                        </th>
                        <th class="border border-slate-300">
                            Producción
                        </th>
                        <th class="border border-slate-300">
                            Facturación
                        </th>
                        <th class="border border-slate-300">
                            Nro factura
                        </th>
                        <th class="border border-slate-300">
                            Registrado por
                        </th>
                        <th class="border border-slate-300">
                            Registrado el
                        </th>
                        <th class="border border-slate-300">
                            Modificado por
                        </th>
                        <th class="border border-slate-300">
                            Última modificación
                        </th>
                    </tr>
                    {{-- Verificar que $modal_arr_multivariable no sea null, parece que
                    esto se puede dar o por ciclos del livewire , o por campos que no tengan listados 
                    desde tablas: --}}
                    @if($modal_arr_inventario_historial_detalle !== null)  
                        @foreach ($modal_arr_inventario_historial_detalle as $fila)
                            <tr>
                                <td class="border border-slate-300">
                                    {{$fila[0]}}
                                </td>
                                <td class="border border-slate-300 text-right">
                                    {{number_format($fila[1], 0, '.', ',')}}  
                                </td>
                                <td class="border border-slate-300 text-right">
                                    {{number_format($fila[2], 0, '.', ',')}}
                                </td>
                                <td class="border border-slate-300 text-center">
                                    {{$fila[3]}}
                                </td>
                                <td class="border border-slate-300">
                                    {{$fila[4]}}
                                </td>
                                <td class="border border-slate-300">
                                    {{$fila[5]}}
                                </td>
                                <td class="border border-slate-300">
                                    {{$fila[6]}}
                                </td>
                                <td class="border border-slate-300">
                                    {{$fila[7]}}
                                </td>
                            </tr>
                        @endforeach 
                    @endif
                </table>
    
                {{-- Botón Cerrar  --}}
                <div class="mx-auto w-1/4 mt-6">
                    <button type="button" wire:click="$set('ver_inventario_modal_visible', false)" class="w-full bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold" > Cerrar</button>
                </div>
            </x-slot>
        
            <x-slot name="footer">
    
            </x-slot>
        </x-jet-confirmation-modal>    
    

    {{-- 
        =====================================================================================
            Modal para modificar la producción y facturación
        ===================================================================================== 
    --}}     
    <x-jet-dialog-modal wire:model="modificar_produccion_facturacion_modal_visible">
        <x-slot name="title">
            MODIFICAR PRODUCCIÓN Y FACTURACIÓN<br>PARA EL PEDIDO: <span class="text-blue-500">{{$this->modal_modificar_pedido_conse}}</span>
            <br>
            Estado: <span class="text-blue-500">{{$this->modal_modificar_estado_nombre}}</span>
            <br>
            Cantidad pedida: <span class="text-blue-500">{{number_format($this->modal_modificar_canti_pedida, 0, '.', ',')}}</span>
            {{-- Mensajes de error desde submit....()  --}}
            <div class="text-red-500">
                {!!$this->modal_modificar_errores!!}

            </div>
        </x-slot>  

        <x-slot name="content"> 
            {{-- Verificar que $arr_modificar_inventarios no sea null, parece que
            esto se puede dar o por ciclos del livewire , o por campos que no tengan listados 
            desde tablas o porque se llega a la vista sin llenar la propiedad pública: --}}            
            @if($arr_modificar_inventarios !== null)
                @php
                    $ancho = 'w-3/12';                    
                @endphp
                {{-- <form  style="max-height: 450px; overflow-y: auto;" x-on:click.away="$wire.cerrar_modal_info_multivariable()" wire:submit.prevent="submit_info_multivariable()"> --}}
                <form  style="max-height: 450px; overflow-y: auto;"  wire:submit.prevent="submit_actualizar_produccion_facturacion()">
                    <div class="mt-3"> 
                        {{-- Cabeceras de cada columna  --}}
                        <div class="flex">
                            <div class="w-2/12  mr-2">
                                ID interno
                            </div>                        
                            <div class="{{$ancho}}  mr-2">
                                Fecha
                                <span class="text-red-500 ml-1">*</span>
                            </div>                        
                            <div class="{{$ancho}}  mr-2">
                                Producción
                                <span class="text-red-500 ml-1">*</span>
                            </div>                        
                            <div class="{{$ancho}}  mr-2">
                                Facturación
                                <span class="text-red-500 ml-1">*</span>
                            </div>                        
                            <div class="{{$ancho}}  mr-2">
                                Nro factura
                            </div>
                        </div>    

                        {{-- 
                            Petición de datos:
                            ($arr_modificar_inventarios es un array tal que cada
                             fila es a su vez otro array de 4 columnas)
                        --}}
                        @foreach ($arr_modificar_inventarios as $key => $arr_contenido)
                            <div class="flex">
                                @php
                                    $col_contador = 0;    
                                @endphp
                                @foreach ($arr_contenido as $key2 => $ele)
                                    @php
                                        if($key2 == 'produ_movi' || $key2 == 'factu_movi'){
                                            $alinea = "text-right";
                                        }else{
                                            $alinea = "text-left";
                                        }
                                    @endphp

                                    <div class="@if ($key2 == 'id') w-2/12 @else $ancho @endif mr-2 {{$alinea}}">
                                        <input type="text"
                                            wire:change = "totalizar_produ_factu" 
                                            wire:model = "arr_input_modificar_inventarios.{{$key}}.{{$key2}}"  
                                            @if ($key2 == 'id') readonly @endif
                                            class = "{{$alinea}} w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors "
                                        >
                                    </div>                                    
                                    @php
                                        $col_contador++;    
                                    @endphp
                                @endforeach
                            </div>
                        @endforeach 

                        {{-- totales  --}}
                        <div class="flex font-bold">
                            <div class="w-2/12  mr-6">
                                &nbsp;
                            </div>                        
                            <div class="w-3/12  mr-6 text-right">
                                Totales ===>
                            </div>                        
                            <div class="w-3/12  mr-6  text-right">
                                {{number_format($this->modal_modificar_total_produ, 0, '.', ',')}}
                            </div>                        
                            <div class="w-3/12  mr-6 text-right">
                                {{number_format($this->modal_modificar_total_factu, 0, '.', ',')}}
                            </div>                        
                            <div class="w-3/12  mr-6">
                                &nbsp;
                            </div>
                        </div>

                    </div>

                    {{-- Botones --}}
                    <div class="flex mx-auto">
                        <div class="mx-auto w-1/8 mt-6">
                            <button type="submit" class=" w-full bg-blue-600 hover:bg-blue-400 focus:bg-blue-400  text-white rounded-lg px-3 py-3 font-semibold"> Grabar cambios</button>
                        </div>
                        <div class="mx-auto w-1/4 mt-6">
                            {{-- <button type="button" class="w-full bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold" wire:click="$set('registrar_produccion_modal_visible', false)"> Cancelar</button> --}}
                            <button type="button" wire:click="cerrar_modal_modificar_produccion_facturacion()" class="w-full bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold" > Cancelar</button>
                        </div>
                    </div>                    
                </form>
            @endif

        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>  
    
    {{-- 
        =====================================================================================
            Modal para anular un pedido_conse
        ===================================================================================== 
    --}}   
    <x-jet-dialog-modal wire:model="anular_modal_visible">
        <x-slot name="title">
            <span class="text-gray-500">ANULACIÓN DEL PEDIDO {{$this->modal_anular_pedido_conse}}</span>
            <br><br>
            <table>
                <tr>
                    <td>Estado: </td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_anular_estado_nombre}}</td>
                </tr>
                <tr>
                    <td>Cliente</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_anular_cliente_nombre}}</td>
                </tr>
                <tr>
                    <td>Dirección de entrega</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_anular_dir_entrega}}</td>
                </tr>
                <tr>
                    <td>Tipo de producto</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_anular_tipo_producto_nombre}}</td>
                </tr>
                <tr>
                    <td>Código del producto</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_anular_codigo_producto}}</td>
                </tr>
                <tr>
                    <td>Cantidad pedida</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_anular_pedida_canti}}</td>
                </tr>
                {{-- <tr>
                    <td>Cantidad producida a hoy</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_anular_produ_canti}}</td>
                </tr>
                <tr>
                    <td>Cantidad facturada a hoy</td>
                    <td>&nbsp;=>&nbsp;&nbsp;</td>
                    <td>{{$this->modal_anular_factu_canti}}</td>
                </tr>                 --}}
            </table>
        </x-slot>
    
        <x-slot name="content"> 
            <form wire:submit.prevent="submit_anular_pedido_conse()" id="formanular">   
                {{-- Pedir causa de la anulación --}}
                <div class="flex">

                    {{-- Causa de la anulación --}}
                    <div class="mr-2">
                        <label class="w-full font-bold text-base my-auto  mb-2 ml-1">
                            Causa de la anulación:
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <textarea rows="3" cols="50" wire:model="modal_anular_input_causa" id="idmodalcausa" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
                        </div>                            
                        @error('modal_anular_input_causa') <span class="text-red-500"><br>{{ $message }}</span> @enderror   
                    </div>                

                </div>

                {{-- Buttons --}}
                <div class="flex mx-auto">
                    <div class="mx-auto w-1/8 mt-6">
                        <button type="submit" class=" w-full bg-red-600 hover:bg-red-400 focus:bg-red-400  text-white rounded-lg px-3 py-3 font-semibold"> Anular el pedido</button>
                    </div>
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" wire:click="cerrar_modal_anular()" class="w-full bg-blue-500 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3 font-semibold" > Cancelar</button>
                    </div>
                </div>                 
            </form>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>    
</div>
