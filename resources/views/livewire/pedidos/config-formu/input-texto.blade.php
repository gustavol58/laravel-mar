<div>
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
            <div x-data x-init="$refs.texto_cabecera.focus()">
                <form  x-on:click.away="$wire.cerrar_modal_texto()" wire:submit.prevent="submit_texto()">

                    <div class="mt-3">
                        @include('livewire.parciales.modales.cabecera' , ['campo_encadena' => 'texto_cabecera'])
                        @include('livewire.parciales.modales.roles' , ['campo_encadena' => 'texto_rol_escogido'])
                        @include('livewire.parciales.modales.obligatorio' , ['campo_encadena' => 'texto_obligatorio'])

                        {{-- Longitud máxima  --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Longitud máxima:</label>
                            <div class="w-8/12">
                                <input type="text" wire:model="texto_longitud_max"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                        </div>  
                        @error('texto_longitud_max')
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror  
     
                        @include('livewire.parciales.modales.botones' , ['metodo_cerrar_modal' => 'cerrar_modal_texto()'])
    
                    </div>
                </form>
            </div>

        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>    
</div>
