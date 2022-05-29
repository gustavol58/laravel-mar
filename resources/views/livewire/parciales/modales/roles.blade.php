<div>
    <div class="flex mb-4">
        {{-- <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600"> --}}
        <label class="w-4/12 font-bold text-base  mb-2 ml-1 text-gray-600">
            Rol:
            {{-- <span class="text-red-500">*</span> --}}
        </label>
        <div class="w-8/12">
            @foreach($arr_roles_disponibles as $index => $rol)
                {{-- <input  type="checkbox" wire:model="{{$campo_encadena}}.{{ $rol['id'] }}" {{ $rol['inhabilitado'] }}  value="{{ $rol['id'] }}"   class="mr-4"><label><span class="text-gray-700">{{ $rol['titulo'] }}</span></label><br>                                    --}}
                <input  type="radio" wire:model="{{$campo_encadena}}"  value="{{ $rol['id'] }}"   class="mr-4"><label><span class="text-gray-700">{{ $rol['titulo'] }}</span></label><br>                                   
            @endforeach

        </div>
    </div>  
    @error($campo_encadena)
        <div  class="text-red-500 mb-4 -mt-6">{{ $message }}</div> 
    @enderror   


</div>