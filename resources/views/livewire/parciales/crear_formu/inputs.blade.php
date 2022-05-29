{{-- vista parcial llamada desde /markka-pruebas/laravel/resources/views/livewire/pedidos/formu/crear-formu.blade.php --}}
    <div class="relative flex w-full flex-wrap items-stretch mb-3">
        <input {{$disabled1___}} wire:model="arr_input_campos.{{$fila->slug}}" type="text" class="w-full shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors {{$fondo1___}}">
        <span class="absolute right-0 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md">
            <svg class="inline-block align-text-top w-6 h-6" >
                <path fill="currentColor" d="{{$icono_input_d}}" />
            </svg>  
        </span>
        @error('arr_input_campos.'.$fila->slug) <span class="text-red-500">{{ $message }}</span> @enderror   
    </div>  