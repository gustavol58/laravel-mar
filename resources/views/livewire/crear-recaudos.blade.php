<div>
    {{-- {{$errors}} --}}
    {{-- {{dd(Auth::user()->id)}} --}}
    @livewire('menu-own')

    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">

        <div class="flex mb-10 h-8">
            <div class="w-1/4">
                <h1 class="font-bold text-xl uppercase">Ingreso de recaudos</h1>
            </div>
            <div class="w-3/4">
                {{-- Muestra mensaje de grabación correcta --}}
                @if ($mensaje)
                    <div  class="mb-4">
                        <div class="alert flex flex-row items-center bg-green-200 pl-5 pt-3 pb-2 rounded border-b-2 border-green-300">
                            <div class="alert-icon flex items-center bg-green-100 border-2 border-green-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
                                <span class="text-green-500">
                                    <svg fill="currentColor"
                                        viewBox="0 0 20 20"
                                        class="h-6 w-6">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="alert-content ml-4">
                                <div class="alert-title font-semibold text-lg text-green-800">
                                    Grabación correcta.
                                </div>
                                <div class="alert-description text-sm text-green-600">
                                    {{ $mensaje }}
                                </div>
                            </div>
                        </div>        
                    </div>
                @endif
                {{-- Muestra mensaje de error en la validación lado servidor --}}
                @if ($mensaje_error)
                    <div  class="mb-4">
                        <div class="alert flex flex-row items-center bg-red-200 pl-5 pt-3 pb-2 rounded border-b-2 border-red-300">
                            <div class="alert-icon flex items-center bg-red-100 border-2 border-red-500 justify-center h-10 w-10 flex-shrink-0 rounded-full">
                                <span class="text-red-500">
                                    <svg fill="currentColor"
                                        viewBox="0 0 20 20"
                                        class="h-6 w-6">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="alert-content ml-4">
                                <div class="alert-title font-semibold text-lg text-red-800">
                                    El recaudo no pudo ser grabado.
                                </div>
                                <div class="alert-description text-sm text-red-600">
                                    {{ $mensaje_error }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
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
                    <select wire:model="cliente_id" id="idcliente_id" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        <option value="">Seleccione cliente...</option>
                        @foreach ($clientes as $cliente)
                            @if(Auth::user()->hasRole('comer'))
                                <option value="{{$cliente->id}}">{{$cliente->nom_cliente}}</option>
                            @else
                                <option value="{{$cliente->id}}">{{$cliente->nom_cliente}} - ({{$cliente->comercial}})</option>
                            @endif
                        @endforeach
                    </select>
                    @error('cliente_id') <span class="text-red-500">{{ $message }}</span> @enderror 
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

            {{-- <div x-data="{ tipo_alpine : @entangle('tipo') }"> --}}
            <div x-data="main()">
                {{-- Valor --}}
                <div class="flex mb-4" x-show="tipo_alpine == 1">
                    <label class="w-2/12 font-bold text-base my-auto mb-2 ml-1">Valor:</label>
                    <div class="w-10/12">
                        <input type="number" wire:model="valor" id="idvalor" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        @error('valor') <span class="text-red-500">{{ $message }}</span> @enderror   
                    </div>
                </div>                    

                {{-- Fecha --}}
                <div class="flex mb-4" x-show="tipo_alpine == 1">
                    <label class="w-2/12 font-bold text-base my-auto  mb-2 ml-1">Fecha del recaudo:</label>
                    {{-- debido a que el datapicker es de una libreria externa, se debe 
                    indicar a livewire que no lo actualice como los demás, para eso
                    son wire_ignore y wire:key --}}
                    <div class="w-10/12">
                        <div wire:ignore wire:key="a" >
                            <input
                                x-data
                                x-ref="fec_recaudo_ref"
                                x-init="new Pikaday({
                                    field: $refs.fec_recaudo_ref,
                                    defaultDate: moment().toDate(), 
                                    setDefaultDate: true,
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
                                class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"
                            >
                        </div>
                        @error('fec_recaudo') <span class="text-red-500">{{ $message }}</span> @enderror  
                    </div>                
                </div>

                {{-- Combo de consignaciones --}}
                <div class="flex mb-4" x-show="tipo_alpine == 2">
                    <label class="w-2/12 font-bold text-base my-auto  mb-2 ml-1">Consignación:</label>
                    <div class="w-10/12">
                        <select wire:model="consignacion_id" id="idconsignacion_id" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            <option value="">Seleccione consignación...</option>
                            @foreach ($consignaciones as $consignacion)
                                <option value="{{$consignacion->id}}">{{$consignacion->fecha}} :&nbsp;&nbsp;&nbsp; $ {{number_format($consignacion->valor, 2, '.', ',')}}&nbsp;&nbsp;&nbsp;&nbsp; - {{$consignacion->referencia}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                            @endforeach
                        </select>
                        @error('consignacion_id') <span class="text-red-500">{{ $message }}</span> @enderror 
                    </div>
                </div>                    

            </div>    
                
            <div class="flex">
                {{-- observaciones --}}
                <div class="w-1/2">
                    <label class="w-2/12 font-bold text-base my-auto  mb-2 ml-1">Observaciones:</label>
                    <div class="w-10/12">
                        <textarea rows="3" cols="45" wire:model="obs" id="idobs" class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
                    </div>
                </div>

                {{-- foto comprobante --}}
                <div class="w-1/2">
                    <label  class="w-2/12 font-bold text-base my-auto  mb-2 ml-1">Foto comprobante (Imagen, menor a 4 megas):</label>
                    {{-- div para 3 divisiones: Pedir el archivo. Vista previa. Botón cerrar. --}}
                    <div class="w-10/12 flex">
                        <div class="flex">
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

            <div class="flex">
                <button type="submit" class="mx-auto w-1/2 mt-6 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold"> Grabar</button>
                <div class="mx-auto w-1/4 mt-6">
                    <a href="{{route('ver-recaudos')}}" class="">
                        <button type="button" class="w-full bg-red-600 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold"> Cancelar</button>
                    </a>
                </div>
            </div>
        </form>


    </div>
</div>


{{-- El siguiente evento-gancho de javascript livewire tuvo que ser utilizado para poder 
hacer que después de grabar un mensaje, la fecha de recaudo se inicializara 
en la fecha actual, recordar que todo se debe a que la fecha de recaudo 
es independiente de los wire:model ya que es hecha con una libreria 
externa de js (pikaday) --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        Livewire.hook('message.processed', (el, component) => {
            var cliente = document.getElementById('idcliente_id').value;
            var valor = document.getElementById('idvalor').value;
            var obs = document.getElementById('idobs').value;
            if(cliente == '' && valor == '' && obs == ''){
                var f = new Date();
                var mes = f.getMonth() + 1;
                if(mes <= 9){ mes = '0' + mes;}
                var dia = f.getDate();
                if(dia <= 9){ dia = '0' + dia; }
                document.getElementById('idfec_recaudo').value = f.getFullYear() + "/" + mes + "/" + dia;
            }
        });
    });

    function main(){
        return {
            tipo_alpine : @entangle('tipo'),
        }
    }

</script>




