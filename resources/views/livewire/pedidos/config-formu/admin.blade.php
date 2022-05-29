<div>
    @livewire('menu-own')

    @if (session()->has('message_cuerpo'))
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
                        {!! session('message_titulo') !!}
                    </div>
                    <div class="alert-description text-sm text-red-600">
                        {!! session('message_cuerpo') !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
        <div class="">
            <h1 class="font-black text-4xl ">Configuración de Tipos de producto</h1>
        </div>

        {{-- botones  --}}
        <div class="flex  my-4">
            {{-- botón NUEVO  --}}
            <div class="w-1/4">  
                <button type="button" wire:click="mostrar_modal_admin()" class=" bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold"><svg class="inline-block align-bottom  text-white fill-current w-6 h-6 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/></svg> Crear</button>
            </div>      
            {{-- botón REGRESAR  --}}
            <div class="w-1/4">   
                <a href="{{route('dashboard')}}" >                
                    <button type="button"  
                    class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                    <svg class="inline-block align-text-top w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg> Regresar</button>
                 </a>  
            </div>      
        </div>      

        {{-- Registros: --}}
        <table class="table-fixed">
            <thead class="justify-between">
                <tr class="bg-green-500">
                    {{-- <th class="">
                        <span class="px-2 text-white font-bold">Nro</span>
                    </th> --}}
                    <th class="">
                        <span class="text-white font-bold">Tipo de producto</span>
                    </th>
                    <th class="">

                    </th>                    
                    <th class="" colspan="2">
                        <span class="text-white font-bold">Prefijo</span>
                    </th>
                    <th class="">

                    </th>                      
                    <th class="">

                    </th>
                    <th class="">

                    </th>
                </tr>
            </thead>

            <tbody class="bg-gray-200">
                @foreach ($tipos_producto as $fila)
                    <tr class="bg-white border-4 border-gray-200">
                        {{-- Nombre largo del tipo de producto  --}}
                        <td class="px-2 border-none">
                            <span>{{$fila['tipo_producto_nombre']}}</span>
                        </td>
                        {{-- Botón para editar nombre largo del tipo de producto --}}
                        <td class="border-none px-4 text-yellow-500">
                            <a href="{{route('modificar-nombre-largo' , [
                                        'tipo_producto_recibido_id' => $fila['id'] , 
                                        'tipo_producto_recibido_nombre' => $fila['tipo_producto_nombre'], 
                                        'tipo_producto_recibido_slug' => $fila['tipo_producto_slug'] 
                                    ])}}">
                                <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                            </a>
                        </td>    

                        {{-- Prefijo  --}}
                        <td class="  border-r-0   px-2 border border-gray-300">
                            <span>{{$fila['prefijo']}}</span>
                        </td>
                        @if ($fila['editar_prefijo'])
                            {{-- Botón para editar prefijo, siempre y cuando formu__... no tenga rgtos --}}
                            <td class="border-none px-4 text-yellow-500">
                                <a href="{{route('modificar-prefijo' , [
                                            'tipo_producto_recibido_id' => $fila['id'] , 
                                            'tipo_producto_recibido_nombre' => $fila['tipo_producto_nombre'], 
                                            'tipo_producto_recibido_slug' => $fila['tipo_producto_slug'] ,
                                            'tipo_producto_recibido_prefijo' => $fila['prefijo'] , 
                                        ])}}">
                                    <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                </a>
                            </td> 
                        @else 
                            <td>
                                
                            </td>                             
                        @endif
                         

                        {{-- href para agregar campos al tipo de producto  --}}
                        <td class="border border-gray-300 px-4 text-green-500">
                            <a href="{{route('generar-formu-index' , [
                                    'tipo_producto_recibido_id' => $fila['id'] , 
                                    'tipo_producto_recibido_nombre' => $fila['tipo_producto_nombre'], 
                                    'tipo_producto_recibido_slug' => $fila['tipo_producto_slug'] 
                                ])}}">
                                    Configurar formulario ...
                            </a>
                        </td>                        
                    </tr>
                @endforeach
            </tbody>
        </table>  
    </div>

    {{-- MODAL para pedir un nuevo tipo de producto --}}
    <x-jet-dialog-modal wire:model="modal_visible_admin" >

        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Agregar nuevo tipo de producto.</span>       
                </div>
            </div>
        </x-slot>
    
        <x-slot name="content"> 

            <form  x-on:click.away="$wire.cerrar_modal_admin()" wire:submit.prevent="submit_admin()">

                <div class="mt-3">
                    {{-- nombre del tipo de producto  --}}
                    <div class="flex mb-4">
                        <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Nombre: <span class="text-red-500">*</span></label>
                        <div class="w-8/12">
                            <input type="text" id="idFocus" wire:model="admin_cabecera"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                    </div>  
                    @error('admin_cabecera')
                        <span  class="text-red-500">{{ $message }}</span> 
                    @enderror 

                    {{-- prefijo  --}}
                    <div class="flex mb-4">
                        <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">Prefijo:</label>
                        <div class="w-4/12">
                            <input pattern="[A-Z]{2,254}" type="text" wire:model="admin_prefijo"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        </div>
                        <div class="group relative w-28 text-center">
                                    <div class="w-4/12 ml-4">
                                        <button type="button"  
                                            class="ml-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                                            <svg style="width:12px;height:12px" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M10,19H13V22H10V19M12,2C17.35,2.22 19.68,7.62 16.5,11.67C15.67,12.67 14.33,13.33 13.67,14.17C13,15 13,16 13,17H10C10,15.33 10,13.92 10.67,12.92C11.33,11.92 12.67,11.33 13.5,10.67C15.92,8.43 15.32,5.26 12,5A3,3 0 0,0 9,8H6A6,6 0 0,1 12,2Z" />
                                            </svg>
                                        </button>
                                        <div class="opacity-0 w-28 bg-blue-400 text-white font-bold text-center text-xs rounded-lg py-2 absolute group-hover:opacity-100 bottom-full -left-1/2 ml-14 px-3 pointer-events-none">
                                            Campo opcional<br>Solo letras MAYÚSCULAS, mínimo 2, máximo 234.
                                            <svg class="absolute text-green-500 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255" xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0" /></svg>
                                        </div>   
                                    </div>
                        </div>
                    </div>  
                    @error('admin_prefijo')
                        <span  class="text-red-500">{{ $message }}</span> 
                    @enderror  

                    {{-- botones  --}}
                    <div class="flex mb-4" >
                        <div class="w-1/2 ml-4 ">
                            {{-- botón grabar: --}}
                            <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3">
                                <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor" d="M14,2A8,8 0 0,0 6,10A8,8 0 0,0 14,18A8,8 0 0,0 22,10H20C20,13.32 17.32,16 14,16A6,6 0 0,1 8,10A6,6 0 0,1 14,4C14.43,4 14.86,4.05 15.27,4.14L16.88,2.54C15.96,2.18 15,2 14,2M20.59,3.58L14,10.17L11.62,7.79L10.21,9.21L14,13L22,5M4.93,5.82C3.08,7.34 2,9.61 2,12A8,8 0 0,0 10,20C10.64,20 11.27,19.92 11.88,19.77C10.12,19.38 8.5,18.5 7.17,17.29C5.22,16.25 4,14.21 4,12C4,11.7 4.03,11.41 4.07,11.11C4.03,10.74 4,10.37 4,10C4,8.56 4.32,7.13 4.93,5.82Z" />
                                </svg>
                                <span class="align-text-bottom font-semibold">Crear</span>
                            </button>
                        </div>
            
                        <div class="w-1/2 ml-4 pr-4">
                            {{-- botón cancelar : --}}
                            <button type="button"  wire:click="cerrar_modal_admin()" class="w-full mt-4 bg-red-500 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3">
                                <svg class="inline-block align-text-top  w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="align-text-bottom font-semibold">Cancelar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>   







</div>

<script>
    // este fragmento javascript se usa unica y exclusivamente para el manejo 
    // correcto del autofocus del formulario, sin embargo en los otros modales se 
    // utiliza una forma alternativa: usanddo AlpineJs
    // https://stackoverflow.com/questions/1096436/document-getelementbyidid-focus-is-not-working-for-firefox-or-chrome/41474220
    document.addEventListener('livewire:load', function () {
        window.livewire.on('focusInicial' , function (){
            window.setTimeout(function (){
                document.getElementById("idFocus").focus() ;
            }, 0);
        });
    });
</script>






 


