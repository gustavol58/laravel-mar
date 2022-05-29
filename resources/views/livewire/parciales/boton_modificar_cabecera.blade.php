{{-- vista parcial llamada desde /markka-pruebas22/laravel/resources/views/livewire/pedidos/config-formu/config-index.blade.php --}}
{{-- el mr-6 hay que usarlo por el margen que se pierde al agregar el icon dentro del input text  --}}
{{-- 13sep2021: href para permitir modificar la cabecera del campo:  --}}
<a href="{{route('modificar-cabecera-campo' , [
    'formu_detalle_id' => $fila['id'],
    'cabecera_actual' => $fila['cabecera'],
    'tipo_producto_recibido_id' => $tipo_producto_recibido_id,
    'tipo_producto_recibido_nombre' => $tipo_producto_recibido_nombre,
    'tipo_producto_recibido_slug' => $tipo_producto_recibido_slug,
    ])}}">
        {{-- <svg style="width:18px;height:18px" viewBox="0 0 18 18" class="inline-block"> --}}
        <svg class="inline-block align-text-bottom w-6 h-6 text-yellow-500">
            {{-- <path fill="currentColor" d="M14.06,9L15,9.94L5.92,19H5V18.08L14.06,9M17.66,3C17.41,3 17.15,3.1 16.96,3.29L15.13,5.12L18.88,8.87L20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18.17,3.09 17.92,3 17.66,3M14.06,6.19L3,17.25V21H6.75L17.81,9.94L14.06,6.19Z" /> --}}
            <path fill="currentColor" d="M14.06,9L15,9.94L5.92,19H5V18.08L14.06,9M17.66,3C17.41,3 17.15,3.1 16.96,3.29L15.13,5.12L18.88,8.87L20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18.17,3.09 17.92,3 17.66,3M14.06,6.19L3,17.25V21H6.75L17.81,9.94L14.06,6.19Z" />
        </svg>
</a>    