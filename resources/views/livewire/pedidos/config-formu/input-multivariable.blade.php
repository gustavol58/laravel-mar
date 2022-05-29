<div>
    {{-- MODAL para pedir elemento multivariable --}}
    <x-jet-dialog-modal wire:model="modal_visible_multivariable" maxWidth="4xl" >
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar un elemento multivariable.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 
            <div x-data x-init="$refs.multivariable_cabecera.focus()">
                <form style="max-height: 450px; overflow-y: auto;" x-on:click.away="$wire.cerrar_modal_multivariable()" wire:submit.prevent="submit_multivariable()">

                    <div class="mt-3"> 
                        @include('livewire.parciales.modales.cabecera' , ['campo_encadena' => 'multivariable_cabecera'])
                        {{-- @include('livewire.parciales.modales.roles' , ['campo_encadena' => 'multivariable_rol_escogido']) --}}
                        {{-- @include('livewire.parciales.modales.obligatorio' , ['campo_encadena' => 'numero_obligatorio']) --}}
    
                        {{-- Número mínimo de filas --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Número mínimo de filas: <span class="text-red-500">*</span></label>
                            <div class="w-8/12">
                                <input type="text" wire:model="multivariable_filas_min"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                        </div>  
                        @error('multivariable_filas_min')
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror 

                        {{-- Número máximo de filas --}}
                        <div class="flex mb-4">
                            <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Número máximo de filas: </label>
                            <div class="w-8/12">
                                <input type="text" wire:model="multivariable_filas_max"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            </div>
                        </div>  
                        @error('multivariable_filas_max')
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror                         

                        {{-- 03sep2021: Implementar columnas configurables:  --}}
                        {{-- 
                            Ciclo para mostrar todas las columnas.
                            $columnas_actuales se incrementa si el 
                            usuario usa el botón 'Agregar columnas':
                        --}}
                        @for ($num_col = 1; $num_col <= $columnas_actuales; $num_col++)
                            {{-- fila: num - cabecera - roles - tipo - X  --}}
                            <hr><br>
                            {{-- fila para títulos de las 5 columnas (incluye X final) --}}
                            <div class="flex">
                                <div class="w-1/12 mr-2">
                                    Num.
                                </div>

                                <div class="w-4/12 mr-2">
                                    Cabecera de columna
                                    <span class="text-red-500 ml-1">*</span>
                                </div>

                                <div class="w-4/12 mr-2">
                                    Tipo
                                    <span class="text-red-500 ml-1">*</span>
                                </div>

                                <div class="w-4/12 mr-2">
                                    Roles
                                    <span class="text-red-500 ml-1">*</span>
                                </div>                            

                                <div class="w-1/12 mr-2">
        
                                </div>
                            </div>                            
                            <div class="flex">
                                {{-- num  --}}
                                <div class="w-1/12 mr-2 text-bold text-4xl">
                                    {{$num_col}}
                                </div>

                                {{-- cabecera  --}}
                                <div class="w-4/12 mr-2">
                                    <input type="text" wire:model="arr_columnas_configurables.{{$num_col}}.1"  class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                    <br>

                                </div>

                                {{-- tipo (texto o listas)  --}}
                                <div class="w-4/12 mr-2">
                                    <div x-data="main_tipo_origen()"> 

                                        {{-- radio button para el tipo origen --}}
                                        <input type="radio" wire:model="arr_columnas_configurables.{{$num_col}}.2" value="0" class=""><span class="ml-2 text-gray-700">Texto<br></span>
                                        <input type="radio" wire:model="arr_columnas_configurables.{{$num_col}}.2" value="3" class=""><span class="ml-2 text-gray-700">Área de texto<br></span>
                                        <input type="radio" wire:model="arr_columnas_configurables.{{$num_col}}.2" value="1" class=""><span class="ml-2 text-gray-700">Lista desde valores<br></span>
                                        <input type="radio" wire:model="arr_columnas_configurables.{{$num_col}}.2" value="2" class=""><span class="ml-2 text-gray-700">Lista desde tabla<br></span>

                                        {{-- Si se escogió lista desde valores  --}}
                                        {{-- <div class="mt-10 " x-show="arr_columnas_configurables_alpine[{{$num_col}}][3] == 1"> --}}
                                        <div class="col-span-2 mt-4 -ml-60" x-show="arr_columnas_configurables_alpine[{{$num_col}}][2] == 1">
                                            <label class=" font-bold text-base my-auto mb-2 ml-1 text-gray-600">Digite los valores de la lista, cada linea corresponde a un valor:</label>
                                            <div class=" ">
                                                <textarea wire:model="arr_columnas_configurables.{{$num_col}}.4"  cols="46" rows="6" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
                                            </div>
                                        </div>  
                                        {{-- No existe @error ya que se validara que sea obligatorio y menor a 254 
                                        caracteres en el método submit_multivariable() (en el lado servidor) --}}

                                        {{-- Si se escogió lista desde tabla  --}}
                                        {{-- <div class="border border-green-500  mt-4 -ml-60" x-show="arr_columnas_configurables_alpine[{{$num_col}}][2] == 2"> --}}
                                        <div class="col-span-2 mt-4 -ml-60" x-show="arr_columnas_configurables_alpine[{{$num_col}}][2] == 2">
                                            {{-- Seleccionar tabla  --}}
                                            {{-- <select wire:change="tabla_escogida($event.target.value)" wire:model="seleccion_tabla" id="idtabla" class="mb-4 shadow-lg px-3   border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"> --}}
                                            <select wire:change="tabla_escogida($event.target.value , {{$num_col}})" wire:model="arr_columnas_configurables.{{$num_col}}.5"  class="mb-4 shadow-lg px-3   border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                                <option value="">Seleccione tabla...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                @foreach ($arr_tablas as $una_tabla)
                                                    <option value="{{$una_tabla['id']}}">{{$una_tabla['titulo']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                @endforeach
                                            </select>

                                            {{-- Campos disponibles y campos a mostrar  --}}
                                            {{-- <div class="border border-yellow-500 flex " x-show="arr_columnas_configurables_alpine[{{$num_col}}][2] == 2"> --}}
                                            <div class="flex " x-show="arr_columnas_configurables_alpine[{{$num_col}}][2] == 2">
                                                {{-- campos disponibles --}}
                                                {{-- <div class="border border-blue-500 mb-4 w-5/12"> --}}
                                                <div class="mb-4 w-5/12">
                                                    <label class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Campos disponibles:</label>
                                                    <div class="w-12/12">   
                                                        {{-- el evento doble-click se hace aprovechando el div alpine dentro del cual estamos  --}}
                                                        {{-- <select x-on:dblclick="$wire.dobleClick_disponibles($event.target.value , $event.target.innerText)" size="{{count($arr_columnas_configurables[6])}}"  class="bg-none shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"> --}}
                                                        @if (isset($arr_columnas_configurables[$num_col][6]))
                                                            <select  x-on:dblclick="$wire.dobleClick_disponibles($event.target.value , $event.target.innerText , {{$num_col}})" size="{{$arr_columnas_configurables[$num_col][8]}}"  class="bg-none shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                                                @if (count($arr_columnas_configurables[$num_col][6]) !== 0)
                                                                    @foreach ($arr_columnas_configurables[$num_col][6] as $fila_un_disponible)
                                                                            <option value="{{$fila_un_disponible['id']}}">{{$fila_un_disponible['titulo']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                                            
                                                                    @endforeach                                                
                                                                @endif                                                                
                                                            </select>
                                                        @endif
                                                    </div>   
                                                    <div class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-right text-gray-600">
                                                        Doble click para pasarlos <span class="text-blue-500 text-bold text-3xl">===></span>
                                                    </div>
                                                </div>
    
                                                {{-- espacio entre las dos listas  --}}
                                                <div class="mb-4 w-2/12">
                                                    
                                                </div>
    
                                                {{-- campos para mostrar --}}
                                                {{-- <div class="border border-red-500 mb-4 w-5/12"> --}}
                                                <div class="mb-4 w-5/12">
                                                    <label class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Campos para mostrar</label>
                                                    <div class="w-12/12">
                                                        {{-- <select x-on:dblclick="$wire.dobleClick_mostrar($event.target.value , $event.target.innerText)" wire:model="seleccion_campos_mostrar" size="{{$provis_campos_disponibles_canti}}" id="idcamposdisponibles" class="bg-none shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"> --}}
                                                        {{-- <select x-on:dblclick="$wire.dobleClick_mostrar($event.target.value , $event.target.innerText)" size="{{count($arr_columnas_configurables[6])}}" id="idcamposdisponibles" class="bg-none shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"> --}}
                                                        @if (isset($arr_columnas_configurables[$num_col][7]))
                                                            @php $campos_mostrar_canti = count($arr_columnas_configurables[$num_col][7]); @endphp
                                                            <select  x-on:dblclick="$wire.dobleClick_mostrar($event.target.value , $event.target.innerText , {{$num_col}})" size="{{$arr_columnas_configurables[$num_col][8]}}" class="bg-none shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                                                @if (count($arr_columnas_configurables[$num_col][7]) !== 0)
                                                                    @foreach ($arr_columnas_configurables[$num_col][7] as $un_mostrar)
                                                                        <option value="{{$un_mostrar['id']}}">{{$un_mostrar['titulo']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                                    @endforeach  


                                                                @endif
                                                            </select>
                                                        
                                                        @endif
                                                    </div>   
                                                    <div class="w-12/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">
                                                        <span class="text-blue-500 text-bold text-3xl"><===</span> Doble click para regresarlos
                                                    </div>                                
                                                </div>
                                            </div>
                                            {{-- Fin de campos disponibles y campos a mostrar  (si escogio origen de datos: tabla) --}}

                                        </div>
                                    </div>
                                    {{-- fin del x-data main_tipo_origen()  --}}
                                </div>

                                {{-- roles  --}}
                                <div class="w-4/12 mr-2">
                                    @foreach($arr_roles_disponibles as $index => $rol)
                                        {{-- <input  type="checkbox" wire:model="arr_columnas_configurables.{{$num_col}}.3.{{ $rol['id'] }}"  value="{{ $rol['id'] }}"   class="ml-8 mr-4"><label><span class="text-gray-700">{{ $rol['titulo'] }}</span></label><br>                                    --}}
                                        <input  type="radio" wire:model="arr_columnas_configurables.{{$num_col}}.3"  value="{{ $rol['id'] }}"   class="ml-8 mr-4"><label><span class="text-gray-700">{{ $rol['titulo'] }}</span></label><br>                                   
                                    @endforeach
                                </div>                                

                                {{-- si se trata de la segunda columna en adelante, debe 
                                mostrar el botón X (eliminar) 
                                Recordar que $num_col empieza desde  uno
                                --}}
                                @if ($num_col >= 2)
                                    <div class="w-1/12 mr-2">
                                        <button type="button" wire:click="eliminar_columna({{$num_col}})"  class=" bg-white rounded-2xl text-red-500  hover:bg-gray-300  hover:text-red-700">
                                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>  
                                @else 
                                    <div class="w-1/12 mr-2">

                                    </div>  
                                @endif

                            </div>    
                            {{-- fin del class 'flex'  --}}
                        @endfor 
                        {{-- Mostrar errores de arr_columnas_configurables:  --}}
                        <br><br>                          
                        @error('arr_columnas_configurables.*.1')
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror  
                        @error('arr_columnas_configurables.*.2') 
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror  
                        @error('arr_columnas_configurables.*.3') 
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror  
                        @error('arr_columnas_configurables.*.4') 
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror  
                        @error('arr_columnas_configurables.*.5') 
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror  
                        @error('arr_columnas_configurables.*.7') 
                            <div  class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
                        @enderror  

                    </div>

                    <div>
                        <div class="flex mb-4" >
                            <div class="w-4/12 ml-4 ">
                                {{-- botón agregar columna: --}}
                                <button type="button" wire:click="agregar_columna" class="w-full mt-4 bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3">
                                    <svg class="inline-block align-bottom  text-white fill-current w-6 h-6 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/></svg>
                                    <span class="align-text-bottom font-semibold">Agregar columna</span>
                                </button>
                            </div>

                            <div class="w-4/12 ml-4 ">
                                {{-- botón grabar: --}}
                                <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3">
                                    <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="currentColor" d="M14,2A8,8 0 0,0 6,10A8,8 0 0,0 14,18A8,8 0 0,0 22,10H20C20,13.32 17.32,16 14,16A6,6 0 0,1 8,10A6,6 0 0,1 14,4C14.43,4 14.86,4.05 15.27,4.14L16.88,2.54C15.96,2.18 15,2 14,2M20.59,3.58L14,10.17L11.62,7.79L10.21,9.21L14,13L22,5M4.93,5.82C3.08,7.34 2,9.61 2,12A8,8 0 0,0 10,20C10.64,20 11.27,19.92 11.88,19.77C10.12,19.38 8.5,18.5 7.17,17.29C5.22,16.25 4,14.21 4,12C4,11.7 4.03,11.41 4.07,11.11C4.03,10.74 4,10.37 4,10C4,8.56 4.32,7.13 4.93,5.82Z" />
                                    </svg>
                                    <span class="align-text-bottom font-semibold">Crear elemento</span>
                                </button>
                            </div>
                    
                            <div class="w-4/12 ml-4 pr-4">
                                {{-- botón cancelar : --}}
                                <button type="button"  wire:click="cerrar_modal_multivariable" class="w-full mt-4 bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3">
                                    <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="align-text-bottom font-semibold">Cancelar</span>
                                </button>
                            </div>
                        </div>
                    </div>  


                        
                        {{-- @include('livewire.parciales.modales.botones' , ['metodo_cerrar_modal' => 'cerrar_modal_multivariable()']) --}}
    
                    {{-- </div> --}}
                </form>
            </div>

        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-dialog-modal>    
</div>

<script>
    function main_tipo_origen(){
        return {
            arr_columnas_configurables_alpine : @entangle('arr_columnas_configurables'),
        }
    }
</script>
