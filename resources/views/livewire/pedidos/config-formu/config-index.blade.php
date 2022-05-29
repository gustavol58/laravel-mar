<div>
    @livewire('menu-own')

    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
        {{-- título  --}}
        <div class="">
            <h1 class="font-bold text-xl">{{$mostrar_titulo}}</h1>
        </div>

        {{-- botones  --}}
        <div class="flex  my-4 ">
            {{-- botón para la configuración general  --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_gral('{{$tipo_producto->titulo}}' , '{{$tipo_producto->subtitulo}}' , '{{$tipo_producto->columnas}}')"
                    class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6" >
                        <path fill="currentColor" d="M21.7 18.6V17.6L22.8 16.8C22.9 16.7 23 16.6 22.9 16.5L21.9 14.8C21.9 14.7 21.7 14.7 21.6 14.7L20.4 15.2C20.1 15 19.8 14.8 19.5 14.7L19.3 13.4C19.3 13.3 19.2 13.2 19.1 13.2H17.1C16.9 13.2 16.8 13.3 16.8 13.4L16.6 14.7C16.3 14.9 16.1 15 15.8 15.2L14.6 14.7C14.5 14.7 14.4 14.7 14.3 14.8L13.3 16.5C13.3 16.6 13.3 16.7 13.4 16.8L14.5 17.6V18.6L13.4 19.4C13.3 19.5 13.2 19.6 13.3 19.7L14.3 21.4C14.4 21.5 14.5 21.5 14.6 21.5L15.8 21C16 21.2 16.3 21.4 16.6 21.5L16.8 22.8C16.9 22.9 17 23 17.1 23H19.1C19.2 23 19.3 22.9 19.3 22.8L19.5 21.5C19.8 21.3 20 21.2 20.3 21L21.5 21.4C21.6 21.4 21.7 21.4 21.8 21.3L22.8 19.6C22.9 19.5 22.9 19.4 22.8 19.4L21.7 18.6M18 19.5C17.2 19.5 16.5 18.8 16.5 18S17.2 16.5 18 16.5 19.5 17.2 19.5 18 18.8 19.5 18 19.5M11.29 20H5C3.89 20 3 19.1 3 18V6C3 4.89 3.9 4 5 4H19C20.11 4 21 4.9 21 6V11.68C20.38 11.39 19.71 11.18 19 11.08V8H5V18H11C11 18.7 11.11 19.37 11.29 20Z" />
                    </svg>
                </button>
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Configuración general: Título, subtítulo y número de columnas del formulario.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>
            </div>            

            {{-- nueva sección en el formulario  --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_seccion()"
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M13,17A1,1 0 0,0 14,16A1,1 0 0,0 13,15A1,1 0 0,0 12,16A1,1 0 0,0 13,17M13,13A1,1 0 0,0 14,12A1,1 0 0,0 13,11A1,1 0 0,0 12,12A1,1 0 0,0 13,13M13,9A1,1 0 0,0 14,8A1,1 0 0,0 13,7A1,1 0 0,0 12,8A1,1 0 0,0 13,9M17,12.5A0.5,0.5 0 0,0 17.5,12A0.5,0.5 0 0,0 17,11.5A0.5,0.5 0 0,0 16.5,12A0.5,0.5 0 0,0 17,12.5M17,8.5A0.5,0.5 0 0,0 17.5,8A0.5,0.5 0 0,0 17,7.5A0.5,0.5 0 0,0 16.5,8A0.5,0.5 0 0,0 17,8.5M3,3V5H21V3M17,16.5A0.5,0.5 0 0,0 17.5,16A0.5,0.5 0 0,0 17,15.5A0.5,0.5 0 0,0 16.5,16A0.5,0.5 0 0,0 17,16.5M9,17A1,1 0 0,0 10,16A1,1 0 0,0 9,15A1,1 0 0,0 8,16A1,1 0 0,0 9,17M5,13.5A1.5,1.5 0 0,0 6.5,12A1.5,1.5 0 0,0 5,10.5A1.5,1.5 0 0,0 3.5,12A1.5,1.5 0 0,0 5,13.5M5,9.5A1.5,1.5 0 0,0 6.5,8A1.5,1.5 0 0,0 5,6.5A1.5,1.5 0 0,0 3.5,8A1.5,1.5 0 0,0 5,9.5M3,21H21V19H3M9,9A1,1 0 0,0 10,8A1,1 0 0,0 9,7A1,1 0 0,0 8,8A1,1 0 0,0 9,9M9,13A1,1 0 0,0 10,12A1,1 0 0,0 9,11A1,1 0 0,0 8,12A1,1 0 0,0 9,13M5,17.5A1.5,1.5 0 0,0 6.5,16A1.5,1.5 0 0,0 5,14.5A1.5,1.5 0 0,0 3.5,16A1.5,1.5 0 0,0 5,17.5Z" />
                    </svg>
                </button>
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Insertar una nueva sección en el formulario.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>
            </div>

            {{-- input text texto  --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_texto()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6" >
                        <path fill="currentColor" d="M18.5,4L19.66,8.35L18.7,8.61C18.25,7.74 17.79,6.87 17.26,6.43C16.73,6 16.11,6 15.5,6H13V16.5C13,17 13,17.5 13.33,17.75C13.67,18 14.33,18 15,18V19H9V18C9.67,18 10.33,18 10.67,17.75C11,17.5 11,17 11,16.5V6H8.5C7.89,6 7.27,6 6.74,6.43C6.21,6.87 5.75,7.74 5.3,8.61L4.34,8.35L5.5,4H18.5Z" />
                    </svg>                      
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Agregar un elemento que pida caracteres alfabéticos.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>                            
            </div>            

            {{-- input text number  --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_numero()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M4,17V9H2V7H6V17H4M22,15C22,16.11 21.1,17 20,17H16V15H20V13H18V11H20V9H16V7H20A2,2 0 0,1 22,9V10.5A1.5,1.5 0 0,1 20.5,12A1.5,1.5 0 0,1 22,13.5V15M14,15V17H8V13C8,11.89 8.9,11 10,11H12V9H8V7H12A2,2 0 0,1 14,9V11C14,12.11 13.1,13 12,13H10V15H14Z" />
                    </svg>
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Agregar un elemento que pida un número.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>                                 
            </div>

            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_seleccion()"
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M2 2V10H22V2zM14 4H20L17 8zM3 3H21V9H3zM2 12H22V14H2zM2 16H22V18H2zM2 20H22V22H2z"></path>
                    </svg>
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Agregar una lista de selección.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>           
            </div>     
            
            {{-- radio button  --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_radio()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M6 13C3.79 13 2 14.79 2 17S3.79 21 6 21 10 19.21 10 17 8.21 13 6 13M6 19C4.9 19 4 18.1 4 17S4.9 15 6 15 8 15.9 8 17 7.1 19 6 19M6 3C3.79 3 2 4.79 2 7S3.79 11 6 11 10 9.21 10 7 8.21 3 6 3M12 5H22V7H12V5M12 19V17H22V19H12M12 11H22V13H12V11Z" />
                    </svg>
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Agregar un elemento para escoger una entre varias opciones.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>              
            </div>            
            
            {{-- casillas de verificación  --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_casilla()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M4 13C2.89 13 2 13.89 2 15V19C2 20.11 2.89 21 4 21H8C9.11 21 10 20.11 10 19V15C10 13.89 9.11 13 8 13M8.2 14.5L9.26 15.55L5.27 19.5L2.74 16.95L3.81 15.9L5.28 17.39M4 3C2.89 3 2 3.89 2 5V9C2 10.11 2.89 11 4 11H8C9.11 11 10 10.11 10 9V5C10 3.89 9.11 3 8 3M4 5H8V9H4M12 5H22V7H12M12 19V17H22V19M12 11H22V13H12Z" />
                    </svg>
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Agregar un elemento formado por casillas de verificación.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>              
            </div>

            {{-- email  --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_email()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M22 6C22 4.9 21.1 4 20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6M20 6L12 11L4 6H20M20 18H4V8L12 13L20 8V18Z" />
                    </svg>
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Agregar un elemento que pida un email válido.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>                 
            </div>

            {{-- fecha  --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_fecha()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M9,14V16H7V14H9M13,14V16H11V14H13M17,14V16H15V14H17Z" />
                    </svg>
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Agregar un elemento que pida una fecha.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>                
            </div>

            {{-- subir archivo  --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_archivo()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M14,13V17H10V13H7L12,8L17,13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z" />
                    </svg>                    
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Agregar un elemento para seleccionar y subir un archivo.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>                
            </div>

            {{-- multivariable:  --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_multivariable()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M7 2H21C22.11 2 23 2.9 23 4V16C23 17.11 22.11 18 21 18H7C5.9 18 5 17.11 5 16V4C5 2.9 5.9 2 7 2M7 6V10H13V6H7M15 6V10H21V6H15M7 12V16H13V12H7M15 12V16H21V12H15M3 20V6H1V20C1 21.11 1.89 22 3 22H19V20H3Z" />
                    </svg>                    
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Agregar un elemento multivariable (Varias filas y columnas).
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>                
            </div>

            {{-- re-ordendar campos --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_ordenar()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M3,13V11H17V13H3M3,19V17H17V19H3M3,7V5H17V7H3M20,8V5H19V4H21V8H20M19,17V16H22V20H19V19H21V18.5H20V17.5H21V17H19M21.25,10C21.67,10 22,10.34 22,10.75C22,10.95 21.92,11.14 21.79,11.27L20.12,13H22V14H19V13.08L21,11H19V10H21.25Z" />
                    </svg>                   
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Re-ordenar elementos.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>                
            </div>

            {{-- eliminar campos --}}
            <div class="group relative w-28 text-center">
                <button type="button" wire:click="llamar_eliminar()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M2,6V8H14V6H2M2,10V12H11V10H2M14.17,10.76L12.76,12.17L15.59,15L12.76,17.83L14.17,19.24L17,16.41L19.83,19.24L21.24,17.83L18.41,15L21.24,12.17L19.83,10.76L17,13.59L14.17,10.76M2,14V16H11V14H2Z" />
                    </svg>                 
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Eliminar elementos a los cuales el usuario comercial no les haya asignado valores
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>                
            </div>

            {{-- regresar a tipos de producto --}}
            <div class="group relative w-28 text-center">
                <button type="button"  wire:click="llamar_regresar()" 
                    class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6">
                        <path fill="currentColor" d="M13.34,8.17C12.41,8.17 11.65,7.4 11.65,6.47A1.69,1.69 0 0,1 13.34,4.78C14.28,4.78 15.04,5.54 15.04,6.47C15.04,7.4 14.28,8.17 13.34,8.17M10.3,19.93L4.37,18.75L4.71,17.05L8.86,17.9L10.21,11.04L8.69,11.64V14.5H7V10.54L11.4,8.67L12.07,8.59C12.67,8.59 13.17,8.93 13.5,9.44L14.36,10.79C15.04,12 16.39,12.82 18,12.82V14.5C16.14,14.5 14.44,13.67 13.34,12.4L12.84,14.94L14.61,16.63V23H12.92V17.9L11.14,16.21L10.3,19.93M21,23H19V3H6V16.11L4,15.69V1H21V23M6,23H4V19.78L6,20.2V23Z" />
                    </svg>
                </button>   
                <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                    Regresar a tipos de producto.
                    <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                </div>                
            </div>
        </div>      
        {{-- fin de los botones        --}}

        {{-- vista preliminar  --}}
        <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
            <h1 class="font-bold text-4xl text-center ">{{$tipo_producto->titulo}}</h1> 
            <h1 class="font-bold text-2xl text-center ">{{$tipo_producto->subtitulo}}</h1> 
            <div class="grid grid-cols-{{$tipo_producto->columnas}}">
                @foreach($elementos_html as $fila)
                    @switch($fila->html_elemento_id)
                        @case(1)
                            {{-- el mr-6 hay que usarlo por el margen que se pierde al agregar el icon dentro del input text  --}}
                            <div class="mt-6 mr-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{$fila->cabecera}} 
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <div class="relative flex w-full flex-wrap items-stretch mb-3">
                                    <input type="text" id="id{{$fila->slug}}" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                    {{-- <span class="absolute right-0 pr-3 pt-3 mt-1 bg-gray-300 rounded"> --}}
                                    <span class="absolute right-0 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md">
                                        <svg class="inline-block align-text-top w-6 h-6" >
                                            <path fill="currentColor" d="M18.5,4L19.66,8.35L18.7,8.61C18.25,7.74 17.79,6.87 17.26,6.43C16.73,6 16.11,6 15.5,6H13V16.5C13,17 13,17.5 13.33,17.75C13.67,18 14.33,18 15,18V19H9V18C9.67,18 10.33,18 10.67,17.75C11,17.5 11,17 11,16.5V6H8.5C7.89,6 7.27,6 6.74,6.43C6.21,6.87 5.75,7.74 5.3,8.61L4.34,8.35L5.5,4H18.5Z" />
                                        </svg>  
                                    </span>
                                </div>  
                            </div>     
                            @break
                        @case(2)
                            {{-- input número entero --}}
                            {{-- el mr-6 hay que usarlo por el margen que se pierde al agregar el icon dentro del input text  --}}
                            <div class="mt-6 mr-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{$fila->cabecera}}
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label> 
                                <div class="relative flex w-full flex-wrap items-stretch mb-3">
                                    <input type="text" id="id{{$fila->slug}}" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                    <span class="absolute right-0 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md">
                                        <svg class="inline-block align-text-top w-6 h-6">
                                            <path fill="currentColor" d="M4,17V9H2V7H6V17H4M22,15C22,16.11 21.1,17 20,17H16V15H20V13H18V11H20V9H16V7H20A2,2 0 0,1 22,9V10.5A1.5,1.5 0 0,1 20.5,12A1.5,1.5 0 0,1 22,13.5V15M14,15V17H8V13C8,11.89 8.9,11 10,11H12V9H8V7H12A2,2 0 0,1 14,9V11C14,12.11 13.1,13 12,13H10V15H14Z" />
                                        </svg> 
                                    </span>
                                </div>  
                            </div>                              
                            @break                        
                        @case(3)   
                            {{-- lista desde valores  --}}
                            <div class="mt-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{-- 13sep2021: href para permitir modificar la cabecera del campo:  --}}
                                    {{$fila->cabecera}} 
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                {{-- <select wire:model="preliminar_{{$fila->slug}}" id="id{{$fila->slug}}" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"> --}}
                                {{-- <select wire:change="tabla_escogida($event.target.value)" id="id{{$fila->slug}}" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"> --}}
                                <select  id="id{{$fila->slug}}" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                    <option value="">Seleccione...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    @foreach ($fila->arr_para_combobox as $fila_val)
                                        <option value="{{$fila_val['conse']}}">{{$fila_val['texto']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    @endforeach
                                    {{-- @foreach ($fila->arr_para_combobox as $key => $ele)
                                        <option value="{{$key}}">{{$ele}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    @endforeach --}}
                                </select>
                            </div>    
                            @break
                        @case(4)
                            {{-- casillas  --}}
                            <div class="mt-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{$fila->cabecera}} 
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                @foreach($fila->arr_para_casillas as $fila_val)
                                    <input type="checkbox" value="{{$fila_val['conse']}}" id="id{{$fila->slug}}" class="mr-4">{{$fila_val['texto']}}<br>
                                @endforeach
                            </div>                              
                            @break                            
                        @case(5)
                            {{-- radio --}}
                            <div class="mt-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{$fila->cabecera}} 
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                @foreach($fila->arr_para_radios as $fila_val)
                                    <input type="radio" value="{{$fila_val['conse']}}" id="id{{$fila->slug}}" name="php_elmismo_radio{{$fila->slug}}" class="mr-4">{{$fila_val['texto']}}<br>
                                @endforeach
                            </div>                              
                            @break                            
                        @case(6)
                            {{-- input email  --}}
                            <div class="mt-6 mr-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{$fila->cabecera}} 
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <div class="relative flex w-full flex-wrap items-stretch mb-3">
                                    <input type="text" id="id{{$fila->slug}}" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                    <span class="absolute right-0 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md">
                                        <svg class="inline-block align-text-top w-6 h-6">
                                            <path fill="currentColor" d="M22 6C22 4.9 21.1 4 20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6M20 6L12 11L4 6H20M20 18H4V8L12 13L20 8V18Z" />
                                        </svg>
                                    </span>
                                </div>  
                            </div>                              
                            @break       
                        @case(7)
                            {{-- input fecha  --}}
                            <div class="mt-6 mr-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{$fila->cabecera}} 
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <div class="relative flex w-full flex-wrap items-stretch mb-3">
                                    <input
                                        x-data
                                        x-ref="fec_desde_provis"
                                        x-init="new Pikaday({
                                            field: $refs.fec_desde_provis, 
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
                                        id="idfec_desde{{$fila->slug}}" 
                                        class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                                    >
                                    <span class="absolute right-0 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md">
                                        <svg class="inline-block align-text-top w-6 h-6">
                                            <path fill="currentColor" d="M9,10V12H7V10H9M13,10V12H11V10H13M17,10V12H15V10H17M19,3A2,2 0 0,1 21,5V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H6V1H8V3H16V1H18V3H19M19,19V8H5V19H19M9,14V16H7V14H9M13,14V16H11V14H13M17,14V16H15V14H17Z" />
                                        </svg>
                                    </span>
                                </div>  
                            </div>                              
                            @break       
                        @case(8)
                            {{-- nueva sección  --}}
                            <div class="col-span-{{$tipo_producto->columnas}}"> 
                                
                            </div>
                            <div class="mt-12 text-2xl font-bold col-span-{{$tipo_producto->columnas}}">
                                {{$fila->cabecera}} 
                                @include('livewire.parciales.boton_modificar_cabecera')
                            </div>                            
                            @break
                        @case(9)
                            {{-- input número decimal --}}
                            {{-- el mr-6 hay que usarlo por el margen que se pierde al agregar el icon dentro del input text  --}}
                            <div class="mt-6 mr-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{$fila->cabecera}} 
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label> 
                                <div class="relative flex w-full flex-wrap items-stretch mb-3">
                                    <input type="text" id="id{{$fila->slug}}" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                    <span class="absolute right-0 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md">
                                        <svg class="inline-block align-text-top w-6 h-6">
                                            <path fill="currentColor" d="M4 7V9H8V11H6A2 2 0 0 0 4 13V17H10V15H6V13H8A2 2 0 0 0 10 11V9A2 2 0 0 0 8 7H4M16 7V9H18V17H20V7H16M12 15V17H14V15H12Z" />
                                        </svg>
                                    </span>
                                </div>  
                            </div>                              
                            @break                            
                        @case(10)   
                            {{-- lista desde tablas  --}}
                            <div class="mt-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{$fila->cabecera}} 
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                {{-- <select wire:model="preliminar_{{$fila->slug}}" id="id{{$fila->slug}}" class="w-11/12  shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"> --}}
                                <select id="id{{$fila->slug}}" class="w-11/12  shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                    <option value="">Seleccione...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    @foreach ($fila->arr_para_combobox as $fila_combobox)
                                            <option value="{{$fila_combobox->id}}">{{$fila_combobox->salida}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    @endforeach
                                </select>
                            </div>    
                            @break  
                        @case(11)
                            {{-- Subir archivo  --}}
                            <div class="mt-6 mr-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{$fila->cabecera}} 
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <div class="relative flex w-full flex-wrap items-stretch mb-3">
                                    <input type="file" 
                                        {{-- wire:model="foto"  --}}
                                        id="id{{$fila->slug}}" 
                                        {{-- class="w-full shadow-lg px-0 py-0 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors" --}}
                                        class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                                    >   
                                 
                                    {{-- <span class="absolute right-0 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md">
                                        <svg class="inline-block align-text-top w-6 h-6">
                                            <path fill="currentColor" d="M14,13V17H10V13H7L12,8L17,13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z" />
                                        </svg>    
                                    </span> --}}
                                </div>  
                            </div>     
                            @break  
                        @case(12)
                            {{-- elemento multivariable    --}}
                            <div class="mt-6 mr-6">
                                <label class="block font-bold text-base my-auto ml-1">
                                    {{$fila->cabecera}} 
                                    @include('livewire.parciales.boton_modificar_cabecera')
                                    @if ($fila->obligatorio)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                <div class="relative flex w-full flex-wrap items-stretch mb-3">
                                    <input type="button" value="Click para llenar multivariable"
                                        id="id{{$fila->slug}}" 
                                        class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                                    >   
                                </div>  
                            </div>
                            @break;
                        @default
                            
                    @endswitch
                @endforeach   
            </div>
        </div>      {{-- fin de la vista preliminar  --}}
    </div>      {{-- fin del contenido principal  --}}
</div>   {{-- fin del div principal  --}}
