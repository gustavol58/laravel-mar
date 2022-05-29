<div>
    @livewire('menu-own')
{{-- {{dd($errors)}} --}}
{{-- {{dd($arr_para_tipos_producto)}} --}}
    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
        {{-- 
            =====================================================================================
                Título
            ===================================================================================== 
        --}}
        <div class="">
            @if ($operacion == 'crear')
                <h1 class="font-bold text-xl uppercase">Ingresar nuevo pedido.</h1>
            @elseif($operacion == 'modificar')
                <h1 class="font-bold text-xl uppercase">Modificación del pedido número: {{$modificar_pedido_encab_id}}</h1>
            @else
                <h1 class="font-bold text-xl uppercase">*** error ***</h1>
            @endif
        </div>

        {{-- 
            =====================================================================================
                Formulario
            ===================================================================================== 
        --}}
        <form wire:submit.prevent="submit_pedido()" enctype="multipart/form-data">  
            <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
                {{-- 
                    ==========================================================================
                        Encabezado del pedido
                    ==========================================================================
                --}}                
                <div class="grid grid-cols-2">
                    {{-- cliente  --}}
                    <div class="mr-6">
                        <label class="block font-bold text-base my-auto ml-1">
                            Cliente
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <select wire:change="llenar_dir_entrega($event.target.value)" wire:model="cliente_id" id="idcliente_id" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                <option value="">Seleccione cliente...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                @foreach ($arr_para_clientes as $un_cliente)
                                    <option value="{{$un_cliente->id}}">{{$un_cliente->nom_cliente}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                @endforeach
                            </select>
                            @error('cliente_id') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>
                    </div>     
                    
                    {{-- dirección de entrega --}}
                    <div class="mr-6 col-2">
                        <label class="block font-bold text-base my-auto ml-1">
                            Dirección de entrega
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <input wire:model="dir_entrega" type="text" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                            @error('dir_entrega') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>                     
                    </div>  
                    
                    {{-- observaciones del pedido --}}
                    <div class="mt-6 mr-6  col-span-2">
                        <label class="block font-bold text-base my-auto ml-1">
                            Observaciones del pedido
                        </label>
                        <div class="relative flex w-full flex-wrap items-stretch mb-3">
                            <textarea rows="1" cols="45" wire:model="obs" id="idobs" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
                            @error('obs') <span class="text-red-500">{{ $message }}</span> @enderror   
                        </div>  
                    </div>  
                </div>
                {{-- fin del encabezado, grid-cols-2 --}}

                {{-- 
                    ==========================================================================
                        Detalle (productos) del pedido
                    ==========================================================================
                --}}                
                <fieldset class="border border-solid border-gray-300 p-3 mt-6">
                    <legend class="text-lg font-bold">Productos:&nbsp;&nbsp;&nbsp;</legend>

                    {{-- Cabeceras de cada columna  --}}
                    <div class="flex">
                        <div class="mr-2" style="width: 22%;">
                            Tipo de producto
                            <span class="text-red-500 ml-1">*</span>
                        </div>
                        <div class="mr-2" style="width: 22%;">
                            Producto
                            <span class="text-red-500 ml-1">*</span>
                        </div>
                        <div class="mr-2" style="width: 14%;">
                            Categoria
                            <span class="text-red-500 ml-1">*</span>
                        </div>
                        <div class="mr-2" style="width: 8%;">
                            Cantidad
                            <span class="text-red-500 ml-1">*</span>
                        </div>
                        <div class="mr-2" style="width: 10%;">
                            Precio unitario
                            <span class="text-red-500 ml-1">*</span>
                        </div>
                        <div class="mr-2" style="width: 18%;">
                            Observaciones
                        </div>
                        <div class="mr-2" style="width: 8%;">
                            &nbsp;
                        </div>
                    </div>        

                    @for ($i = 0; $i < $filas_detalles; $i++)
                        <div class="flex">
                            {{-- tipo de producto  --}}
                            <div class="mr-4" style="width: 22%;">
                                <select wire:change="llenar_select_productos($event.target.value , '{{$i}}')" wire:model="arr_detalle_productos.{{$i}}.tipo_producto_id" id="idtipo_producto_id{{$i}}" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                    <option value="">Seleccione...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    @foreach ($arr_para_tipos_producto as $un_tipo_producto)
                                        <option value="{{$un_tipo_producto->id}}">{{$un_tipo_producto->tipo_producto_nombre}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    @endforeach
                                </select>
                                @error("arr_detalle_productos.$i.tipo_producto_id") <span class="text-red-500">{{ $message }}</span> @enderror   
                
                            </div>  
                
                            {{-- producto  --}}
{{-- @if ($i == 1) --}}
{{-- {{dd($arr_detalle_productos)}} --}}
{{-- {{dd($arr_para_productos)}} --}}
{{-- @endif                             --}}
                            <div class="mr-4" style="width: 22%;">
                                <select  wire:model="arr_detalle_productos.{{$i}}.producto_id" id="idproducto_id{{$i}}" class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                    <option value="">Seleccione...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    @if(isset($arr_para_productos[$i]))
                                        {{-- 
                                            Explicación del if interno del foreach: el método 
                                            llamado por el evento del select tipo 
                                            de producto (llenar_select_productos()), la primera 
                                            vez crea un obj en cambio la segunda vez, 
                                            "misteriosamente" un arr, esa es la razón 
                                            de ser del if:  
                                        --}}
                                        @foreach ($arr_para_productos[$i] as $un_producto_)
                                            @if (is_array($un_producto_))
                                                <option value="{{$un_producto_['id']}}">{{$un_producto_['codigo']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                            @else
                                                <option value="{{$un_producto_->id}}">{{$un_producto_->codigo}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error("arr_detalle_productos.$i.producto_id") <span class="text-red-500">{{ $message }}</span> @enderror   
                            </div>  
                
                            {{-- categoria  --}}
                            <div class="mr-4" style="width: 14%;">
                                <input type="radio" value="1" wire:model="arr_detalle_productos.{{$i}}.categoria" id="idcategoria1{{$i}}" class="mr-4 ">Nuevo<br>
                                <input type="radio" value="2" wire:model="arr_detalle_productos.{{$i}}.categoria" id="idcategoria2" class="mr-4 ">Reprogramación<br>
                                @error("arr_detalle_productos.$i.categoria") <span class="text-red-500">{{ $message }}</span> @enderror   
                            </div>  
                
                            {{-- cantidad  --}}
                            <div class="mr-4" style="width: 8%;">
                                <input wire:model="arr_detalle_productos.{{$i}}.canti" type="text" id="idcanti{{$i}}aaa" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                @error("arr_detalle_productos.$i.canti") <span class="text-red-500">{{ $message }}</span> @enderror   
                            </div>  
                
                            {{-- precio unitario  --}}
                            <div class="mr-4" style="width: 10%;">
                                <input wire:model="arr_detalle_productos.{{$i}}.precio" type="text" id="idprecio{{$i}}" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                                @error("arr_detalle_productos.$i.precio") <span class="text-red-500">{{ $message }}</span> @enderror   
                            </div>  

                            {{-- observaciones del producto  --}}
                            <div class="mr-4" style="width: 18%;">
                                <textarea rows="1" cols="45" wire:model="arr_detalle_productos.{{$i}}.obs_producto" id="idobs_producto{{$i}}" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors"></textarea>
                            </div>  

                            {{-- agregar o eliminar producto --}}
                            <div class="mr-4 mt-2" style="width: 8%;">
                                @if ($i == 0)
                                    <button type="button" wire:click="agregar_detalle_producto()"  class=" bg-white rounded-2xl text-blue-500  hover:bg-gray-300  hover:text-blue-700">
                                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M17,13H13V17H11V13H7V11H11V7H13V11H17M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                @else 
                                    {{-- El botón ELIMINAR solo aparece en 'crear' 
                                         o en 'modificar' si es un pedido_conse nuevo 
                                    --}}
                                    @if ($operacion == 'crear')
                                        <button type="button" wire:click="eliminar_detalle_producto({{$i}})"  class=" bg-white rounded-2xl text-red-500  hover:bg-gray-300  hover:text-red-700">
                                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    @elseif($operacion == 'modificar')
                                        @if($i >= $filas_detalles_original)
                                            <button type="button" wire:click="eliminar_detalle_producto({{$i}})"  class=" bg-white rounded-2xl text-red-500  hover:bg-gray-300  hover:text-red-700">
                                                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endfor
                </fieldset>

                {{-- 
                    ==========================================================================
                        Mensajes de validaciones/grabaciones
                    ==========================================================================
                --}}                 
                <div class="">
                    {{-- Muestra mensaje de grabación correcta --}}
                    @if ($mensaje_correcto)
                        <div  class="mb-4 mt-4">
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
                                        {{ $mensaje_correcto }}
                                    </div>
                                </div>
                            </div>        
                        </div>
                    @endif

                    {{-- Muestra mensaje de error en la validación lado servidor --}}
                    @if ($mensaje_error)
                        <div  class="mb-4 mt-4">
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
                                        El producto no pudo ser grabado.
                                    </div>
                                    <div class="alert-description text-sm text-red-600">
                                        {{ $mensaje_error }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- fin de los mensajes validaciones / grabación  --}}    

                {{-- 
                    ==========================================================================
                        Botones
                    ==========================================================================
                --}}                  
                @php
                    if($operacion == 'crear'){
                        $texto_boton = "Crear pedido";
                        $ancho_boton = "w-1/4";
                    }else{
                        $texto_boton = "Grabar modificaciones del pedido";
                        $ancho_boton = "w-2/4";
                    }    
                @endphp
                <div class="flex">
                    <button type="submit" class="mx-auto {{$ancho_boton}} mt-6 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold"> {{$texto_boton}}</button> 

                    @if ($operacion == 'crear')
                        <div class="mx-auto w-1/4 mt-6">
                            <button type="button" wire:click="btn_limpiar()" class="w-full bg-blue-600 hover:bg-blue-400 focus:bg-blue-400 text-white rounded-lg px-3 py-3 font-semibold"> Limpiar</button>
                        </div>
                    @endif

                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" wire:click="mostrar_modal_btn_cancelar()" class="w-full bg-red-600 hover:bg-red-400 focus:bg-red-400 text-white rounded-lg px-3 py-3 font-semibold">
                             Cancelar - Regresar
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>


    {{-- 
        =====================================================================================
            MODAL para avisar que hay info sin grabar, antes de cerrar el formu de pedidos
        ===================================================================================== 
    --}}    
    <x-jet-dialog-modal wire:model="modal_visible_cancelar">
        <x-slot name="title">
            <br><br>
            <center>¿Está seguro de perder la información digitada?</center>
        </x-slot>
    
        <x-slot name="content"> 
                {{-- Botones --}}
                <div class="flex mx-auto">
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" class=" w-full bg-red-600 hover:bg-red-400 focus:bg-red-400  text-white rounded-lg px-3 py-3 font-semibold" wire:click="btn_cancelar()"> Si, perder la info</button>
                    </div>
                    <div class="mx-auto w-1/4 mt-6">
                        <button type="button" class="w-full bg-green-500 hover:bg-green-400 focus:bg-green-400 text-white rounded-lg px-3 py-3 font-semibold" wire:click="$set('modal_visible_cancelar', false)"> No, regresar</button>
                    </div>
                </div> 
        </x-slot>
    
        <x-slot name="footer">

        </x-slot>
    </x-jet-confirmation-modal>    
    {{-- FIN DEL MODAL para avisar que hay info sin grabar, antes de cerrar la creación de productos --}}
</div>



