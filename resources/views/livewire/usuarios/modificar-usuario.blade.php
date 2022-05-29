<div>
    @livewire('menu-own')
    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
        {{-- 
            =====================================================================================
                Título
            ===================================================================================== 
        --}}
        <div class="">
            <h1 class="font-bold text-xl uppercase">Modificación del usuario {{$nombre_completo}}</h1>
        </div>

        {{-- 
            =====================================================================================
                Formulario
            ===================================================================================== 
        --}}
        <form wire:submit.prevent="submit_modificar_usuario()" enctype="multipart/form-data"> 
            <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
                {{-- 
                    ==========================================================================
                        Petición de campos
                    ==========================================================================
                --}}                 
                {{-- nombre completo --}}
                <div class="mr-6 col-2">
                    <label class="block font-bold text-base my-auto ml-1">
                        Nombre completo
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex w-full flex-wrap items-stretch mb-3">
                        <input wire:model="nombre_completo" type="text" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        @error('nombre_completo') <span class="text-red-500">{{ $message }}</span> @enderror   
                    </div>                     
                </div>                   
                {{-- nombre acceso --}}
                <div class="mr-6 col-2">
                    <label class="block font-bold text-base my-auto ml-1">
                        Nombre de acceso
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex w-full flex-wrap items-stretch mb-3">
                        <input wire:model="nombre_acceso" type="text" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        @error('nombre_acceso') <span class="text-red-500">{{ $message }}</span> @enderror   
                    </div>                     
                </div>                   
                {{-- email --}}
                <div class="mr-6 col-2">
                    <label class="block font-bold text-base my-auto ml-1">
                        Email
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex w-full flex-wrap items-stretch mb-3">
                        <input wire:model="email" type="text" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        @error('email') <span class="text-red-500">{{ $message }}</span> @enderror   
                    </div>                     
                </div> 
                {{-- Rol (combo)  --}}
                <div class="mr-6">
                    <label class="block font-bold text-base my-auto ml-1">
                        Rol
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex w-full flex-wrap items-stretch mb-3">
                        <select wire:model="rol_id" id="idrol_id" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            <option value="">Seleccione rol...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                            @foreach ($arr_para_roles as $un_rol)
                                <option value="{{$un_rol['id']}}">{{$un_rol['rol']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                            @endforeach
                        </select>
                        @error('rol_id') <span class="text-red-500">{{ $message }}</span> @enderror   
                    </div>
                </div> 
                {{-- 
                    ==========================================================================
                        Mensajes de validaciones/grabaciones
                    ==========================================================================
                --}}                 
                <div class="">

                </div>    
                
                {{-- 
                    ==========================================================================
                        Botones
                    ==========================================================================
                --}}                  
                @php
                    $texto_boton = "Grabar modificaciones";
                    $ancho_boton = "w-2/4";
                @endphp
                <div class="flex">
                    <button type="submit" class="mx-auto {{$ancho_boton}} mt-6 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold"> {{$texto_boton}}</button> 


                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" wire:click="mostrar_modal_btn_cancelar()" class="w-full bg-red-600 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold">
                             Cancelar - Regresar
                        </button>
                    </div>

                </div>
            </div>
        </form>        
    </div>

    {{-- 
        ================================================================================================
            MODAL para confirmar salir sin grabar, antes de cerrar el formu de modificar usuarios
        ================================================================================================ 
    --}}    
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
