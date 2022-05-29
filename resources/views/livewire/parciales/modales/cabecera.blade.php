<div>
    <div class="flex mb-4">
        <label class="w-4/12 font-bold text-base my-auto mb-2 ml-1 text-gray-600">
            Cabecera:
            <span class="text-red-500">*</span>
        </label>
        <div class="w-8/12">
            <input type="text" x-ref="{{$campo_encadena}}" wire:model="{{$campo_encadena}}"  class="shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
        </div>
    </div>  
    @error($campo_encadena)
        <div class="text-red-500 mb-4 -mt-4">{{ $message }}</div> 
    @enderror  
</div>
