<div>
    {{-- MODAL para pedir email --}}
    <x-jet-dialog-modal wire:model="modal_visible_email">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar elemento para pedir email.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <div x-data x-init="$refs.email_cabecera.focus()">
                <form  x-on:click.away="$wire.cerrar_modal_email()" wire:submit.prevent="submit_email()">

                    {{-- <div class="mt-3 text-center "> --}}
                    <div class="mt-3">
                        @include('livewire.parciales.modales.cabecera' , ['campo_encadena' => 'email_cabecera'])
                        @include('livewire.parciales.modales.roles' , ['campo_encadena' => 'email_rol_escogido'])
                        @include('livewire.parciales.modales.obligatorio' , ['campo_encadena' => 'email_obligatorio'])

                        @include('livewire.parciales.modales.botones' , ['metodo_cerrar_modal' => 'cerrar_modal_email()'])

                    </div>
                </form>
            </div>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>             
</div>
