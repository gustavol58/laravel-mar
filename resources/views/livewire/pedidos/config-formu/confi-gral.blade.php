<div>
    {{-- MODAL para pedir la configuración general --}}
    <x-jet-dialog-modal  wire:model="modal_visible_gral" >
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500">CONFIGURACIÓN GENERAL.</span>       
                </div>
            </div>
        </x-slot>
        
        <x-slot name="content"> 
            {{-- el alpine es solamente para colocar el autofocus en el primer campo html:  --}}
            <div x-data x-init="$refs.gral_titulo.focus()">
                <form x-on:click.away="$wire.cerrar_modal_gral()"  wire:submit.prevent="submit_gral()">
                    <div class="mt-3  ">
                        {{-- Título en Configuraciones generales --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Título:</label>
                            <div class="w-8/12">
                                <input type="text"  x-ref="gral_titulo" wire:model="gral_titulo" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                        </div>  

                        {{-- Subtítulo en Configuraciones generales --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Subtítulo:</label>
                            <div class="w-8/12">
                                <input type="text" wire:model="gral_subtitulo" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                        </div>   

                        {{-- Número de columnas --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Número de columnas:</label>
                            <div class="w-8/12">
                                <input type="number" wire:model="gral_columnas" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                        </div>   
                        @error('gral_columnas')
                            <span  class="text-red-500">{{ $message }}</span> 
                        @enderror   

                        {{-- botones  --}}
                        <div class="flex mb-4" >
                            <div class="w-1/4 ml-4 ">
                                {{-- botón grabar: --}}
                                <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3">
                                    <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="currentColor" d="M14,2A8,8 0 0,0 6,10A8,8 0 0,0 14,18A8,8 0 0,0 22,10H20C20,13.32 17.32,16 14,16A6,6 0 0,1 8,10A6,6 0 0,1 14,4C14.43,4 14.86,4.05 15.27,4.14L16.88,2.54C15.96,2.18 15,2 14,2M20.59,3.58L14,10.17L11.62,7.79L10.21,9.21L14,13L22,5M4.93,5.82C3.08,7.34 2,9.61 2,12A8,8 0 0,0 10,20C10.64,20 11.27,19.92 11.88,19.77C10.12,19.38 8.5,18.5 7.17,17.29C5.22,16.25 4,14.21 4,12C4,11.7 4.03,11.41 4.07,11.11C4.03,10.74 4,10.37 4,10C4,8.56 4.32,7.13 4.93,5.82Z" />
                                    </svg>
                                    
                                    <span class="align-text-bottom font-semibold">Aplicar</span>
                                </button>
                            </div>
                
                            <div class="w-1/4 ml-4 pr-4">
                                {{-- botón cancelar : --}}
                                <button type="button"  wire:click="cerrar_modal_gral()" class="w-full mt-4 bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3">
                                    <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="align-text-bottom font-semibold">Cancelar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>    
            {{-- fin del div alpine para el autofocus  --}}
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>
</div>