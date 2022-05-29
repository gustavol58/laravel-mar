<div>
    {{-- {{$recaudo_id}} --}}
    @livewire('menu-own')

    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">


            <div class="">
                <h1 class="font-bold text-xl mb-4">Modificación del recaudo número {{$recaudo_id}} (Estado: {{$estado_texto}})</h1>
            </div>

            <form wire:submit.prevent="submit(document.getElementById('idfec_recaudo').value)">
                {{-- Categoria --}}
                <div class="flex mb-4">
                    <label class="w-2/12 font-bold text-base my-auto mb-2 ml-1">Categoria:</label>
                    <div class="w-10/12">
                        <input type="radio" wire:model="categoria" value="1" name="categoria"  ><span class="ml-2 text-gray-700">Anticipo</span>
                        <input type="radio" wire:model="categoria" value="2" name="categoria"  class="ml-6"   ><span class="ml-2 text-gray-700">Pago facturas</span>
                    @error('categoria') <span class="text-red-500">{{ $message }}</span> @enderror   
                    </div>
                </div>

                {{-- Cliente --}}
                <div class="flex mb-4">
                    <label class="w-2/12 font-bold text-base my-auto  mb-2 ml-1">Cliente</label>
                    <div class="w-10/12">
                        <select wire:model="cliente_model_id" id="idcliente_id" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            <option value="">Seleccione cliente...</option>
                            @foreach ($clientes_combo as $un_cliente)
                                <option value="{{$un_cliente->id}}">{{$un_cliente->nom_cliente}} - ({{$un_cliente->comercial}})</option>
                            @endforeach
                        </select>
                        @error('cliente_model_id') <span class="text-red-500">{{ $message }}</span> @enderror 
                    </div>
                </div>
    
                {{-- Valor --}}
                <div class="flex mb-4">
                    <label class="w-2/12 font-bold text-base my-auto mb-2 ml-1">Valor:</label>
                    <div class="w-10/12">
                        <input type="number" wire:model="valor" id="idvalor" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        @error('valor') <span class="text-red-500">{{ $message }}</span> @enderror   
                    </div>
                </div>
    
                {{-- Tipo --}}
                <div class="flex mb-4">
                    <label class="w-2/12 font-bold text-base my-auto mb-2 ml-1">Tipo:</label>
                    <div class="w-10/12">
                        <input type="radio" wire:model="tipo" value="1" name="tipo"  ><span class="ml-2 text-gray-700">Efectivo</span>
                        <input type="radio" wire:model="tipo" value="2" name="tipo"  class="ml-6"   ><span class="ml-2 text-gray-700">Consignación</span>
                       @error('tipo') <span class="text-red-500">{{ $message }}</span> @enderror   
                    </div>
                </div>
    
                {{-- Fecha --}}
                <div class="flex mb-4">
                    <label class="w-2/12 font-bold text-base my-auto  mb-2 ml-1">Fecha del recaudo:</label>
                    {{-- debido a que el datapicker es de una libreria externa, se debe 
                    indicar a livewire que no lo actualice como los demás, para eso
                    son wire_ignore y wire:key --}}
                    <div class="w-10/12">
                        <div wire:ignore wire:key="a">
                            <input
                                x-data
                                x-ref="fec_recaudo_ref"
                                x-init="new Pikaday({
                                    field: $refs.fec_recaudo_ref,
                                    // defaultDate: moment().toDate(), 
                                    // setDefaultDate: true,
                                    i18n: {
                                        previousMonth : 'Anterior',
                                        nextMonth     : 'Siguiente',
                                        months        : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                                        weekdays      : ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
                                        weekdaysShort : ['Dom','Lun','Mar','Mie','Jue','Vie','Sáb']
                                    },
                                    format: 'YYYY-MM-DD',
                                    onSelect: function(date) {
                                        // alert('hola...');
                                        // field.value = this.getMoment().format('YYYY-MM-DD');
                                    }
                                })"
                                type="text"
                                id="idfec_recaudo" 
                                value="{{$fec_recaudo}}"
                                class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                            >
                        </div>
                        @error('fec_recaudo') <span class="text-red-500">{{ $message }}</span> @enderror  
                    </div>                
                </div>

                {{-- Valor del asiento, solo se habilita si el recaudo está asentado (estado 3) --}}
                @if($estado == 3)   
                    <div class="flex mb-4">
                        <label class="w-2/12 font-bold text-base my-auto mb-2 ml-1">Valor asentado:</label>
                        <div class="w-10/12">
                            <input type="number" wire:model="valor_asentado" id="idvalor_asentado" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            @error('valor_asentado') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>
                    </div> 
                @else
                    <div class="flex mb-4">
                        <label class="w-2/12 font-bold text-base my-auto mb-2 ml-1">Valor asentado:</label>
                        <div class="w-10/12">
                            <p class="text-gray-400">
                                pendiente por asiento...
                            </p>
                        </div>
                    </div> 
                @endif
      
                <div class="flex">
                    {{-- observaciones --}}
                    <div class="w-1/2">
                        <label class="w-2/12 font-bold text-base my-auto  mb-2 ml-1">Observaciones:</label>
                        <div class="w-10/12">
                            <textarea rows="3" cols="45" wire:model="obs" id="idobs" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
                        </div>
                    </div>
{{-- {{dd($foto_existe_nombre)}}    27nov2021 (por modificación productos....) ...   --}}
                    {{-- foto comprobante --}}
                    <div class="w-1/2">
                        <label  class="w-2/12 font-bold text-base my-auto  mb-2 ml-1">Foto comprobante (Imagen, menor a 4 megas):</label>
                        {{-- div para 3 divisiones: Pedir el archivo. Vista previa. Botón cerrar. --}}
                        <div class="w-10/12 flex">
                            <div class="flex">

                                {{-- <img src="{{str_replace('public','storage',asset($foto_existe_nombre))}}" alt="slider-image" class=""> --}}
                                @if($foto_existe_nombre !== '')
                                    <img src="{{asset('storage/'.$foto_existe_nombre)}}" style="width: 12rem; height: 5rem;">
                                @endif
                                

                                {{-- <img src="{{ asset("storage/imgHabitaciones/$hab->hab_urlimg" )}}" alt="" /> --}}


                                {{-- Primera división: Pedir el archivo. --}}
                                <div class="inline-block">
                                    {{-- <label class="w-64 flex flex-col items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide  border border-blue cursor-pointer hover:bg-blue hover:text-blue-400"> --}}
                                    <label class="w-64 flex  items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide  border border-blue cursor-pointer hover:bg-blue hover:text-blue-400">
                                        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                                        </svg>
                                        <span class="mt-2 ml-4 text-base leading-normal">Seleccione archivo...</span>
                                        <input type="file" 
                                            wire:model="foto" 
                                            id="idadjunto_{{$contador_foto}}" 
                                            class="hidden"
                                        >
                                    </label>
                                    <div>
                                        @error('foto') <span class="text-sm text-red-500 italic">{{ $message }}</span>@enderror
                                    </div>
                                    <div wire:loading wire:target="foto" class="text-sm text-gray-500 italic">Cargando...</div>
                                </div>
        
                                {{-- Segunda división: Vista previa archivo subido --}}
                                <div class="inline-block">
                                    @if($errors->has('foto'))
                                    @else 
                                        @if ($foto)
                                            @php
                                                try {
                                                    $url = $foto->temporaryUrl();
                                                    $this->photoStatus = true;
                                                }catch (RuntimeException $exception){
                                                    $this->photoStatus =  false;
                                                }
                                            @endphp
                                            @if($this->photoStatus)
                                            <div class="border rounded-lg ml-4">
                                                <img src="{{ $url }}" style="width: 12rem; height: 5rem;">
                                                @php
                                                    $boton_eliminar_foto = true
                                                @endphp
                                            </div>
                                            @else
                                                &nbsp;&nbsp;&nbsp;No selecciono un archivo correcto.<br>&nbsp;&nbsp;&nbsp;Debe ser una imagen no mayor a 4 megas.
                                            @endif
                        
                                        @endif
                                    @endif 
                                </div>
        
                                {{-- Tercera división: Botón eliminar foto: --}}
                                @if ($boton_eliminar_foto)
                                    <div class="inline-block my-auto ml-4">
                                        <button type="button" wire:click="reset_adjunto">
                                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
        
                            </div>
        
                        </div>
                    </div>
    
                </div>
    
                <div class="flex mx-auto">
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="submit" class=" w-full bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3 font-semibold"> Grabar cambios</button>
                    </div>
                    <div class="mx-auto w-1/4 mt-6">
                        <a href="{{route('ver-recaudos')}}" class="">
                            <button type="button" class="w-full bg-red-600 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold"> Cancelar</button>
                        </a>
                    </div>
                </div>
          
            </form>
    </div>
</div>
