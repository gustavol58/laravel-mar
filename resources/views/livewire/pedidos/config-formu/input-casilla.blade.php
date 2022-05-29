<div>
    {{-- MODAL para pedir entrada de casillas --}}
    <x-jet-dialog-modal wire:model="modal_visible_casilla">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar elemento de casillas.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <div x-data x-init="$refs.casilla_cabecera.focus()">
                <form  x-on:click.away="$wire.cerrar_modal_casilla()" wire:submit.prevent="submit_casilla()">
                    <div class="mt-3">
                        @include('livewire.parciales.modales.cabecera' , ['campo_encadena' => 'casilla_cabecera'])
                        @include('livewire.parciales.modales.roles' , ['campo_encadena' => 'casilla_rol_escogido'])
                        @include('livewire.parciales.modales.obligatorio' , ['campo_encadena' => 'casilla_obligatorio'])

                        {{-- Listado de valores para las casillas --}}
                        <div class="mb-4" >
                            <label class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Digite las casillas que van a ser usadas, cada linea corresponde a una casilla:</label>
                            <div class="w-12/12">
                                <textarea wire:model="casilla_valores"  cols="56" rows="8" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
                            </div>
                        </div>  
                        @error('casilla_valores')
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror                      
        
                        @include('livewire.parciales.modales.botones' , ['metodo_cerrar_modal' => 'cerrar_modal_casilla()'])

                    </div>
                </form>
            </div>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>             
</div>
