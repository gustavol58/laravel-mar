<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
{{-- {{dd($registros)}} --}}
    @livewire('menu-own')
    <div class="bg-white border rounded border-gray-300 m-2 p-2 text-gray-600">
        {{-- 
            =====================================================================================
                Título y botones
            ===================================================================================== 
        --}}        
        <div class="flex mb-2">
            <div class="w-4/5">
                <h1 class="font-black text-3xl ">Usuarios</h1>
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
                    <a href="{{route('register')}}">
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

                        {{-- estado  --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('usu.state')">
                                         <span class="px-2">Estado</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'usu.state' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'usu.state' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                         </th>
 


                        {{-- nombre completo del usuario --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('usu.name')">
                                         <span class="px-2">Nombre completo</span>
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
                        {{-- nombre de acceso del usuario --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('usu.user_name')">
                                         <span class="px-2">Nombre de acceso</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'usu.user_name' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'usu.user_name' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                        </th>                                               
                        {{-- Email --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('usu.email')">
                                         <span class="px-2">Email</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'usu.email' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'usu.email' && $ordenar_tipo == ' desc') 
                                     <div class=" bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                     </div>
                                 @endif                                 
                            </div>
                        </th>                                               
                        {{-- Rol --}}
                        <th class="border border-l-1  sticky top-0">
                            <div class="flex">
                                 <div class="flex-1">
                                     <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('rol.name')">
                                         <span class="px-2">Rol</span>
                                      </button>
                                 </div>
                                 @if($ordenar_campo == 'rol.name' && $ordenar_tipo == ' asc') 
                                     <div class="bg-blue-500 text-white ml-4">
                                         <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                     </div>
                                 @endif 
                                 @if($ordenar_campo == 'rol.name' && $ordenar_tipo == ' desc') 
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
                    {{-- estado: El filtro tiene un mensaje de ayuda --}}
                    {{-- <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="estado_filtro" placeholder="0: INACTIVOS. 1: ACTIVOS.">
                    </td>  --}}
                    
                    {{-- estado: El filtro del estado, tiene un mensaje de ayuda --}}
                    <td>
                        {{-- Este div es el EXTERNO para mostrar el mensaje emergente de la ayuda:  --}}
                        <div class="group relative w-full text-center">
                            <input class="w-full mt-21 border rounded border-gray-300" type="text" wire:model="estado_filtro">
                            {{-- Este es el div INTERNO para mostrar el mensaje emergente:  --}}
                            {{-- <div class="opacity-0 w-full text-center bg-green-500 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none"> --}}
                            <div class="opacity-0 w-full text-center bg-green-500 text-white font-bold  text-sm absolute group-hover:opacity-100 left-3/4 pointer-events-none">
                                Digite 0 para ver los INACTIVOS. <br>1 para ver los ACTIVOS.
                                {{-- <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg> --}}
                            </div>                           
                        </div>
                    </td> 

                    {{-- nombre completo del usuario --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="nombre_completo_filtro">
                    </td>                    
                    {{-- nombre de acceso --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="nombre_acceso_filtro">
                    </td>                    
                    {{-- email --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="email_filtro">
                    </td>                    
                    {{-- rol --}}
                    <td>
                        <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="rol_completo_filtro">
                    </td>                    
                </tr>

                {{-- 
                    =====================================================================================
                        Cuerpo de la tabla: registros
                    ===================================================================================== 
                --}}    
                @foreach ($registros as $registro)                                        
                    <tr class="bg-white border-4 border-gray-200">
                        {{-- Column action para el botón edit (solo para usuarios activos) --}}
                        <td class="flex border border-gray-300 px-4 text-yellow-500">
                            @if ($registro->estado_filtro)
                                {{-- Botón: Modificar usuario:  --}}
                                {{-- Este div es el EXTERNO para mostrar un mensaje emergente ahref:  --}}
                                <div class="group relative  text-center">
                                    <a href="{{route('modificar-usuario' , [
                                        'modificar_usuario_id' => $registro->id,
                                    ])}}">
                                        <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                    </a> 
                                    {{-- Este es el div INTERNO para mostrar un mensaje emergente ahref:  --}}
                                    <div class="opacity-0 text-center bg-yellow-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                        Modificar usuario.
                                    </div>                                     
                                </div>
                            @else
                                &nbsp;                                
                            @endif
                        </td>   

                        {{-- estado: se muestra con iconos:                         --}}
                        <td class="border border-gray-300 text-center ">
                            {{-- Este div es el EXTERNO para mostrar el mensaje emergente:  --}}
                            <div class="group relative w-full text-center">
                                @if ($registro->estado_filtro == 1)
                                    {{-- el usuario está Activo --}}
                                    <button  wire:click="cambiar_estado_usuario({{$registro->id}} , 0)"   class="bg-white text-green-500  hover:bg-white  hover:text-green-700 ">
                                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    {{-- Este es el div INTERNO para mostrar el mensaje emergente:  --}}
                                    <div class="opacity-0 text-center bg-green-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                        Click para Inactivar usuario.
                                    </div>                                     
                                @else 
                                    <button wire:click="cambiar_estado_usuario({{$registro->id}} , 1)"  class="bg-white text-red-500  hover:bg-white  hover:text-red-700 ">
                                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>                                                                                
                                    {{-- Este es el div INTERNO para mostrar el mensaje emergente:  --}}
                                    <div class="opacity-0 text-center bg-red-400 text-white font-bold  text-sm absolute group-hover:opacity-100 bottom-full pointer-events-none">
                                        Click para Activar usuario.
                                    </div>                                     
                                @endif                        

                            </div>
                        </td>
                        {{-- fin de la columna estado         --}}

                        {{-- Nombre completo del usuario  --}}
                        <td class="border border-gray-300 text-center">
                            {{$registro->nombre_completo}}
                        </td>                        
                        {{-- Nombre de acceso  --}}
                        <td class="border border-gray-300 text-center">
                            {{$registro->nombre_acceso}}
                        </td>                        
                        {{-- Email  --}}
                        <td class="border border-gray-300 text-center">
                            {{$registro->email}}
                        </td>                        
                        {{-- Rol  --}}
                        <td class="border border-gray-300 text-center">
                            {{$registro->rol_completo}}
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
    {{-- <x-jet-dialog-modal wire:model="registrar_produccion_modal_visible">

    </x-jet-confirmation-modal> --}}
</div>
