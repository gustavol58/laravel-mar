{{-- vista parcial llamada desde /markka-pruebas/laravel/resources/views/livewire/pedidos/formu/crear-formu.blade.php --}}
{{-- Esta vista parcial se encarga  de colocar * si el campo se debe digitar obligatoriamente --}}
    <label class="block font-bold text-base my-auto ml-1">
        {{$fila->cabecera}}
        @if ($fila->obligatorio)
            <span class="text-red-500">*</span>
        @endif
    </label>


