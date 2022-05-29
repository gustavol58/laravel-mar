<div>
    @livewire('menu-own')

    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
        <div class="">
            <h1 class="font-black text-4xl ">Mantenimiento de productos.</h1>
            <h3 class="font-black text-2xl ">Escoja el Tipo de producto:</h3>
        </div>

        {{-- botones  --}}
        <div class="flex  my-4">
            {{-- botón REGRESAR  --}}
            <div class="w-1/4">   
                <a href="{{route('dashboard')}}" >                
                    <button type="button"  
                        class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold">
                        <svg class="inline-block align-text-top w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                         Regresar
                    </button>
                 </a>  
            </div>      
        </div>      

        {{-- Registros: --}}
        <table class="table-fixed">
            <thead class="justify-between">
                <tr class="bg-green-500">
                    <th class="">
                        <span class="text-white font-bold">Tipo de producto</span>
                    </th>
                    <th class=""> 
                        <span class="text-white font-bold">Prefijo</span>
                    </th>
                    <th class="">

                    </th>
                </tr>
            </thead>
{{-- {{dd($tipos_producto)}} --}}
            <tbody class="bg-gray-200">
                @foreach ($tipos_producto as $fila)
                    <tr class="bg-white border-4 border-gray-200">
                        {{-- Nombre largo del tipo de producto  --}}
                        <td class="px-2 border border-gray-300">
                            <span>{{$fila->tipo_producto_nombre}}</span>
                        </td>

                        {{-- Prefijo del tipo de producto  --}}
                        <td class="px-2 border border-gray-300">
                            <span>{{$fila->prefijo}}</span>
                        </td>
   
                        {{-- href para escoger tipo de producto  --}}
                        <td class="border border-gray-300 px-4 text-green-500">
                            <a href="{{route('ver-formu' , [
                                    'tipo_producto_id' => $fila->id , 
                                ])}}">
                                    Ir a productos ...
                            </a>
                        </td>                        
                    </tr>
                @endforeach
            </tbody>
        </table>  
    </div>    




</div>
