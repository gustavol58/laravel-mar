<div>
    <div class="flex mb-4">
        <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">
            ¿Debe ser obligatorio?:
            <span class="text-red-500">*</span>
        </label>
        <div class="w-8/12">
            <input type="radio" wire:model="{{$campo_encadena}}" value="1"   ><span class="ml-2 text-gray-700">Sí</span>
            <input type="radio" wire:model="{{$campo_encadena}}" value="0"   class="ml-6"   ><span class="ml-2 text-gray-700">No</span>
        </div>
    </div>  
    @error($campo_encadena)
        <div  class="text-red-500 mb-4 -mt-6">{{ $message }}</div> 
    @enderror   
</div>
