<div>
    <style>
        /* tool tips para los botones que agregan los elementos html:  */
        .icon-texto:hover:after {
            content: "Permite agregar un elemento que pida caracteres alfabéticos.";
            background: lightblue;
            padding: 4px 8px;
            position: absolute;
            left: 22%;
            top: 22%;
            border-radius: 5px;  
            /* white-space: nowrap;   */
        }
        .icon-numero:hover:after {
            content: "Permite agregar un elemento que pida un número.";
            background: lightblue;
            padding: 4px 8px;
            position: absolute;
            left: 29%;
            top: 22%;
            border-radius: 5px;  
        }
        .icon-lista:hover:after {
            content: "Permite agregar una lista de selección.";
            background: lightblue;
            padding: 4px 8px;
            position: absolute;
            left: 36%;
            top: 22%;
            border-radius: 5px;  
        }        
        .icon-casillas:hover:after {
            content: "Permite agregar un elemento formado por casillas de verificación.";
            background: lightblue;
            padding: 4px 8px;
            position: absolute;
            left: 43%;
            top: 22%;
            border-radius: 5px;  
        }
        .icon-radio:hover:after {
            content: "Permite agregar un elemento para escoger una entre varias opciones.";
            background: lightblue;
            padding: 4px 8px;
            position: absolute;
            left: 50%;
            top: 22%;
            border-radius: 5px;  
        }
        .icon-email:hover:after {
            content: "Permite agregar un elemento que pida un email válido.";
            background: lightblue;
            padding: 4px 8px;
            position: absolute;
            left: 57%;
            top: 22%;
            border-radius: 5px;  
        }
        .icon-fecha:hover:after {
            content: "Permite agregar un elemento que pida una fecha.";
            background: lightblue;
            padding: 4px 8px;
            position: absolute;
            left: 64%;
            top: 22%;
            border-radius: 5px;  
        }
    </style>
    @livewire('menu-own')

    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
        {{-- título  --}}
        <div class="">
            <h1 class="font-bold text-xl uppercase">Generar formulario de etiquetas</h1>
        </div>

        {{-- botones  --}}
        <div class="flex  my-4">
            {{-- botón para la configuración general  --}}
            <button type="button"  wire:click="mostrar_modal_gral('{{$tipo_producto->titulo}}' , '{{$tipo_producto->subtitulo}}' , '{{$tipo_producto->columnas}}')"
                class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                <svg class="inline-block align-text-top w-6 h-6" >
                    <path fill="currentColor" d="M6 2C4.89 2 4 2.9 4 4V20C4 21.11 4.89 22 6 22H12V20H6V4H13V9H18V12H20V8L14 2M18 14C17.87 14 17.76 14.09 17.74 14.21L17.55 15.53C17.25 15.66 16.96 15.82 16.7 16L15.46 15.5C15.35 15.5 15.22 15.5 15.15 15.63L14.15 17.36C14.09 17.47 14.11 17.6 14.21 17.68L15.27 18.5C15.25 18.67 15.24 18.83 15.24 19C15.24 19.17 15.25 19.33 15.27 19.5L14.21 20.32C14.12 20.4 14.09 20.53 14.15 20.64L15.15 22.37C15.21 22.5 15.34 22.5 15.46 22.5L16.7 22C16.96 22.18 17.24 22.35 17.55 22.47L17.74 23.79C17.76 23.91 17.86 24 18 24H20C20.11 24 20.22 23.91 20.24 23.79L20.43 22.47C20.73 22.34 21 22.18 21.27 22L22.5 22.5C22.63 22.5 22.76 22.5 22.83 22.37L23.83 20.64C23.89 20.53 23.86 20.4 23.77 20.32L22.7 19.5C22.72 19.33 22.74 19.17 22.74 19C22.74 18.83 22.73 18.67 22.7 18.5L23.76 17.68C23.85 17.6 23.88 17.47 23.82 17.36L22.82 15.63C22.76 15.5 22.63 15.5 22.5 15.5L21.27 16C21 15.82 20.73 15.65 20.42 15.53L20.23 14.21C20.22 14.09 20.11 14 20 14M19 17.5C19.83 17.5 20.5 18.17 20.5 19C20.5 19.83 19.83 20.5 19 20.5C18.16 20.5 17.5 19.83 17.5 19C17.5 18.17 18.17 17.5 19 17.5Z" />
                </svg>
                {{-- <svg class="inline-block align-text-top w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> --}}
                {{-- <svg class="inline-block align-text-top w-6 h-6" viewBox="0 0 24 24"><path fill="currentColor" d="M12,3C8.59,3 5.69,4.07 4.54,5.57L9.79,10.82C10.5,10.93 11.22,11 12,11C16.42,11 20,9.21 20,7C20,4.79 16.42,3 12,3M3.92,7.08L2.5,8.5L5,11H0V13H5L2.5,15.5L3.92,16.92L8.84,12M20,9C20,11.21 16.42,13 12,13C11.34,13 10.7,12.95 10.09,12.87L7.62,15.34C8.88,15.75 10.38,16 12,16C16.42,16 20,14.21 20,12M20,14C20,16.21 16.42,18 12,18C9.72,18 7.67,17.5 6.21,16.75L4.53,18.43C5.68,19.93 8.59,21 12,21C16.42,21 20,19.21 20,17" /></svg> --}}
                Configuración general
            </button>

            {{-- input text texto  --}}
            <button type="button"  wire:click="mostrar_modal_texto()" 
                class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="currentColor" d="M6,11A2,2 0 0,1 8,13V17H4A2,2 0 0,1 2,15V13A2,2 0 0,1 4,11H6M4,13V15H6V13H4M20,13V15H22V17H20A2,2 0 0,1 18,15V13A2,2 0 0,1 20,11H22V13H20M12,7V11H14A2,2 0 0,1 16,13V15A2,2 0 0,1 14,17H12A2,2 0 0,1 10,15V7H12M12,15H14V13H12V15Z" />
                </svg>
            </button>
            <div class="icon-texto"  >
                <svg class="inline-block align-text-top w-6 h-6">
                {{-- <svg style="width:24px;height:24px" viewBox="0 0 24 24"> --}}
                    <path fill="red" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                </svg>
            </div>

            {{-- input text number  --}}
            <button type="button"  wire:click="mostrar_modal_gral()" 
                class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="currentColor" d="M4,17V9H2V7H6V17H4M22,15C22,16.11 21.1,17 20,17H16V15H20V13H18V11H20V9H16V7H20A2,2 0 0,1 22,9V10.5A1.5,1.5 0 0,1 20.5,12A1.5,1.5 0 0,1 22,13.5V15M14,15V17H8V13C8,11.89 8.9,11 10,11H12V9H8V7H12A2,2 0 0,1 14,9V11C14,12.11 13.1,13 12,13H10V15H14Z" />
                </svg>
            </button>
            <div class="icon-numero"  >
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="red" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                </svg>
            </div>

            {{-- lista de selección  --}}
            <button type="button"  wire:click="mostrar_modal_gral()" 
                class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="currentColor" d="M15 5H18L16.5 7L15 5M5 2H19C20.11 2 21 2.9 21 4V20C21 21.11 20.11 22 19 22H5C3.9 22 3 21.11 3 20V4C3 2.9 3.9 2 5 2M5 4V8H19V4H5M5 20H19V10H5V20M7 12H17V14H7V12M7 16H17V18H7V16Z" />
                </svg>
            </button>
            <div class="icon-lista"  >
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="red" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                </svg>
            </div>

            {{-- casillas de verificación  --}}
            <button type="button"  wire:click="mostrar_modal_gral()" 
                class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="currentColor" d="M4 13C2.89 13 2 13.89 2 15V19C2 20.11 2.89 21 4 21H8C9.11 21 10 20.11 10 19V15C10 13.89 9.11 13 8 13M8.2 14.5L9.26 15.55L5.27 19.5L2.74 16.95L3.81 15.9L5.28 17.39M4 3C2.89 3 2 3.89 2 5V9C2 10.11 2.89 11 4 11H8C9.11 11 10 10.11 10 9V5C10 3.89 9.11 3 8 3M4 5H8V9H4M12 5H22V7H12M12 19V17H22V19M12 11H22V13H12Z" />
                </svg>
            </button>
            <div class="icon-casillas"  >
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="red" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                </svg>
            </div>

            {{-- radio button  --}}
            <button type="button"  wire:click="mostrar_modal_gral()" 
                class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="currentColor" d="M6 13C3.79 13 2 14.79 2 17S3.79 21 6 21 10 19.21 10 17 8.21 13 6 13M6 19C4.9 19 4 18.1 4 17S4.9 15 6 15 8 15.9 8 17 7.1 19 6 19M6 3C3.79 3 2 4.79 2 7S3.79 11 6 11 10 9.21 10 7 8.21 3 6 3M12 5H22V7H12V5M12 19V17H22V19H12M12 11H22V13H12V11Z" />
                </svg>
            </button>
            <div class="icon-radio"  >
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="red" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                </svg>
            </div>

            {{-- email  --}}
            <button type="button"  wire:click="mostrar_modal_gral()" 
                class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="currentColor" d="M21,13.34C20.37,13.12 19.7,13 19,13A6,6 0 0,0 13,19C13,19.34 13.03,19.67 13.08,20H3A2,2 0 0,1 1,18V6C1,4.89 1.89,4 3,4H19A2,2 0 0,1 21,6V13.34M23.5,17L18.5,22L15,18.5L16.5,17L18.5,19L22,15.5L23.5,17M3,6V8L11,13L19,8V6L11,11L3,6Z" />
                </svg>
            </button>
            <div class="icon-email"  >
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="red" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                </svg>
            </div>

            {{-- fecha  --}}
            <button type="button"  wire:click="mostrar_modal_gral()" 
                class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="currentColor" d="M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M9,14V16H7V14H9M13,14V16H11V14H13M17,14V16H15V14H17Z" />
                </svg>
            </button>
            <div class="icon-fecha"  >
                <svg class="inline-block align-text-top w-6 h-6">
                    <path fill="red" d="M15.07,11.25L14.17,12.17C13.45,12.89 13,13.5 13,15H11V14.5C11,13.39 11.45,12.39 12.17,11.67L13.41,10.41C13.78,10.05 14,9.55 14,9C14,7.89 13.1,7 12,7A2,2 0 0,0 10,9H8A4,4 0 0,1 12,5A4,4 0 0,1 16,9C16,9.88 15.64,10.67 15.07,11.25M13,19H11V17H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12C22,6.47 17.5,2 12,2Z" />
                </svg>
            </div>
        </div>

        {{-- Vista preliminar  --}}
        <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
            {{-- título de la vista preliminar  --}}
            {{-- <div class=""> --}}
                <h1 class="font-bold text-4xl text-center ">{{$tipo_producto->titulo}}</h1> 
                <h1 class="font-bold text-2xl text-center ">{{$tipo_producto->subtitulo}}</h1> 
                @foreach($elementos_html->chunk($tipo_producto->columnas) as $elemento)
                    <div class="flex">

                        @foreach ($elemento as $fila)
                            <div class="w-1/2">

                                <label class="w-2/12 font-bold text-base my-auto mb-2 ml-1">
                                    {{$fila->cabecera}}
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <div class="w-10/12">
                                    <input type="text" wire:model="preliminar_{{$fila->slug}}" id="id{{$fila->slug}}" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                </div>
                            </div> 
                        @endforeach
                    </div>

                @endforeach
            {{-- </div> --}}
        </div>
    </div>

    {{-- MODAL para pedir la configuración general --}}
    <x-jet-dialog-modal wire:model="modal_visible_gral">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500">CONFIGURACIÓN GENERAL.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <form  x-on:click.away="modal_visible_gral = false" wire:submit.prevent="submit_gral()">

                <div class="mt-3 text-center ">
                    
                    @if (session()->has('message'))
                        <div class="alert alert-success text-red-500">
                            {{ session('message') }}
                        </div>                            
                    @endif

                    {{-- Título en Configuraciones generales --}}
                    <div class="flex mb-4">
                        <label class="w-2/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Título:</label>
                        <div class="w-10/12">
                            <input type="text" wire:model="gral_titulo" id="idtitulo" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                    </div>  

                    {{-- Subtítulo en Configuraciones generales --}}
                    <div class="flex mb-4">
                        <label class="w-2/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Subtítulo:</label>
                        <div class="w-10/12">
                            <input type="text" wire:model="gral_subtitulo" id="idsubtitulo" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                    </div>   

                    {{-- Número de columnas --}}
                    <div class="flex mb-4">
                        <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Número de columnas:</label>
                        <div class="w-8/12">
                            <input type="number" wire:model="gral_columnas" id="idcolumnas" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                    </div>   
                    @error('gral_columnas')
                        <span  class="text-red-500">{{ $message }}</span> 
                    @enderror   
                    @error('texto_cabecera')
                        <span  class="text-red-500">{{ $message }}</span> 
                    @enderror  
                    {{-- botones  --}}
                    <div class="flex mb-4" >
                        <div class="w-1/4 ml-4 ">
                            {{-- botón grabar: --}}
                            <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3">
                                <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor" d="M14,2A8,8 0 0,0 6,10A8,8 0 0,0 14,18A8,8 0 0,0 22,10H20C20,13.32 17.32,16 14,16A6,6 0 0,1 8,10A6,6 0 0,1 14,4C14.43,4 14.86,4.05 15.27,4.14L16.88,2.54C15.96,2.18 15,2 14,2M20.59,3.58L14,10.17L11.62,7.79L10.21,9.21L14,13L22,5M4.93,5.82C3.08,7.34 2,9.61 2,12A8,8 0 0,0 10,20C10.64,20 11.27,19.92 11.88,19.77C10.12,19.38 8.5,18.5 7.17,17.29C5.22,16.25 4,14.21 4,12C4,11.7 4.03,11.41 4.07,11.11C4.03,10.74 4,10.37 4,10C4,8.56 4.32,7.13 4.93,5.82Z" />
                                </svg>
                                
                                <span class="align-text-bottom font-semibold">Aplicar</span>
                            </button>
                        </div>
            
                        <div class="w-1/4 ml-4 pr-4">
                            {{-- botón cancelar : --}}
                            <button type="button"  wire:click="$set('modal_visible_gral',false)" class="w-full mt-4 bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3">
                                <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="align-text-bottom font-semibold">Cancelar</span>
                            </button>
                        </div>
                    </div>


                </div>
            </form>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>

    {{-- MODAL para pedir entrada de texto --}}
    <x-jet-dialog-modal wire:model="modal_visible_texto">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar entrada de texto.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <form  x-on:click.away="modal_visible_texto = false" wire:submit.prevent="submit_texto()">

                {{-- <div class="mt-3 text-center "> --}}
                <div class="mt-3">
                    
                    {{-- @if (session()->has('message'))
                        <div class="alert alert-success text-red-500">
                            {{ session('message') }}
                        </div>                            
                    @endif --}}

                    {{-- Cabecera en: texto --}}
                    <div class="flex mb-4">
                        <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Cabecera:</label>
                        <div class="w-8/12">
                            <input type="text" wire:model="texto_cabecera"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                    </div>  
                    @error('texto_cabecera')
                        <span  class="text-red-500">{{ $message }}</span> 
                    @enderror  

                    {{-- Obligatorio en: texto --}}
                    <div class="flex mb-4">
                        <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">¿Debe ser obligatorio?:</label>
                        <div class="w-8/12">
                            <input type="radio" wire:model="texto_obligatorio" value="1"   ><span class="ml-2 text-gray-700">Sí</span>
                            <input type="radio" wire:model="texto_obligatorio" value="0"   class="ml-6"   ><span class="ml-2 text-gray-700">No</span>
                        </div>
                    </div>  
                    @error('texto_obligatorio')
                        <span  class="text-red-500">{{ $message }}</span> 
                    @enderror   

                    {{-- Longitud máxima en: texto --}}
                    <div class="flex mb-4">
                        <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Longitud máxima:</label>
                        <div class="w-8/12">
                            <input type="number" wire:model="texto_longitud_max"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                    </div>  
                    @error('texto_longitud_max')
                        <span  class="text-red-500">{{ $message }}</span> 
                    @enderror  
 



                    {{-- botones  --}}
                    <div class="flex mb-4" >
                        <div class="w-1/2 ml-4 ">
                            {{-- botón grabar: --}}
                            <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3">
                                <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor" d="M14,2A8,8 0 0,0 6,10A8,8 0 0,0 14,18A8,8 0 0,0 22,10H20C20,13.32 17.32,16 14,16A6,6 0 0,1 8,10A6,6 0 0,1 14,4C14.43,4 14.86,4.05 15.27,4.14L16.88,2.54C15.96,2.18 15,2 14,2M20.59,3.58L14,10.17L11.62,7.79L10.21,9.21L14,13L22,5M4.93,5.82C3.08,7.34 2,9.61 2,12A8,8 0 0,0 10,20C10.64,20 11.27,19.92 11.88,19.77C10.12,19.38 8.5,18.5 7.17,17.29C5.22,16.25 4,14.21 4,12C4,11.7 4.03,11.41 4.07,11.11C4.03,10.74 4,10.37 4,10C4,8.56 4.32,7.13 4.93,5.82Z" />
                                </svg>
                                <span class="align-text-bottom font-semibold">Crear elemento</span>
                            </button>
                        </div>
            
                        <div class="w-1/2 ml-4 pr-4">
                            {{-- botón cancelar : --}}
                            <button type="button"  wire:click="$set('modal_visible_texto',false)" class="w-full mt-4 bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3">
                                <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="align-text-bottom font-semibold">Cancelar</span>
                            </button>
                        </div>
                    </div>


                </div>
            </form>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>

</div>

