<div>
    {{-- MODAL para eliminar elementos --}}
    <x-jet-dialog-modal wire:model="modal_visible_eliminar" >
        <x-slot name="title">
            <div class="flex ">
                {{-- Título del modal  --}}
                <div class="w-10/12 mt-4">
                    <span class="text-gray-500 text-4xl">Eliminar elementos que aún no tengan valores asignados.</span>       
                </div>
            </div>
        </x-slot>
        <x-slot name="content"> 
            <div x-data>
                <form  x-on:click.away="$wire.cerrar_modal_eliminar()" wire:submit.prevent="submit_eliminar()">
    
                   <div class="mt-3">
                      <table class="mx-auto">
                         @foreach ($obj_eliminar as $key => $item1)
                            {{-- Debido a que la primera vez llega una colección y las siguientes 
                            un array, se hizo necesaria esta conversión explícita a array:  --}}                         
                            @php
                                $item = (array)$item1;
                            @endphp
                            <tr>
                                <td>
                                    @if($item['eliminar'])
                                        <button type="button"  wire:click="marcar_para_eliminar({{$key}})" 
                                            class="bg-red-600 hover:bg-red-700 focus:bg-red-700 text-white rounded-lg font-semibold">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>                                        
                                        </button>  
                                    @endif
                               </td>        
                               <td class="py-1 px-4">
                                   @if ($item['marca_eliminar'])
                                        <span class="line-through">
                                            {{$item['cabecera']}} ({{$item['tipo']}})
                                        </span>
                                   @else 
                                        {{$item['cabecera']}} ({{$item['tipo']}})
                                   @endif
                               </td>
                            </tr>
                         @endforeach
                      </table>
                      @include('livewire.parciales.modales.botones' , [
                         'metodo_cerrar_modal' => 'cerrar_modal_eliminar()',
                         'primer_boton' => 'Eliminar los elementos tachados',
                      ])                  
                   </div>
                </form>
            </div>            
        </x-slot>
    
        <x-slot name="footer">
  
        </x-slot>
    </x-jet-confirmation-modal> 
</div>
