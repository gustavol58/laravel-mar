<div>
    {{-- MODAL para re-ordenar elementos --}}
    <x-jet-dialog-modal wire:model="modal_visible_orden" >
      <x-slot name="title">
          <div class="flex ">
              {{-- Título del modal  --}}
              <div class="w-10/12 mt-4">
                  <span class="text-gray-500 text-4xl">Re-ordenar elementos.</span>       
              </div>
          </div>
      </x-slot>
  
      <x-slot name="content"> 
         <div x-data>
            <form  x-on:click.away="$wire.cerrar_modal_texto()" wire:submit.prevent="submit_orden()">

               <div class="mt-3">
                  <table class="mx-auto">
                     @foreach ($obj_orden as $key => $item1)
                        {{-- Debido a que la primera vez llega una colección y las siguientes 
                        un array, se hizo necesaria esta conversión explícita a array:  --}}
                        @php
                           $item = (array)$item1;
                        @endphp
                        <tr>
                           <td>
                              @if(! $loop->first)
                                 <button type="button"  wire:click="subir_array({{$key}})" 
                                       class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg font-semibold">
                                       <svg class="inline-block align-text-top w-6 h-6">
                                          <path fill="currentColor" d="M15,20H9V12H4.16L12,4.16L19.84,12H15V20Z" />
                                    </svg>                              
                                 </button>   
                              @endif
                           </td>

                           <td class="py-1 px-4">
                              {{$item['cabecera']}} ({{$item['tipo']}})
                           </td>

                           <td>
                              @if(! $loop->last)
                                 <button type="button"  wire:click="bajar_array({{$key}})" 
                                       class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg font-semibold">
                                       <svg class="inline-block align-text-top w-6 h-6">
                                          <path fill="currentColor" d="M10,4H14V13L17.5,9.5L19.92,11.92L12,19.84L4.08,11.92L6.5,9.5L10,13V4Z" />
                                       </svg>                             
                                 </button>   
                              @endif
                           </td>                     

                        </tr>
                     @endforeach
                  </table>
                  @include('livewire.parciales.modales.botones' , [
                     'metodo_cerrar_modal' => 'cerrar_modal_orden()',
                     'primer_boton' => 'Grabar cambios',
                  ])                  
               </div>
            </form>
         </div>

      </x-slot>
    
      <x-slot name="footer">

      </x-slot>
  </x-jet-confirmation-modal>           
</div>