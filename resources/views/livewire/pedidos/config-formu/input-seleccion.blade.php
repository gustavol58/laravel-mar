<div>
    {{-- MODAL para pedir entrada de selección --}}
    <x-jet-dialog-modal wire:model="modal_visible_seleccion">
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar lista de selección.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <div x-data x-init="$refs.seleccion_cabecera.focus()">
                <form  x-on:click.away="$wire.cerrar_modal_seleccion()" wire:submit.prevent="submit_seleccion()">
                    <div class="mt-3">
                        @include('livewire.parciales.modales.cabecera' , ['campo_encadena' => 'seleccion_cabecera'])
                        @include('livewire.parciales.modales.roles' , ['campo_encadena' => 'seleccion_rol_escogido'])
                        @include('livewire.parciales.modales.obligatorio' , ['campo_encadena' => 'seleccion_obligatorio'])

                        <div x-data="main_tipo_origen()">
                            {{-- tipo de origen en: seleccion --}}
                            <div class="flex mb-4">
                                <label class="align-text-top w-4/12 font-bold text-base  mb-2 ml-1 text-gray-600">Origen de datos: <span class="text-red-500">*</span></label>
                                <div class="w-5/12">
                                    <input type="radio" wire:model="seleccion_tipo_origen" value="1"><span class="ml-2 text-gray-700">Lista de valores<br></span>
                                    <input type="radio" wire:model="seleccion_tipo_origen" value="2" class=""><span class="ml-2 text-gray-700">Tabla</span>
                                </div>
                            </div>  
                            @error('seleccion_tipo_origen')
                                <span  class="text-red-500">{{ $message }}</span> 
                            @enderror  

                            {{-- Listado de valores (si escogio origen de datos valores) --}}
                            <div class="mb-4" x-show="tipo_origen_alpine == 1">
                                <label class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Digite los valores de la lista, cada linea corresponde a un valor:</label>
                                <div class="w-12/12">
                                    <textarea wire:model="seleccion_valores"  cols="56" rows="6" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
                                </div>
                            </div>  
                            @error('seleccion_valores')
                                <div  class="text-red-500 mb-4 -mt-2">{{ $message }}</div> 
                            @enderror    
                            
                            {{-- Combo de tablas (si escogio origen de datos: tabla) --}}
                            <div class="" x-show="tipo_origen_alpine == 2">
                                <div class="w-3/12 mx-auto mb-2">
                                    <select wire:change="tabla_escogida($event.target.value)" wire:model="seleccion_tabla" id="idtabla" class="shadow-lg px-3   border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                        <option value="">Seleccione tabla...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                        @foreach ($arr_tablas as $una_tabla)
                                            <option value="{{$una_tabla['id']}}">{{$una_tabla['titulo']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                        @endforeach
                                    </select>
                                </div>                
                            </div>
                            @error('seleccion_tabla')
                                <div  class="text-red-500 mb-4 -mt-2">{{ $message }}</div> 
                            @enderror                              
            
                            {{-- Campos disponibles y campos a mostrar  (si escogio origen de datos: tabla) --}}
                            <div class="flex mb-4" x-show="tipo_origen_alpine == 2">
                                {{-- campos disponibles --}}
                                <div class="mb-4 w-5/12">
                                    <label class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Campos disponibles:</label>
                                    <div class="w-12/12">   
                                        {{-- el evento doble-click se hace aprovechando el div alpine dentro del cual estamos  --}}
                                        {{-- <select x-on:dblclick="$wire.dobleClick_disponibles($event.target.value , $event.target.innerText)" wire:model="seleccion_campos_disponibles" size="{{$provis_campos_disponibles_canti}}" id="idcamposdisponibles" class="bg-none shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"> --}}
                                        <select x-on:dblclick="$wire.dobleClick_disponibles($event.target.value , $event.target.innerText)" size="{{$provis_campos_disponibles_canti}}" id="idcamposdisponibles" class="bg-none shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                            @if (count($provis_campos_disponibles) !== 0)
                                                @foreach ($provis_campos_disponibles as $un_disponible)
                                                    <option value="{{$un_disponible['id']}}">{{$un_disponible['titulo']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                @endforeach                                                
                                            @endif
                                        </select>
                                    </div>   
                                    <div class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-right text-gray-600">
                                        Doble click para pasarlos ===>
                                    </div>
                                </div>

                                {{-- espacio entre las dos listas  --}}
                                <div class="mb-4 w-2/12">
                                    
                                </div>
                                {{-- campos para mostrar --}}
                                <div class="mb-4 w-5/12">
                                    <label class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Campos para mostrar</label>
                                    <div class="w-12/12">
                                        {{-- <select x-on:dblclick="$wire.dobleClick_mostrar($event.target.value , $event.target.innerText)" wire:model="seleccion_campos_mostrar" size="{{$provis_campos_disponibles_canti}}" id="idcamposdisponibles" class="bg-none shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"> --}}
                                        <select x-on:dblclick="$wire.dobleClick_mostrar($event.target.value , $event.target.innerText)" size="{{$provis_campos_disponibles_canti}}" id="idcamposdisponibles" class="bg-none shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                            @if (count($provis_campos_mostrar) !== 0)
                                                @foreach ($provis_campos_mostrar as $un_mostrar)
                                                    <option value="{{$un_mostrar['id']}}">{{$un_mostrar['titulo']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                @endforeach                                                
                                            @endif
                                        </select>
                                    </div>   
                                    <div class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">
                                        <=== Doble click para regresarlos
                                    </div>
                                    {{-- @error('seleccion_campos_mostrar') --}}
                                    @error('provis_campos_mostrar')
                                        <div  class="text-red-500 mb-4 -mt-2">{{ $message }}</div> 
                                    @enderror                                       
                                </div>                                
                            </div>                        
                        </div>
                        {{-- fin del alpine x-data main_tipo_origen()  --}}

                        @include('livewire.parciales.modales.botones' , ['metodo_cerrar_modal' => 'cerrar_modal_seleccion()'])

                    </div>
                </form>
            </div>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>  
</div>

<script>
    function main_tipo_origen(){
        return {
            tipo_origen_alpine : @entangle('seleccion_tipo_origen'),
        }
    }
</script>
