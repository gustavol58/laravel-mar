<div>
    @livewire('menu-own')

    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
        {{-- Título  --}}
        <div class="">
            @if ($operacion == 'crear')
                <h1 class="font-bold text-xl uppercase">Ingresar nuevo cliente.</h1>
            @elseif($operacion == 'modificar')
                <h1 class="font-bold text-xl uppercase">Modificación del cliente: {{$razon_social}}</h1>
            @else
                <h1 class="font-bold text-xl uppercase">*** error ***</h1>
            @endif
        </div>

        {{-- Formulario:  --}}
        <form wire:submit.prevent="submit_cliente()" enctype="multipart/form-data">  
            <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">

                <div class="grid grid-cols-4">
                    {{-- tipo de documento  --}}
                    <div class="mt-6 mr-6">
                        <label class="block font-bold text-base my-auto ml-1">
                            Tipo de documento
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <select wire:model="tipo_documento_id" id="idtipo_documento_id" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                <option value="">Seleccione tipo...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                @foreach ($arr_para_tipo_documento_id as $un_tipo)
                                    <option value="{{$un_tipo->id}}">{{$un_tipo->nombre_tipo_doc}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                @endforeach
                            </select>
                            @error('tipo_documento_id') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>
                    </div>                     

                    {{-- num docu (nit) --}}
                    <div class="mt-6 mr-6">
                        <label class="block font-bold text-base my-auto ml-1">
                            Número de documento (Sin DIV)
                            <span class="text-red-500">*</span>
                        </label>
                        {{-- <div class="relative flex w-full flex-wrap items-stretch mb-3"> --}}
                        <div class="relative flex w-full items-stretch mb-3">
                            <input wire:model="nit" type="text" id="idnit" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            @error('nit') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>  
                    </div> 
                    
                    {{-- DIV  --}}
                    <div class="mt-6 mr-6 w-16">
                        <label class="block font-bold text-base my-auto ml-1">
                            DIV
                        </label>
                        {{-- <div class="relative flex w-full flex-wrap items-stretch mb-3"> --}}
                        <div class="relative flex w-full items-stretch mb-3">
                            <input wire:model="div_mostrar"  disabled  type="text" class="w-12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors bg-gray-300">
                        </div>  
                    </div>                     

                    {{-- nombre cliente --}}
                    <div class="mt-6 mr-6 col-span-2">
                        <label class="block font-bold text-base my-auto ml-1">
                            Nombre del cliente (Razón social)
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input wire:model="razon_social" type="text" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            @error('razon_social') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>                     
                    </div>                     

                    {{-- dirección --}}
                    <div class="mt-6 mr-6 col-span-2">
                        <label class="block font-bold text-base my-auto ml-1">
                            Dirección
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input wire:model="direccion" type="text" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            @error('direccion') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>                     
                    </div>  
                    
                    {{-- ciudad  --}}
                    <div class="mt-6 mr-6">
                        <label class="block font-bold text-base my-auto ml-1">
                            Ciudad
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <select wire:model="ciudad_id" id="idciudad" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                <option value="">Seleccione ciudad...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                @foreach ($arr_para_ciudades as $una_ciudad)
                                    <option value="{{$una_ciudad->id}}">{{$una_ciudad->ciudad}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                @endforeach
                            </select>
                            @error('ciudad_id') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>
                    </div>  

                    {{-- fijo --}}
                    <div class="mt-6 mr-6">
                        <label class="block font-bold text-base my-auto ml-1">
                            Teléfono fijo
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input wire:model="fijo" type="text" id="idfijo" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            @error('fijo') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>  
                    </div>                    

                    {{-- celular --}}
                    <div class="mt-6 mr-6">
                        <label class="block font-bold text-base my-auto ml-1">
                            Celular
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input wire:model="celular" type="text" id="idcelular" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            @error('celular') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>  
                    </div>     
                    
                    <div>
                        {{-- Para dejar una columna en blanco  --}}
                    </div>

                    {{-- contacto --}}
                    <div class="mt-6 mr-6">
                        <label class="block font-bold text-base my-auto ml-1">
                            Contacto
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input wire:model="contacto" id="idcontacto" type="text" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            @error('contacto') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>                     
                    </div>  

                    {{-- email --}}
                    <div class="mt-6 mr-6">
                        <label class="block font-bold text-base my-auto ml-1">
                            E-mail
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input wire:model="email" id="idemail" type="text" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>                     
                    </div> 

                    {{-- condiciones comerciales --}}
                    <div class="mt-6 mr-6">
                        <label class="block font-bold text-base my-auto ml-1">
                            Condiciones comerciales (0: Contado)
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input wire:model="condiciones_comer" type="text" id="idcondiciones_comer" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            @error('condiciones_comer') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>  
                    </div>  

                    {{-- comercial asignado  --}}
                    {{-- Si el rol es 'comer', no debe escoger comercial asignado  --}}
                    @php
                        if (Auth::user()->roles[0]->name == 'comer'){
                            $this->comercial_asignado_id = Auth::user()->id;
                            $disabled___ = 'disabled';
                            $fondo___ = 'bg-gray-300';
                        }else{
                            $disabled___ = '';
                            $fondo___ = '';
                        }
                    @endphp 
                    <div class="mt-6 mr-6">
                        <label class="block font-bold text-base my-auto ml-1">
                            Comercial asignado
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <select {{$disabled___}} {{$fondo___}} wire:model="comercial_asignado_id" id="idcomercial_asignado_id" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                <option value="">Seleccione comercial...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                @foreach ($arr_para_comerciales_asignados as $un_comercial)
                                    <option value="{{$un_comercial->id}}">{{$un_comercial->comercial}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                @endforeach
                            </select>
                            @error('comercial_asignado_id') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>
                    </div>                          
                </div>

                {{-- Mensajes de resultado validaciones / grabación  --}}
                <div class="">
                    {{-- Muestra mensaje de grabación correcta --}}
                    @if ($mensaje_correcto)
                        <div  class="mb-4 mt-4">
                            <div class="alert flex flex-row items-center bg-green-200 pl-5 pt-3 pb-2 rounded border-b-2 border-green-300">
                                <div class="alert-icon flex items-center bg-green-100 border-2 border-green-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
                                    <span class="text-green-500">
                                        <svg fill="currentColor"
                                            viewBox="0 0 20 20"
                                            class="h-6 w-6">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="alert-content ml-4">
                                    <div class="alert-title font-semibold text-lg text-green-800">
                                        Grabación correcta.
                                    </div>
                                    <div class="alert-description text-sm text-green-600">
                                        {{ $mensaje_correcto }}
                                    </div>
                                </div>
                            </div>        
                        </div>
                    @endif

                    {{-- Muestra mensaje de error en la validación lado servidor --}}
                    @if ($mensaje_error)
                        <div  class="mb-4 mt-4">
                            <div class="alert flex flex-row items-center bg-red-200 pl-5 pt-3 pb-2 rounded border-b-2 border-red-300">
                                <div class="alert-icon flex items-center bg-red-100 border-2 border-red-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
                                    <span class="text-red-500">
                                        <svg fill="currentColor"
                                            viewBox="0 0 20 20"
                                            class="h-6 w-6">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="alert-content ml-4">
                                    <div class="alert-title font-semibold text-lg text-red-800">
                                        El producto no pudo ser grabado.
                                    </div>
                                    <div class="alert-description text-sm text-red-600">
                                        {{ $mensaje_error }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- fin de los mensajes validaciones / grabación  --}}    

                {{-- Botones:  --}}
                @php
                    if($operacion == 'crear'){
                        $texto_boton = "Crear cliente";
                        $ancho_boton = "w-1/4";
                    }else{
                        $texto_boton = "Grabar modificaciones del cliente";
                        $ancho_boton = "w-2/4";
                    }    
                @endphp
                <div class="flex">
                    <button type="submit" class="mx-auto {{$ancho_boton}} mt-6 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold"> {{$texto_boton}}</button> 

                    @if ($operacion == 'crear')
                        <div class="mx-auto w-1/4 mt-6">
                            <button type="button" wire:click="btn_limpiar()" class="w-full bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3 font-semibold"> Limpiar</button>
                        </div>
                    @endif

                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" wire:click="mostrar_modal_btn_cancelar()" class="w-full bg-red-600 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold">
                             Cancelar - Regresar
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    {{-- MODAL para avisar que hay info sin grabar, antes de cerrar el formu de clientes --}}
    <x-jet-dialog-modal wire:model="modal_visible_cancelar">
        <x-slot name="title">
            <br><br>
            <center>¿Está seguro de perder la información digitada?</center>
        </x-slot>
    
        <x-slot name="content"> 
                {{-- Botones --}}
                <div class="flex mx-auto">
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" class=" w-full bg-red-600 hover:bg-red-400 focus:bg-red-400  text-white rounded-lg px-3 py-3 font-semibold" wire:click="btn_cancelar()"> Si, perder la info</button>
                    </div>
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" class="w-full bg-green-500 hover:bg-green-400 focus:bg-green-400 text-white rounded-lg px-3 py-3 font-semibold" wire:click="$set('modal_visible_cancelar', false)"> No, regresar</button>
                    </div>
                </div> 
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>    
    {{-- FIN DEL MODAL para avisar que hay info sin grabar, antes de cerrar la creación de productos --}}
</div>

<script>
    // Para que cuando se escoja un tipo de documento, o cuando se digita un nit, 
    // se proceda a calcular el DIV: 

    let input_tipo = document.getElementById('idtipo_documento_id');
    input_tipo.addEventListener('blur', () => {
        Livewire.emit('calcular_div_');
    });
    
    let input_nit = document.getElementById('idnit');
    input_nit.addEventListener('blur', () => {
        Livewire.emit('calcular_div_');
    });
</script>


