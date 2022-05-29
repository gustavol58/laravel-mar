<div>
    {{-- MODAL para pedir fecha --}}
    <x-jet-dialog-modal wire:model="modal_visible_fecha">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar elemento para pedir fecha.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <div x-data x-init="$refs.fecha_cabecera.focus()">
                <form  x-on:click.away="$wire.cerrar_modal_fecha()" wire:submit.prevent="submit_fecha()">
                    <div class="mt-3">
                        @include('livewire.parciales.modales.cabecera' , ['campo_encadena' => 'fecha_cabecera'])
                        @include('livewire.parciales.modales.roles' , ['campo_encadena' => 'fecha_rol_escogido'])
                        @include('livewire.parciales.modales.obligatorio' , ['campo_encadena' => 'fecha_obligatorio'])
    
                        @include('livewire.parciales.modales.botones' , ['metodo_cerrar_modal' => 'cerrar_modal_fecha()'])
                    </div>
                </form>
            </div>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>              
</div>
