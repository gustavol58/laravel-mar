<div>
    {{-- MODAL para pedir entrada de número --}}
    <x-jet-dialog-modal wire:model="modal_visible_numero">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar una entrada de número.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <div x-data x-init="$refs.numero_cabecera.focus()">
                <form  x-on:click.away="$wire.cerrar_modal_numero()" wire:submit.prevent="submit_numero()">

                    <div class="mt-3">

                        @include('livewire.parciales.modales.cabecera' , ['campo_encadena' => 'numero_cabecera'])
                        @include('livewire.parciales.modales.roles' , ['campo_encadena' => 'numero_rol_escogido'])
                        @include('livewire.parciales.modales.obligatorio' , ['campo_encadena' => 'numero_obligatorio'])
    
                        {{-- Tipo (1 entero 2 decimal) --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Tipo: <span class="text-red-500">*</span></label>
                            <div class="w-8/12">
                                <input type="radio" wire:model="numero_tipo" value="1"   ><span class="ml-2 text-gray-700">Entero</span>
                                <input type="radio" wire:model="numero_tipo" value="2"   class="ml-6"   ><span class="ml-2 text-gray-700">Decimal (2 decimales)</span>
                            </div>
                        </div>  
                        @error('numero_tipo')
                            {{-- <span  class="text-red-500 border border-1 border-green-500">{{ $message }}</span>  --}}
                            <div  class="text-red-500 mb-4 -mt-6">{{ $message }}</div> 
                        @enderror   
    
                        {{-- Número mínimo --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Número mínimo permitido:</label>
                            <div class="w-8/12">
                                <input type="text" wire:model="numero_min"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                        </div>  
                        @error('numero_min')
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror 
    
                        {{-- Número máximo --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Número máximo permitido:</label>
                            <div class="w-8/12">
                                <input type="text" wire:model="numero_max"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                        </div>  
                        @error('numero_max')
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror                      
    
                        @include('livewire.parciales.modales.botones' , ['metodo_cerrar_modal' => 'cerrar_modal_numero()'])
    
                    </div>
                </form>
            </div>

        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>    
</div>
