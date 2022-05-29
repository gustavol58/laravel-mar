<div>
{{-- {{dd($registros)}}     --}}
    @livewire('menu-own')
    <div class="bg-white border rounded border-gray-300 m-2 p-2 text-gray-600">
        {{-- título y botones  --}}
        <div class="flex mb-2">
            <div class="w-4/5">
                <h1 class="font-black text-3xl ">Clientes</h1>
            </div>
            {{-- botones  --}}
            <div class="flex w-1/5 items-end justify-end">
                {{-- botón NUEVO  --}}
                <a href="{{route('crear-cliente' , [
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
                        {{-- id  --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.id')">
                                        <span class="px-2">Id</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'cli.id' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'cli.id' && $ordenar_tipo == ' desc') 
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
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.estado')">
                                        <span class="px-2">Estado</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'cli.estado' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'cli.estado' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- tipo documento --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('tipdoc.nombre_tipo_doc')">
                                         <span class="px-2">Tipo doc</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'tipdoc.nombre_tipo_doc' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'tipdoc.nombre_tipo_doc' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                        </th>
                        {{-- nit  --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.nit')">
                                        <span class="px-2">Nit</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'cli.nit' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'cli.nit' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- DIV_  --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.div_')">
                                        <span class="px-2">Div</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'cli.div_' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'cli.div_' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- nombre cliente --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.nom_cliente')">
                                        <span class="px-2">Nombre</span>
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
                        {{-- direccion --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.direccion')">
                                        <span class="px-2">Dirección</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'cli.direccion' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'cli.direccion' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- ciudad  --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('ciu.nombre_ciudad')">
                                        <span class="px-2">Ciudad</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'ciu.nombre_ciudad' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'ciu.nombre_ciudad' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                           </div>
                        </th>
                        {{-- fijo --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.fijo')">
                                         <span class="px-2">Fijo</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'cli.fijo' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'cli.fijo' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                         </th>                        
                        {{-- celular --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.celular')">
                                         <span class="px-2">Celular</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'cli.celular' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'cli.celular' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                         </th>                        
                        {{-- contacto --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.contacto')">
                                         <span class="px-2">Contacto</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'cli.contacto' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'cli.contacto' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                         </th>                        
                        {{-- email --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.email')">
                                         <span class="px-2">E-mail</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'cli.email' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'cli.email' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                         </th>                        
                        {{-- condiciones --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('cli.condiciones')">
                                         <span class="px-2">Condiciones comerciales<br>(0=Contado)</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'cli.condiciones' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'cli.condiciones' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                         </th>                        
                        {{-- comercial asignado --}}
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('usu.name')">
                                        <span class="px-2">Comercial</span>
                                     </button>
                                </div>
                                @if($ordenar_campo == 'usu.name' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'usu.name' && $ordenar_tipo == ' desc') 
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
                    {{-- id  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="cliente_id">
                    </td>                     
                    {{-- estado  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="estado">
                    </td>
                    {{-- tipo documento --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="tipo_documento">
                    </td>                                          
                    {{-- nit  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="nit">
                    </td>                     
                    {{-- div_ --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="div_">
                    </td>                     
                    {{-- nombre cliente  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="nom_cliente">
                    </td>                     
                    {{-- direccion  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="direccion">
                    </td>                     
                    {{-- ciudad --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="nombre_ciudad">
                    </td> 
                    {{-- fijo  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fijo">
                    </td>                                           
                    {{-- celular  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="celular">
                    </td>                                           
                    {{-- contacto  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="contacto">
                    </td>                                           
                    {{-- email  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="email">
                    </td>                                           
                    {{-- condiciones  --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="condiciones">
                    </td>                                           
                    {{-- comercial asignado --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="comercial_asignado">
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
                    <tr class="bg-white border-4 border-gray-200">
                        <td class="border border-gray-300 text-center">
                            {{$registro->cliente_id}}
                        </td>   

                        {{-- estado: se muestra con iconos:                         --}}
                        <td class="border border-gray-300 text-center ">
                            {{-- Este div es el EXTERNO para mostrar el mensaje emergente:  --}}
                            <div class="group relative w-full text-center">
                                @if ($registro->estado == 1)
                                    {{-- Muestra el ícono para el estado incompleto:  --}}
                                    <svg class="inline-block w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.41 19L22.54 21.12L21.12 22.54L19 20.41L16.88 22.54L15.47 21.12L17.59 19L15.47 16.88L16.88 15.47L19 17.59L21.12 15.47L22.54 16.88L20.41 19M13.09 18H4V6H20V13.09C20.72 13.21 21.39 13.46 22 13.81V6C22 4.89 21.11 4 20 4H4C2.9 4 2 4.89 2 6V18C2 19.11 2.9 20 4 20H13.09C13.04 19.67 13 19.34 13 19C13 18.66 13.04 18.33 13.09 18Z" />
                                    </svg>
                                    {{-- Este es el div INTERNO para mostrar el mensaje emergente:  --}}
                                    <div class="opacity-0 w-full text-center bg-blue-400 text-white font-bold  text-xs absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                        Incompleto.
                                        <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                    </div>        
                                @elseif($registro->estado == 2)
                                    @if (Auth::user()->roles[0]->name == 'admin')
                                        {{-- Muestra el botón para el estado pendiente:  --}}
                                        <a href="#" wire:click="aprobar_cliente({{$registro->cliente_id}})" class="underline text-blue-500 pr-2 text-center">
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
                                        <a href="#" wire:click="desaprobar_cliente({{$registro->cliente_id}})" class="underline text-blue-500 pr-2 text-center">
                                            <svg class="inline-block w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z" />
                                            </svg>
                                        </a>                                    
                                    @else
                                        {{-- Muestra el ícono para el estado aprobado:  --}}
                                        <svg class="inline-block w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z" />
                                        </svg>           
                                    @endif
                                    {{-- Este es el div INTERNO para mostrar el mensaje emergente:  --}}
                                    <div class="opacity-0 w-full text-center bg-blue-400 text-white font-bold  text-xs absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                        Aprobado.
                                        <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                    </div>                                
                                @endif
                            </div>
                            {{-- Fin del div EXTERNO para mostrar el mensaje emergente:  --}}
                        </td> 
                        {{-- Fin de la columna ESTADOS  --}}
                        
                        <td class="border border-gray-300 text-center">
                            {{$registro->tipo_documento}}
                        </td>                            
                        <td class="border border-gray-300 text-center">
                            {{$registro->nit}}
                        </td>                            
                        <td class="border border-gray-300 text-center">
                            {{$registro->div_}}
                        </td>                            
                        <td class="border border-gray-300 text-center">
                            {{$registro->nom_cliente}}
                        </td>                            
                        <td class="border border-gray-300 text-center">
                            {{$registro->direccion}}
                        </td>                            
                        <td class="border border-gray-300 text-center">
                            {{$registro->nombre_ciudad}}
                        </td>
                        <td class="border border-gray-300 text-center">
                            {{$registro->fijo}}
                        </td>                                                        
                        <td class="border border-gray-300 text-center">
                            {{$registro->celular}}
                        </td>                                                        
                        <td class="border border-gray-300 text-center">
                            {{$registro->contacto}}
                        </td>                                                        
                        <td class="border border-gray-300 text-center">
                            {{$registro->email}}
                        </td>                                                        
                        <td class="border border-gray-300 text-center">
                            {{$registro->condiciones}}
                        </td>                                                        
                        <td class="border border-gray-300 text-center">
                            {{$registro->comercial_asignado}}
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
                        <td>
                            {{-- botón modificar cliente  --}}
                            @if(Auth::user()->roles[0]->name == 'admin')
                                <td class="border border-gray-300 px-4 text-yellow-500">
                                    <a href="{{route('crear-cliente' , [
                                        'operacion' => 'modificar',
                                        'modificar_cliente_id' => $registro->cliente_id,                              
                                    ])}}"> 
                                    <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                    </a>
                                </td>  
                            @else 
                                {{-- Si el usuario es 'comer', depende del estado que se pueda modificar o no:  --}}
                                @if ($registro->estado !== 3)
                                    <td class="border border-gray-300 px-4 text-yellow-500">
                                        <a href="{{route('crear-cliente' , [
                                            'operacion' => 'modificar',
                                            'modificar_cliente_id' => $registro->cliente_id,                              
                                        ])}}"> 
                                        <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                        </a>
                                    </td>                                     
                                @else
                                    
                                @endif
                            @endif
                        </td>                        
                    </tr>
                @endforeach                      
            </table>
        </div> 

   </div>
   {{-- fin del marco principal --}}
</div>
