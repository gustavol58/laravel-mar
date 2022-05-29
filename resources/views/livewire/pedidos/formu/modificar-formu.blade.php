<div>
    {{-- {{dd($arr_input_campos)}} --}}
    {{-- {{dd($elementos_html)}} --}}
    @livewire('menu-own')

    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">

        <div class="">
            <h1 class="font-bold text-xl mb-4">Modificación del producto {{$arr_input_campos['codigo']}}</h1>
        </div>

        {{-- Formulario:  --}}
        <form  wire:submit.prevent="submit_formu()" enctype="multipart/form-data">                
            <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
                <h1 class="font-bold text-4xl text-center ">{{$tipo_producto->titulo}}</h1> 
                <h1 class="font-bold text-2xl text-center ">{{$tipo_producto->subtitulo}}</h1> 
                <div class="grid grid-cols-{{$tipo_producto->columnas}}">
                    {{-- Cada uno de los inputs:  --}}
                    @foreach($elementos_html as $fila)                            
                        @switch($fila->html_elemento_id)
                            @case(1)
                                {{-- es input text  --}}
                                @include('livewire.parciales.crear_formu.inputs' , ['icono_input_d' => 'M18.5,4L19.66,8.35L18.7,8.61C18.25,7.74 17.79,6.87 17.26,6.43C16.73,6 16.11,6 15.5,6H13V16.5C13,17 13,17.5 13.33,17.75C13.67,18 14.33,18 15,18V19H9V18C9.67,18 10.33,18 10.67,17.75C11,17.5 11,17 11,16.5V6H8.5C7.89,6 7.27,6 6.74,6.43C6.21,6.87 5.75,7.74 5.3,8.61L4.34,8.35L5.5,4H18.5Z']) 
                                @break
                            @case(2) 
                                {{-- input número entero --}}
                                @include('livewire.parciales.crear_formu.inputs' , ['icono_input_d' => 'M4,17V9H2V7H6V17H4M22,15C22,16.11 21.1,17 20,17H16V15H20V13H18V11H20V9H16V7H20A2,2 0 0,1 22,9V10.5A1.5,1.5 0 0,1 20.5,12A1.5,1.5 0 0,1 22,13.5V15M14,15V17H8V13C8,11.89 8.9,11 10,11H12V9H8V7H12A2,2 0 0,1 14,9V11C14,12.11 13.1,13 12,13H10V15H14Z']) 
                                @break  
                            @case(3) 
                                {{-- selección desde una lista de valores --}}
                                <div class="mt-6 mr-6">
                                    <label class="block font-bold text-base my-auto ml-1">
                                        {{$fila->cabecera}}
                                        @if ($fila->obligatorio)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    {{-- 06oct2021  --}}
                                    @php
                                        $arr_aux_roles = explode('_@@@_' , $fila->roles);
                                        if(Auth::user()->hasRole($arr_aux_roles)){
                                            $disabled___ = '';
                                            $fondo___ = '';
                                        }else{
                                            $disabled___ = 'disabled';
                                            $fondo___ = 'bg-gray-300';
                                        }
                                    @endphp  
                                    {{-- 21nov2021: Recordar que para que se muestre seleccionada
                                    por defecto la opción que está grabada en formu__..... 
                                    se tuvo que asignar dicha opción desde el mount() (ver docu) --}}
                                    <div class="relative flex w-full flex-wrap items-stretch mb-3">
                                        <select  {{$disabled___}} wire:model="arr_input_campos.{{$fila->slug}}" id="id{{$fila->slug}}" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors {{$fondo___}}">
                                            <option value="">Seleccione opción...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                            @foreach ($fila->arr_para_combobox as $fila_val)
                                                    <option  value="{{$fila_val['conse']}}">{{$fila_val['texto']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                            @endforeach
                                        </select>
                                        @error('arr_input_campos.'.$fila->slug) <span class="text-red-500">{{ $message }}</span> @enderror   
                                    </div>
                                </div>   
                                @break                                                                                
                            @case(4) 
                                {{-- casillas --}}      
                                <div class="mt-6 mr-6">
                                    <label class="block font-bold text-base my-auto ml-1">
                                        {{$fila->cabecera}}
                                        @if ($fila->obligatorio)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    {{-- 06oct2021  --}}
                                    @php
                                        $arr_aux_roles = explode('_@@@_' , $fila->roles);
                                        if(Auth::user()->hasRole($arr_aux_roles)){
                                            $disabled___ = '';
                                            $fondo___ = '';
                                        }else{
                                            $disabled___ = 'disabled';
                                            $fondo___ = 'bg-gray-300';
                                        }
                                    @endphp     
                                    @foreach($fila->arr_para_casillas as $fila_val)
                                        <input {{$disabled___}} type="checkbox" value="{{$fila_val['conse']}}" wire:model="arr_input_campos.{{$fila->slug}}.{{$fila_val['conse']}}" id="id{{$fila->slug}}" class="mr-4 {{$fondo___}}">{{$fila_val['texto']}}<br>
                                    @endforeach
                                    @error('arr_input_campos.'.$fila->slug) <span class="text-red-500">{{ $message }}</span> @enderror   
                                </div>                                                                          
                                @break  
                            @case(5) 
                                {{-- radio --}}  
                                <div class="mt-6 mr-6">
                                    <label class="block font-bold text-base my-auto ml-1">
                                        {{$fila->cabecera}}
                                        @if ($fila->obligatorio)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    {{-- 06oct2021  --}}
                                    @php
                                        $arr_aux_roles = explode('_@@@_' , $fila->roles);
                                        if(Auth::user()->hasRole($arr_aux_roles)){
                                            $disabled___ = '';
                                            $fondo___ = '';
                                        }else{
                                            $disabled___ = 'disabled';
                                            $fondo___ = 'bg-gray-300';
                                        }
                                    @endphp                                      
                                    @foreach($fila->arr_para_radios as $fila_val)
                                        <input {{$disabled___}} type="radio" value="{{$fila_val['conse']}}" wire:model="arr_input_campos.{{$fila->slug}}" id="id{{$fila->slug}}" class="mr-4 {{$fondo___}}">{{$fila_val['texto']}}<br>
                                    @endforeach
                                    @error('arr_input_campos.'.$fila->slug) <span class="text-red-500">{{ $message }}</span> @enderror   
                                </div>                                         
                                @break 
                            @case(6)
                                {{-- email  --}}
                                @include('livewire.parciales.crear_formu.inputs' , ['icono_input_d' => 'M22 6C22 4.9 21.1 4 20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6M20 6L12 11L4 6H20M20 18H4V8L12 13L20 8V18Z']) 
                                @break 
                            @case(7)
                                {{-- fecha  --}}
                                <div class="mt-6 mr-6">
                                    <label class="block font-bold text-base my-auto ml-1">
                                        {{$fila->cabecera}}
                                        @if ($fila->obligatorio)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    {{-- 06oct2021  --}}
                                    @php
                                        $arr_aux_roles = explode('_@@@_' , $fila->roles);
                                        if(Auth::user()->hasRole($arr_aux_roles)){
                                            $disabled___ = '';
                                            $fondo___ = '';
                                        }else{
                                            $disabled___ = 'disabled';
                                            $fondo___ = 'bg-gray-300';
                                        }
                                    @endphp                                     
                                    <div class="relative flex w-full flex-wrap items-stretch mb-3">
                                        <input {{$disabled___}}
                                            x-data
                                            x-ref="fec_{{$fila->slug}}"
                                            x-init="new Pikaday({
                                                field: $refs.fec_{{$fila->slug}}, 
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
                                            wire:model.lazy = "arr_input_campos.{{$fila->slug}}"
                                            id="idfec_{{$fila->slug}}" 
                                            class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors {{$fondo___}}"
                                        >
                                        <span class="absolute right-0 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md">
                                            <svg class="inline-block align-text-top w-6 h-6">
                                                <path fill="currentColor" d="M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M9,14V16H7V14H9M13,14V16H11V14H13M17,14V16H15V14H17Z" />
                                            </svg>
                                        </span>  
                                        @error('arr_input_campos.'.$fila->slug) <span class="text-red-500">{{ $message }}</span> @enderror   
                                    </div>
                                </div>
                                @break 
                            @case(8)
                                {{-- nueva sección  --}}
                                <div class="col-span-{{$tipo_producto->columnas}}"> 
                                    
                                </div>
                                <div class="mt-12 text-2xl font-bold col-span-{{$tipo_producto->columnas}}">
                                    {{$fila->cabecera}}
                                </div>                            
                                @break                                      
                            @case(9) 
                                {{-- input número decimal --}}
                                @include('livewire.parciales.crear_formu.inputs' , ['icono_input_d' => 'M4 7V9H8V11H6A2 2 0 0 0 4 13V17H10V15H6V13H8A2 2 0 0 0 10 11V9A2 2 0 0 0 8 7H4M16 7V9H18V17H20V7H16M12 15V17H14V15H12Z']) 
                                @break 
                            @case(10) 
                                {{-- selección desde una tabla  --}}
                                <div class="mt-6 mr-6">
                                    <label class="block font-bold text-base my-auto ml-1">
                                        {{$fila->cabecera}}
                                        @if ($fila->obligatorio)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    {{-- 06oct2021  --}}
                                    @php
                                        $arr_aux_roles = explode('_@@@_' , $fila->roles);
                                        if(Auth::user()->hasRole($arr_aux_roles)){
                                            $disabled___ = '';
                                            $fondo___ = '';
                                        }else{
                                            $disabled___ = 'disabled';
                                            $fondo___ = 'bg-gray-300';
                                        }
                                    @endphp  

                                    <div class="relative flex w-full flex-wrap items-stretch mb-3">
                                        <select {{$disabled___}} wire:model="arr_input_campos.{{$fila->slug}}" id="id{{$fila->slug}}" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors {{$fondo___}}">
                                            <option value="">Seleccione...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                            @foreach ($fila->arr_para_combobox as $fila_val)
                                                <option value="{{$fila_val->id}}">{{$fila_val->salida}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                            @endforeach
                                        </select>
                                        @error('arr_input_campos.'.$fila->slug) <span class="text-red-500">{{ $message }}</span> @enderror   
                                    </div>
                                </div>   
                                @break 
                            @case(11)
                                {{-- Subir archivo:  --}}
                                {{-- Petición de la selección del archivo: input type file  --}}
                                <div class="mt-6 mr-6">
                                    {{-- Cabecera:  --}}
                                    <label class="block font-bold text-base my-auto ml-1">
                                        {{$fila->cabecera}}
                                        @if ($fila->obligatorio)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>  

                                    {{-- 06oct2021  --}}
                                    @php
                                        $arr_aux_roles = explode('_@@@_' , $fila->roles);
                                        if(Auth::user()->hasRole($arr_aux_roles)){
                                            $disabled___  = '';
                                            $fondo___ = '';
                                        }else{
                                            $disabled___ = 'disabled';
                                            $fondo___ = 'bg-gray-300';
                                        }
                                    @endphp 

                                    {{-- Link Seleccionar ...., input type file, ícono, error, y loading: --}}
                                    <div class="relative flex w-full flex-wrap items-stretch mb-3">

                                        {{-- <label for="id{{$fila->slug}}_{{$contador_eliminar_adjunto}}" class="w-full flex  items-center px-4 py-1 bg-white text-blue rounded-lg shadow-lg tracking-wide  border border-blue cursor-pointer hover:bg-blue hover:text-blue-400">
                                            <span class=" {{$fondo___}} mt-1 ml-1 text-base leading-normal">Seleccione archivo...</span>
                                        </label> --}}
    
                                        {{-- <input {{$disabled___}} type="file" 
                                            wire:model="arr_input_campos.{{$fila->slug}}" 
                                            id="id{{$fila->slug}}_{{$contador_eliminar_adjunto}}" 
                                            class="hidden  bg-gray-300 "
                                        > 
                                        <span class=" {{$fondo___}} absolute right-0 shadow-lg px-4 py-1 mb-1 border-2 border-gray-200 rounded-md">
                                            <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                                            </svg>
                                        </span>                                        
                                        @error('arr_input_campos.'.$fila->slug) <span class="text-red-500">{{ $message }}</span> @enderror    --}}
                                        {{-- <div wire:loading wire:target="arr_input_campos.{{$fila->slug}}" class="text-sm text-gray-500 italic">Cargando...</div> --}}
                                    </div>
                                </div>
{{-- {{dd($arr_input_campos)}}                                 --}}

                                @foreach ($arr_input_campos as $key => $item)
                                    {{-- Por alguna razón desconocida, si el usuario 
                                    elimina un adjunto, en el arr queda un elemento vacio, 
                                    por eso se hace necesario el siguiente if:  --}}
                                    @if($item !== null)
                                        @if($key == $fila->slug)
                                            {{-- Es el que corresponde al archivo dado:  --}}
                                            @php
                                                // 20dic2021
                                                // Verificar si es una imagen, hacerlo depende
                                                // del tipo de contenido de $item: Si es una 
                                                // cadena hay que buscar la extensión, Si es 
                                                // un objeto se usa getMimeType: 
                                                if(is_string($item)){
                                                    // Verificar si es una imagen, usando la extensión: 
                                                    $ultimo_punto = strripos($item, ".");
                                                    if($ultimo_punto !== false){
                                                        $extension = substr($item , $ultimo_punto +1 );
                                                        $arr_extensiones = ['jpg','jpge','png','bmp','gif','tiff','svg'];
                                                        if(in_array($extension , $arr_extensiones)){
                                                            $url = str_replace('/home/u306294386/domains/tavohen.site/public_html/markka-pruebas22/storage/' , 'https://markka-pruebas22.tavohen.site/storage/' , $arr_input_campos[$fila->slug]);     
                                                            $this->photoStatus =  true;
                                                        }else{
                                                            $this->photoStatus =  false;
                                                        }
                                                    }
                                                }else{
                                                    // Verificar si es una imagen con getMimeType: 
                                                    if(substr($item->getMimeType(),0,5) == 'image'){  
                                                        $url = $item->temporaryUrl();
                                                        $this->photoStatus =  true;
                                                    }else{
                                                        $this->photoStatus =  false;
                                                    }                                                    
                                                }
                                            @endphp

                                            {{-- Para permitir cambiar y eliminar el archivo:  --}} 
                                            @php
                                                $boton_cambiar_subir_archivo = '<button type="button" wire:click="cambiar_archivo(\''.$fila->slug.'\',\''.$fila->cabecera.'\')"  class="relative bottom-7 bg-white rounded-2xl text-blue-500  hover:bg-gray-300  hover:text-blue-700">
                                                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2 12A10 10 0 1 0 12 2A10 10 0 0 0 2 12M15.6 13.72A4 4 0 0 0 16 12A4 4 0 0 0 12 8V10L8.88 7L12 4V6A6 6 0 0 1 18 12A5.9 5.9 0 0 1 17.07 15.19M6 12A5.9 5.9 0 0 1 6.93 8.81L8.4 10.28A4 4 0 0 0 8 12A4 4 0 0 0 12 16V14L15 17L12 20V18A6 6 0 0 1 6 12Z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>'; 

                                                $boton_eliminar_subir_archivo = '<button type="button" wire:click="reset_archivo(\''.$fila->slug.'\')"  class="relative bottom-7 bg-white rounded-2xl text-red-500  hover:bg-gray-300  hover:text-red-700">
                                                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>'; 
                                            @endphp
            
                                            @if($this->photoStatus) 
                                                <span>
                                                    <img src="{{ $url }}" class="border rounded w-48 h-20">
                                                    {!! $boton_cambiar_subir_archivo !!}
                                                    {!! $boton_eliminar_subir_archivo !!}
                                                </span>
                                            @else
                                                <span>
                                                    {{-- Es un formato distinto a imagen  --}}
                                                    <div class="mr-6 bg-gray-200 px-2  border border-gray-300 rounded w-auto h-20">
                                                        {{-- Archivo seleccionado:<br>{{$item->getClientOriginalName()}}<br><br> --}}
                                                        {{-- 27nov2021 
                                                            Se cambió el determinar el nombre del archivo, recordar que en 
                                                            la vista crear-formu.blade.php se usaba getClientOriginalName, el cual 
                                                            no funcionó hoy y entonces se usa: basename (php puro) 
                                                        --}}
                                                        Archivo seleccionado:<br>{{basename($arr_input_campos[$fila->slug])}}<br><br>
                                                    </div>
                                                    {!! $boton_cambiar_subir_archivo !!}
                                                    {!! $boton_eliminar_subir_archivo !!}
                                                </span>                                            
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                                @break    
                            @case(12)
                                {{-- es un campo multivariable  --}}
                                <div class="mt-6 mr-6">
                                    <label class="block font-bold text-base my-auto ml-1">
                                        {{$fila->cabecera}}
                                        @if ($fila->obligatorio)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    @php
                                        $arr_aux_roles = explode('_@@@_' , $fila->roles);
                                        if(Auth::user()->hasRole($arr_aux_roles)){
                                            $disabled___ = '';
                                            $fondo___ = '';
                                        }else{
                                            $disabled___ = 'disabled';
                                            $fondo___ = 'bg-gray-300';
                                        }
                                    @endphp  

                                    <div class="relative flex w-full flex-wrap items-stretch mb-3  {{$fondo___}}">
                                        @php
                                            // Calcular cantidad de filas con info que tiene el multivariable:                                            
                                            $bool_tiene_filas = array_search($fila->id, array_column($arr_multivbles_canti_filas , 'campo_detalle_id')); 
                                            if($bool_tiene_filas === false){
                                                $canti_filas = " ";
                                            }else{
                                                $canti_filas = $arr_multivbles_canti_filas[$bool_tiene_filas]['canti_filas'];
                                                // Para que en los que tienen filas mínimas CERO no muestre el cero:
                                                if ($canti_filas >= 1) {
                                                    $canti_filas = " ( " . $canti_filas . " ) ";
                                                }else{
                                                    $canti_filas = " ";
                                                }
                                            }
                                        @endphp
                                        <button {{$disabled___}}  wire:click="llenar_multivbles({{$fila->id}})"
                                            id="id{{$fila->slug}}" 
                                            type="button"
                                            class="w-full bg-gray-600 bg-opacity-5   shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                                        >
                                            Click para diligenciar multivariable
                                            <span  class="text-blue-500 font-bold">
                                                {{$canti_filas}}
                                            </span>
                                        </button>
                                    </div>
                                    @error('arr_input_campos.'.$fila->slug) <span class="text-red-500">{{ $message }}</span> @enderror   
                                </div>                                    
                                @break;                                    
                            @default
                                @break     
                        @endswitch                                
                    @endforeach
                </div>                        
            </div>
        </form>
    </div>

    {{-- MODAL para pedir info de campo multivariable --}}
    <x-jet-dialog-modal wire:model="modal_visible_info_multivariable"  maxWidth="4xl">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    {{-- <span class="text-gray-500 text-4xl">Campo multivariable: {{$cabecera_multivariable}}</span>        --}}
                    <span class="text-gray-500 text-4xl">Campo multivariable: </span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            {{-- Verificar que $arr_formu_detalles_multivariable no sea null, parece que
            esto se puede dar o por ciclos del livewire , o por campos que no tengan listados 
            desde tablas: --}}
            @if($obj_formu_detalles_multivble_1campo !== null)
                @php 
                    // calcular el ancho, de acuerdo al número de columnas: 
                    $num_columnas = count($obj_formu_detalles_multivble_1campo); 
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
                <form  style="max-height: 450px; overflow-y: auto;" x-on:click.away="$wire.cerrar_modal_info_multivariable()" wire:submit.prevent="submit_info_multivariable()">
                    <div class="mt-3"> 
                        {{-- Cabeceras de cada columna  --}}
                        <div class="flex">
                            @foreach($obj_formu_detalles_multivble_1campo as $una_col)
                                <div class="{{$ancho}}  mr-2">
                                    {{$una_col->cabecera}}
                                    <span class="text-red-500 ml-1">*</span>
                                </div>
                            @endforeach
                        </div>                    
                        @for ($i = 0; $i < $cantidad_filas_1campo; $i++)
                            <div class="flex">
                                @foreach ($obj_formu_detalles_multivble_1campo as $key => $fila_contenido)
                                    @php
                                        $arr_aux_roles = explode('_@@@_' , $fila_contenido['roles']);
                                        if(Auth::user()->hasRole($arr_aux_roles)){
                                            $disabled___ = '';
                                            $fondo___ = '';
                                        }else{
                                            $disabled___ = 'disabled';
                                            $fondo___ = 'bg-gray-300';
                                        }
                                    @endphp   
                                    @if($fila_contenido['origen_tipo'] == 0)
                                        <div class="{{$ancho}} mr-2">
                                            <input {{$disabled___}} type="text" wire:model="arr_multivbles_input_1campo.{{$i}}.{{$key}}"  class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors {{$fondo___}}">
                                        </div> 
                                    @elseif($fila_contenido['origen_tipo'] == 1) 
                                        @php $arr_lista_valores = explode('_@@@_' , $fila_contenido['origen_datos']); @endphp
                                        <div class="{{$ancho}} mr-2">
                                            <select {{$disabled___}}  wire:model="arr_multivbles_input_1campo.{{$i}}.{{$key}}"  class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors {{$fondo___}}">
                                                <option value="">&nbsp;</option>
                                                @foreach ($arr_lista_valores as $ele)
                                                    <option value="{{$ele}}">{{$ele}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                @endforeach
                                            </select>                                        
                                        </div> 
                                    @elseif($fila_contenido['origen_tipo'] == 2)
                                        @php $arr_lista_tabla_fila = $arr_lista_tabla_multivariable[$key]; @endphp
                                        <div class="{{$ancho}} mr-2">
                                            <select {{$disabled___}}  wire:model="arr_multivbles_input_1campo.{{$i}}.{{$key}}"  class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors {{$fondo___}}">
                                                <option value="">&nbsp;</option>
                                                @foreach ($arr_lista_tabla_fila as $fila_val)
                                                    {{-- Verificar que $fila_val->id esté definido, parece que puede estar 
                                                    undefined o por ciclos del livewire : --}}            
                                                    @if (isset($fila_val->id))
                                                        <option value="{{$fila_val->id}}">{{$fila_val->salida}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                    @endif
                                                @endforeach
                                            </select>   
                                        </div>  
                                    @elseif($fila_contenido['origen_tipo'] == 3)
                                        <div class="{{$ancho}} mr-2">
                                            <textarea  {{$disabled___}} wire:model="arr_multivbles_input_1campo.{{$i}}.{{$key}}"   class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors {{$fondo___}}"></textarea>
                                        </div>                                                                                                                                                          
                                    @endif
                                @endforeach 

                                {{-- si se trata de una fila agregada por el usuario, debe 
                                mostrar el botón X (eliminar) --}}
                                @if ($i >= $filas_min_1campo)
                                    <div class="w-1/12 mr-2">
                                        <button type="button" wire:click="eliminar_fila_multivariable({{$i}})"  class=" bg-white rounded-2xl text-red-500  hover:bg-gray-300  hover:text-red-700">
                                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>  
                                @else 
                                    <div class="w-1/12 mr-2">

                                    </div>   
                                @endif 
                            </div>
                        @endfor
                        {{-- Mostrar errores de $this->validate():  --}}
                        <br><br>                       
                        @error('arr_multivbles_input_1campo.*.*')
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror                     

                        {{-- los tres botones al final del modal:  --}}
                        <div>
                            <div class="flex mb-4" >
                                <div class="w-4/12 ml-4 ">
                                    {{-- botón agregar fila: --}}
                                    @php
                                        // Si las filas_act_multivariable han sobrepasado las 
                                        // filas_max_multivariable (debido a 
                                        // que el usuario ha agregado muchas filas), no debe
                                        // permitir agregar mas filas:
                                        $deshabilitar = "";
                                        if($cantidad_filas_1campo >= $filas_max_1campo){
                                            $deshabilitar = "disabled";
                                        }
                                    @endphp

                                    <button type="button" {{$deshabilitar}} wire:click="agregar_fila_multivariable" class="w-full mt-4 bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3">
                                        <svg class="inline-block align-bottom  text-white fill-current w-6 h-6 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/></svg>
                                        <span class="align-text-bottom font-semibold">{{($deshabilitar == "")?'Agregar fila':'No disponible'}}</span>
                                    </button>
                                </div>

                                <div class="w-4/12 ml-4 ">
                                    {{-- botón grabar: --}}
                                    <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3">
                                        <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path fill="currentColor" d="M14,2A8,8 0 0,0 6,10A8,8 0 0,0 14,18A8,8 0 0,0 22,10H20C20,13.32 17.32,16 14,16A6,6 0 0,1 8,10A6,6 0 0,1 14,4C14.43,4 14.86,4.05 15.27,4.14L16.88,2.54C15.96,2.18 15,2 14,2M20.59,3.58L14,10.17L11.62,7.79L10.21,9.21L14,13L22,5M4.93,5.82C3.08,7.34 2,9.61 2,12A8,8 0 0,0 10,20C10.64,20 11.27,19.92 11.88,19.77C10.12,19.38 8.5,18.5 7.17,17.29C5.22,16.25 4,14.21 4,12C4,11.7 4.03,11.41 4.07,11.11C4.03,10.74 4,10.37 4,10C4,8.56 4.32,7.13 4.93,5.82Z" />
                                        </svg>
                                        <span class="align-text-bottom font-semibold">Grabar datos</span>
                                    </button>
                                </div>
                        
                                <div class="w-4/12 ml-4 pr-4">
                                    {{-- botón cancelar : --}}
                                    <button type="button"  wire:click="cerrar_modal_info_multivariable" class="w-full mt-4 bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3">
                                        <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="align-text-bottom font-semibold">Cancelar</span>
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
    {{-- FIN DEL MODAL multivariable --}}

    {{-- MODAL para cambiar campo tipo subir archivo --}}
    <x-jet-dialog-modal wire:model="modal_visible_cambiar_archivo" maxWidth="4xl">  
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    {{-- <span class="text-gray-500 text-4xl">Campo multivariable: {{$cabecera_multivariable}}</span>        --}}
                    <span class="text-gray-500 text-4xl">Cambiar el archivo de: {{$cabecera_campo_para_modal_cambiar_archivo}}</span>       
                </div>
            </div>
        </x-slot>

        <x-slot name="content">    
            {{-- debido a que el input type file no funcionó con wire_modal  (había 
            que dar click varias veces al button submit para que reconociera el wire:modal)
            entonces no fue pasado con wire_model sino usando getElementById (lo que implica
            también el uso de wire:ignore y wire:key): --}}
            {{-- <form wire:submit.prevent="submit_cambiar_archivo(document.getElementById('idinput_file').value)" enctype="multipart/form-data"> --}}
            {{-- <form wire:submit.prevent="submit_cambiar_archivo()" enctype="multipart/form-data"> --}}
                {{-- <div wire:ignore wire:key="a" >
                    <input type="file" id="idinput_file">   
                </div> --}}
                    <input type="file"  wire:model="cambiar_un_solo_archivo">   

                
                {{-- Botones:  --}}
                <div class="flex">
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" wire:click="submit_cambiar_archivo()" class="w-full bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold"> Cambiar archivo</button> 
                        @if (session()->has('mensaje_cambiar_archivo'))
                            <div class="alert alert-success text-red-500">
                                {{ session('mensaje_cambiar_archivo') }}
                            </div> 
                        @endif
                    </div>

                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" wire:click="cancelar_cambiar_archivo()" class="w-full bg-red-600 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold">
                             Cancelar
                        </button>
                    </div>

                </div>                

        </x-slot>
        
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>    
    {{-- FIN DEL MODAL para cambiar campo tipo subir archivo --}}    
</div>
    