<div>

   @livewire('menu-own')
{{-- {{dd($arr_estados_id)}} --}}
   {{-- 23oct2021 <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600"> --}}
   <div class="bg-white border rounded border-gray-300 m-2 p-2 text-gray-600">
      {{-- 23oct2021 titulo y botones en una sola linea  --}}
      <div class="flex mb-2">
         {{-- <div class="w-3/4"> --}} 
         <div class="w-4/5">
            {{-- 23oct2021 <h1 class="font-black text-4xl ">Productos del Tipo de producto: {{$tipo_producto_nombre}}</h1>          --}}
            <h1 class="font-black text-3xl ">Productos del Tipo de producto:<br><span class="text-blue-500">{{$tipo_producto_nombre}}</span></h1>
         </div>

         {{-- botones  --}}
         {{-- <div class="flex w-3/5 items-end justify-end"> --}}
         <div class="flex w-1/5 items-end justify-end">
            {{-- botón NUEVO  --}}
            {{-- 22oct2021 
            El botón nuevo solo estará activo roles 'admin' y 'comer': --}}
            @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('comer'))
               {{-- <div class="w-2/5"> --}}
                  {{-- 24dic2021:  --}}
                  <a href="{{route('crear-formu' , [
                              'tipo_producto_recibido_id' => $tipo_producto_id , 
                              'tipo_producto_recibido_nombre' => $tipo_producto_nombre ,
                              'tipo_producto_recibido_slug' => $tipo_producto_slug ,
                              'operacion' => 'crear' ,
                              'tipo_producto_recibido_prefijo' => $tipo_producto_prefijo ,
                           ])}}">
                        {{-- 23oct2021 <button type="button"  class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold"><svg class="inline-block align-bottom  text-white fill-current w-6 h-6 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/></svg> Nuevo</button>                         --}}
                        <button type="button"  
                           class="mr-8 h-8 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 font-semibold">
                           <svg class="inline-block align-bottom  text-white fill-current w-6 h-6 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/></svg>
                           Nuevo
                        </button>
                  </a>
               {{-- </div> --}}
            @endif

            {{-- botón REGRESAR  --}}
            {{-- <div class="w-1/4"> --}}
               <a href="{{route('escoger-tipo_producto')}}" >                
                  <button type="button"  
                     class="h-8 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 font-semibold">
                     {{-- <svg class="inline-block align-text-top w-6 h-6"> --}}
                     <svg class="inline-block align-bottom  text-white fill-current w-6 h-6 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill="currentColor" d="M13.34,8.17C12.41,8.17 11.65,7.4 11.65,6.47A1.69,1.69 0 0,1 13.34,4.78C14.28,4.78 15.04,5.54 15.04,6.47C15.04,7.4 14.28,8.17 13.34,8.17M10.3,19.93L4.37,18.75L4.71,17.05L8.86,17.9L10.21,11.04L8.69,11.64V14.5H7V10.54L11.4,8.67L12.07,8.59C12.67,8.59 13.17,8.93 13.5,9.44L14.36,10.79C15.04,12 16.39,12.82 18,12.82V14.5C16.14,14.5 14.44,13.67 13.34,12.4L12.84,14.94L14.61,16.63V23H12.92V17.9L11.14,16.21L10.3,19.93M21,23H19V3H6V16.11L4,15.69V1H21V23M6,23H4V19.78L6,20.2V23Z" />
                     </svg> Regresar
               </button>
               </a>              
            {{-- </div>    --}}
         </div>
      </div>

      {{-- Registros: --}}
      @if ($registros->hasPages())
         {{ $registros->links() }}   

         {{-- 23oct2024: ensayos para tratar de poner pequeños los link de paginación laravel, no funcionó --}}
         {{-- {{ $registros->links('vendor.pagination.simple-tailwind') }}  anterior - siguiente  --}}
         {{-- {{ $registros->links('vendor.pagination.default') }}  vertical  --}}
         {{-- {{ $registros->links('vendor.pagination.tailwind') }}    este es es que se modificó, pero no resultó --}}
         {{-- {{ $registros->links('vendor.pagination.semantic-ui') }}    solo paginas   --}}
         {{-- {{ $registros->links('vendor.pagination.simple-bootstrap-4') }}    anterior - siguiente con enter  --}}
         {{-- {{ $registros->links('vendor.pagination.simple-default') }}     --}}

      @else 
         Mostrando {{count($registros)}} registros
      @endif  
      <br>
{{-- {{dd($campos_detalle)}} --}}

      {{-- 24oct2021 Si la longitud del nombre del tipo de producto cabe en una 
      sola linea (65 caracteres o menos), el tamaño vh para poder ver 
      el scroll horizontal será de 58vh. Si es mayor el tamaño debe
      reducirse a 52vh, y asi sucesivamente: --}}
      {{-- 23oct2021 <div class="overflow-scroll " style="height: 68vh;"> --}}
{{-- {{dd(strlen($tipo_producto_nombre))}}          --}}
      @if (strlen($tipo_producto_nombre) <= 65)
         <div class="overflow-scroll " style="height: 58vh;">
      @elseif(strlen($tipo_producto_nombre) <= 130)
         <div class="overflow-scroll " style="height: 52vh;">
      @elseif(strlen($tipo_producto_nombre) <= 195)
         <div class="overflow-scroll " style="height: 46vh;">
      @else
         <div class="overflow-scroll " style="height: 40vh;">
         {{-- <div class="overflow-scroll " style="height: 34vh;"> --}}
      @endif
         <table class="table-fixed ">
            {{-- títulos de columna y botón ordenar en cada una --}}
            <thead class="justify-between">
               <tr class="bg-green-500">
                  @foreach ($campos_detalle as $un_campo_detalle)
                        <th class="border border-l-1  sticky top-0">
                           <div class="flex">
                              <div class="flex-1">
                                 <button class="text-white font-bold bg-green-500  border-green-500 rounded" type="button" wire:click="ordenar('{{$un_campo_detalle['slug']}}')">
                                    <span class="px-2">{{$un_campo_detalle['cabecera']}}</span>
                                 </button>
                              </div>   
                              @if($ordenar_campo == $un_campo_detalle['slug'] && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                       <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                              @endif 
                              @if($ordenar_campo == $un_campo_detalle['slug'] && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                       <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                              @endif                                                            
                           </div>
                        </th>
                  @endforeach
               </tr>
            </thead>

            {{-- filtros  --}}
            <tr>
               @foreach ($campos_detalle as $un_campo_detalle2)
                  <td>
                     {{-- 23oct2021 <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="arr_filtros_slug.{{ $un_campo_detalle2['slug'] }}"> --}}
                     <input class="w-full h-8 border rounded border-gray-300" type="text" wire:model="arr_filtros_slug.{{ $un_campo_detalle2['slug'] }}">
                  </td>
               @endforeach
            </tr>

            {{-- cuerpo de la tabla  --}}
{{-- {{dd($registros)}};        --}}
{{-- {{dd($registros_arr_html_elementos_id['estado'])}};        --}}
{{-- @php
$aaa = 1;    
@endphp --}}
{{-- {{dd($registros)}} --}}
@foreach ($registros as $registro)
               <tr class="bg-white border-4 border-gray-200">
                  @foreach ($registro as $key => $valor)
                     {{-- <td class="border border-gray-300 text-center @if($key == 'estado_cerrado') text-blue-500  @endif"> --}}
                     <td class="border border-gray-300 text-center">
                        @if ($key == 'codigo')
                           <span class="whitespace-nowrap">
                        @else
                           <span>
                        @endif
                        @if ($registros_arr_html_elementos_id[$key] == 11)
                           {{-- Es un elemento tipo subir archivo  --}}
                           @if ($valor !== null)
                              @php 
                                 // recordar que en el hosting, los archivos fueron grabados 
                                 // con su nombre procesado con SLUG y con prefijo: nro id:
                                 $ruta_nombre_archivo = asset('storage/subidos_desde_formus/' . $tipo_producto_slug . '/' . $key . '/' . $registro->id . '_' . $valor);  
                              @endphp
                              <a href="{{$ruta_nombre_archivo}}" download="{{$ruta_nombre_archivo}}" target="_blank" class="underline text-blue-500 pr-2 text-center">
                                 <svg class="w-6 h-6 m-auto inline" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M17,13L12,18L7,13H10V9H14V13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z" />
                                 </svg>
                              </a>
                           @endif
                        @elseif($registros_arr_html_elementos_id[$key] == 12) 
                           {{-- Es un elemento tipo multivariable  --}}
                           <a href="#" wire:click="mostrar_modal_multivariable({{$registro->id}} , '{{$key}}')" class="underline text-blue-500 pr-2 text-center">
                              <svg class="inline-block align-text-top w-6 h-6">
                                 <path fill="currentColor" d="M7 2H21C22.11 2 23 2.9 23 4V16C23 17.11 22.11 18 21 18H7C5.9 18 5 17.11 5 16V4C5 2.9 5.9 2 7 2M7 6V10H13V6H7M15 6V10H21V6H15M7 12V16H13V12H7M15 12V16H21V12H15M3 20V6H1V20C1 21.11 1.89 22 3 22H19V20H3Z" />
                             </svg>  
                           </a>
                        @else
                           {{-- Es cualquier otro tipo de elemento  --}}

                           {{-- 21oct2021: La columna 'estado' debe ser actionColum si
                                el rol es 'admin':  --}}
                           @if ($key == 'estado' && Auth::user()->hasRole('admin'))
                              <a href="#" wire:click="modificar_estado_producto({{$registro->id}} , '{{$valor}}' , '{{$registro->codigo}}')" class="text-blue-600 hover:text-blue-800 visited:text-purple-600">
                                 {{$valor}}
                              </a>
                           @else
                              {{-- 31oct2021: La columna 'cerrado' debe ser un ícono:  --}}
                              @if ($key == 'estado_cerrado')
                                 @if ($valor)
                                    <svg class="inline-block w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                       <path d="M12,17A2,2 0 0,0 14,15C14,13.89 13.1,13 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10C4,8.89 4.9,8 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z" />
                                    </svg>
                                 @endif
                              @else
                                 {{$valor}}
                              @endif
                           @endif
                        @endif
                        </span>   {{-- Cierra el span que fue abierto en el anterior if $key == 'codigo' --}}
                     </td>                            
                  @endforeach

                  {{-- botones editar y eliminar productos --}}
                  {{-- 
                     22oct2021: 
                     El rol 'admin' siempre tendrá habilitados los 2 botones. 
                     La habilitación en los demás roles depende...
                  --}}
{{-- 
@php
   $aaa++;    
@endphp 
{{$aaa}}
{{$registro->id}}                  
{{$tipo_producto_id}}                  
{{$tipo_producto_nombre}}                  
{{$tipo_producto_slug}}                  
{{$tipo_producto_prefijo}}  
@if ($aaa==3)
    {{Auth::user()->roles[0]->name . " --- " . $arr_estados_id[$registro->estado]}}
    {{dd('revisar')}}
@endif
{{dd('revisar')}}                   
--}}

                  @php

                     // 10feb2022:
                     // Debido a que el componente class de este blade, en el array $registros a veces 
                     // envia los dos campos 'tipo_producto_prefijo' y 'estado' y otras veces no envia 
                     // esos dos campos, se hicieron necesarios los dos siguientes ifs, de tal manera 
                     // que si esos dos campos no llegan entonces sean creados con los dos valores por 
                     // defecto '@@@' y null
                     // Recordar que 'prefijo' y 'estado' son campos que no son usados por los tipos de 
                     // producto a los cuales el admin no les asigna prefijo.
                     if(isset($tipo_producto_prefijo)){
                        $aux_tipo_producto_prefijo = $tipo_producto_prefijo;
                     }else{
                        $aux_tipo_producto_prefijo = '@@@' ;
                     }    
                     if(isset($registro->estado)){
                        $aux_registro_estado = $registro->estado;
                     }else{
                        $aux_registro_estado = null ;
                     }    
                  @endphp

                  @if(Auth::user()->roles[0]->name == 'admin' || $this->permisos_editar_anular(Auth::user()->roles[0]->name , $arr_estados_id[$registro->estado]))
                     {{-- 27nov2021: 
                        Entra a este if si se cumpla una de estas dos condiciones: 
                        a) Es un usuario administrador 
                        b) Si no es admin, el método $this->permisos_editar_anular determina 
                           si el usuario tiene o no acceso:  
                     --}}

                     {{-- 24dic2021; nuevo parámetro operación  --}} 
                     <td class="border border-gray-300 px-4 text-yellow-500">
                        <a href="{{route('crear-formu' , [
                              'tipo_producto_recibido_id' => $tipo_producto_id , 
                              'tipo_producto_recibido_nombre' => $tipo_producto_nombre ,
                              'tipo_producto_recibido_slug' => $tipo_producto_slug ,
                              'operacion' => 'modificar',
                              'tipo_producto_recibido_prefijo' =>  $aux_tipo_producto_prefijo,
                              'formu__id' => $registro->id,                              
                              'formu__codigo_producto' => $registro->codigo,                               
                              'formu__estado_nombre' => $aux_registro_estado,                               
                           ])}}"> 
                           <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                        </a>
                     </td>

                     <td class="border border-gray-300 px-4 text-red-500">
                        @php
                              // Al descomentariar, sobrará el @php....
                              // <a href="#" wire:click="xxxanular_registro()">
                              //    <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></span>
                              // </a>                     
                        @endphp
                        <a href=""
                              onclick="alert('Opción en construcción.')" >
                              <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></span>
                        </a>
                     </td>
                  @endif
               </tr>

            @endforeach
{{-- {{dd('revisar')}}         --}}

      </table>
      </div>
   </div>

   {{-- Modal para mostrar campos multivariable --}}
   <x-jet-dialog-modal wire:model="mostrar_multimodal_visible">
      <x-slot name="title">
         <div class="flex ">
             {{-- Título del modal  --}}
             <div class="w-10/12 mt-4">
                 <span class="text-gray-500 text-4xl">Campo multivariable: {{$modal_titulo_campo}}</span>       
             </div>
         </div>
      </x-slot>

      <x-slot name="content"> 
         {{-- Verificar que $modal_arr_multivariable no sea null, parece que
         esto se puede dar o por ciclos del livewire , o por campos que no tengan listados 
         desde tablas: --}}
         @if($modal_arr_multivariable !== null)
             @php 
                 // calcular el ancho, de acuerdo al número de columnas: 
                 $num_columnas = count($modal_arr_multivariable); 
                 switch ($num_columnas) {
                     case 1:
                         $ancho = 'w-full';
                         break;
                     case 2:
                         $ancho = 'w-6/12';
                         break;
                     case 3:
                         $ancho = 'w-4/12';
                         break;
                     case 4:
                         $ancho = 'w-3/12';
                         break;
                     case 5:
                         $ancho = 'w-1/5';
                         break;
                     case 6:
                         $ancho = 'w-2/12';
                         break;
                     default:
                         $ancho = 'w-1/12';
                         break;
                 }
             @endphp

             <form  style="max-height: 450px; overflow-y: auto;" x-on:click.away="$wire.cerrar_modal_multivariable()" wire:submit.prevent="submit_info_multivariable()">
                 <div class="mt-3"> 
                     {{-- Cabeceras de cada columna  --}}
                     <div class="flex">
                         @foreach ($modal_arr_multivariable_cabeceras as $fila_cabecera)
                             <div class="{{$ancho}}  mr-2">
                                 {{$fila_cabecera['cabecera']}}
                                 {{-- <span class="text-red-500 ml-1">*</span> --}}
                             </div>
                         @endforeach
                     </div>
                     {{-- Mostrar las filas-columnas del multivariable: --}}
                     @foreach ($modal_arr_multivariable as $key => $fila_contenido)
                        <div class="flex">
                           @foreach ($fila_contenido as $col)
                              <div class="{{$ancho}} mr-2 w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                 {{$col}} 
                              </div>       
                           @endforeach
                        </div>
                     @endforeach
                     {{-- botón para cerrar el modal:  --}}
                     <div>
                         <div class="flex mb-4" >
                             <div class="w-4/12 ml-4 pr-4">
                                 {{-- botón cerrar : --}}
                                 <button type="button"  wire:click="cerrar_modal_multivariable" class="w-full mt-4 bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3">
                                     <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                         <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                     </svg>
                                     <span class="align-text-bottom font-semibold">Cerrar</span>
                                 </button>
                             </div>
                         </div>
                     </div>    
                 </div>
             </form>
         @endif
     </x-slot>

      <x-slot name="footer">

      </x-slot>
   </x-jet-confirmation-modal>   

    {{-- MODAL con opciones para estados --}}
    <x-jet-dialog-modal wire:model="mostrar_cambio_estados_visible">
      <x-slot name="title">
          <h1 class="font-bold text-xl uppercase text-gray-600">Cambio de estado para el producto {{$modal_estados_codigo_producto}}</h1>
      </x-slot>
      <x-slot name="content"> 
              {{-- Botones --}}
              <div class="flex mx-auto">
                  <div class="mx-auto @if($modal_estados_boton3 !== "") ml-1 @else w-1/4 @endif    mt-6">
                     <button type="button" wire:click="modificar_estado_nucleo({{$modal_estados_formu__id}} , {{$modal_estados_nuevo_estado_id_boton1}})" class="w-full bg-blue-500 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3 font-semibold">
                          {{$modal_estados_boton1}}
                     </button>
                  </div>
                  <div class="mx-auto @if($modal_estados_boton3 !== "") ml-1 @else w-1/4 @endif  mt-6">
                     <button type="button" wire:click="modificar_estado_nucleo({{$modal_estados_formu__id}} , {{$modal_estados_nuevo_estado_id_boton2}})" class="w-full bg-blue-500 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3 font-semibold">
                        {{$modal_estados_boton2}}
                     </button>
                  </div>
                  @if ($modal_estados_boton3 !== "")
                     <div class="mx-auto ml-1 mt-6">
                        <button type="button" wire:click="modificar_estado_nucleo({{$modal_estados_formu__id}} , {{$modal_estados_nuevo_estado_id_boton3}})" class="w-full bg-blue-500 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3 font-semibold">
                           {{$modal_estados_boton3}}
                        </button>
                     </div>                      
                  @endif
                  <div class="mx-auto @if($modal_estados_boton3 !== "") ml-1 @else w-1/4 @endif mt-6">
                     {{-- <button type="button" wire:click="$set('mostrar_cambio_estados_visible', false)" class=" w-full bg-red-600 hover:bg-red-400 focus:bg-red-400  text-white rounded-lg px-3 py-3 font-semibold"> --}}
                     <button type="button" wire:click="cancelar_modal_estados()" class=" w-full bg-red-600 hover:bg-red-400 focus:bg-red-400  text-white rounded-lg px-3 py-3 font-semibold">
                         Cancelar
                     </button>
                 </div>                  
              </div> 
      </x-slot>
  
      <x-slot name="footer">

      </x-slot>
  </x-jet-confirmation-modal>    
  {{-- FIN DEL MODAL para avisar que hay info sin grabar, antes de cerrar la creación de productos --}}

     
</div>
