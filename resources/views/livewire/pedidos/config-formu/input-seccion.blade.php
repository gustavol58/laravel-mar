<div>
    {{-- MODAL para pedir nueva sección --}}
    <x-jet-dialog-modal wire:model="modal_visible_seccion">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar nueva sección.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <div x-data x-init="$refs.seccion_cabecera.focus()">
                <form  x-on:click.away="$wire.cerrar_modal_seccion()" wire:submit.prevent="submit_seccion()">

                    <div class="mt-3">
                        @include('livewire.parciales.modales.cabecera' , ['campo_encadena' => 'seccion_cabecera'])

                        @include('livewire.parciales.modales.botones' , ['metodo_cerrar_modal' => 'cerrar_modal_seccion()'])
    
                    </div>
                </form>
            </div>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>    
</div>

