<div>
    {{-- MODAL para pedir subir un archivo --}}
    <x-jet-dialog-modal wire:model="modal_visible_archivo">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar elemento para subir un archivo.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <div x-data x-init="$refs.archivo_cabecera.focus()">
                <form  x-on:click.away="$wire.cerrar_modal_archivo()" wire:submit.prevent="submit_archivo()">
                    <div class="mt-3">
                        @include('livewire.parciales.modales.cabecera' , ['campo_encadena' => 'archivo_cabecera'])
                        @include('livewire.parciales.modales.roles' , ['campo_encadena' => 'archivo_rol_escogido'])
                        @include('livewire.parciales.modales.obligatorio' , ['campo_encadena' => 'archivo_obligatorio'])

                        {{-- Tipos de archivo permitidos  --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base mb-2 ml-1 text-gray-600">Tipos de archivo permitidos: <span class="text-red-500">*</span></label>
                            <div class="w-8/12">
                                @foreach($archivo_tipos_disponibles as $index => $tipo) 
                                    <input type="checkbox" wire:model="archivo_tipos_escogidos" value="{{ $tipo['id'] }}"   class="mr-4"><span class="text-gray-700">{{ $tipo['titulo'] }}</span><br>                                   
                                @endforeach
                            </div>
                        </div>  
                        @error('archivo_tipos_escogidos')
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror  

                        {{-- Tamaño máximo de archivo  --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Tamaño máximo del archivo (megas): <span class="text-red-500">*</span></label>
                            <div class="w-8/12">
                                <input type="text" wire:model="archivo_megas"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                        </div>  
                        @error('archivo_megas')
                            <div  class="text-red-500 mb-4 -mt-6">{{ $message }}</div> 
                        @enderror                          
                        
                        

        
                        @include('livewire.parciales.modales.botones' , ['metodo_cerrar_modal' => 'cerrar_modal_archivo()'])

                    </div>
                </form>
            </div>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>             
</div>
