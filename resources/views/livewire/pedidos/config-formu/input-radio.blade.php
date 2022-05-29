<div>
    {{-- MODAL para pedir entrada de casillas --}}
    <x-jet-dialog-modal wire:model="modal_visible_radio">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar elemento de opciones de radio.</span>       
                    <span class="text-gray-500 text-2xl">(El que permite escoger solo una de las opciones).</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <div x-data x-init="$refs.radio_cabecera.focus()">
                <form  x-on:click.away="$wire.cerrar_modal_radio()" wire:submit.prevent="submit_radio()">
                    <div class="mt-3">
                        @include('livewire.parciales.modales.cabecera' , ['campo_encadena' => 'radio_cabecera'])
                        @include('livewire.parciales.modales.roles' , ['campo_encadena' => 'radio_rol_escogido'])
                        @include('livewire.parciales.modales.obligatorio' , ['campo_encadena' => 'radio_obligatorio'])

                        {{-- Listado de valores para las opciones radio --}}
                        <div class="mb-4" >
                            <label class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Digite las opciones radio, cada linea corresponde a una opción de radio:</label>
                            <div class="w-12/12">
                                <textarea wire:model="radio_valores"  cols="56" rows="8" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
                            </div>
                        </div>  
                        @error('radio_valores')
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div>
                        @enderror                      
        
                        @include('livewire.parciales.modales.botones' , ['metodo_cerrar_modal' => 'cerrar_modal_radio()'])

                    </div>
                </form>
            </div>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>              
</div>
