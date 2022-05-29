<div>
    {{-- {{dd(Auth::user()->id)}} --}}
    {{-- {{dd(Auth::user()->roles[0]->name)}} --}}
{{-- {{dd(Auth::user())}};  --}}
    <nav class="bg-gray-300 " x-data="{open_mobil:false}" x-cloak>
        <div class="flex">
            <!-- LOGO --> 
            {{-- <div class="border border-blue-500 w-1/6"> --}}
            <div class="w-1/6">
                <a href="{{route('login')}}" >
                    <img src={{url(asset('img/logo.png'))}}  >
                </a>
            </div>
            <!-- MENÚ PRINCIPPAL --> 
            {{-- <div class="border border-red-500 w-2/4"> --}}
            <div class="w-2/4">
                <div class="flex  mt-4">
                    <!-- Opción clientes --> 
                    @if (Auth::user()->roles[0]->name == 'admin'
                            || Auth::user()->roles[0]->name == 'comer')
                        <div class="ml-3 relative" x-data="{open_clientes: false}">
                            {{-- Opción principal:  --}}
                            <div>
                                <button x-on:click="open_clientes = true" class="text-gray-700 hover:bg-gray-100 px-3 py-2  rounded-md text-sm font-bold" id="clientes-menu" aria-haspopup="true">
                                    <svg class="inline-block align-top  w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M16 11.5C16 10.12 17.12 9 18.5 9S21 10.12 21 11.5 19.88 14 18.5 14 16 12.88 16 11.5M13 3V20H24V3H13M22 16C20.9 16 20 16.9 20 18H17C17 16.9 16.11 16 15 16V7C16.11 7 17 6.11 17 5H20C20 6.11 20.9 7 22 7V16M7 6C8.1 6 9 6.9 9 8S8.1 10 7 10 5 9.1 5 8 5.9 6 7 6M7 4C4.79 4 3 5.79 3 8S4.79 12 7 12 11 10.21 11 8 9.21 4 7 4M7 14C3.13 14 0 15.79 0 18V20H11V18H2C2 17.42 3.75 16 7 16C8.83 16 10.17 16.45 11 16.95V14.72C9.87 14.27 8.5 14 7 14Z"  clip-rule="evenodd"></path></svg>
                                    <span class="align-top">Clientes</span>
                                </button>
                            </div>
                            {{-- Submenú clientes  --}}
                            <div x-show="open_clientes" x-on:click.away="open_clientes=false"  class="z-50 origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" role="clientes_menu" aria-orientation="vertical" aria-labelledby="clientes-menu">
                                <a href="{{route('ver-clientes')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ingresar - Modificar</a>
                            </div>  
                        </div>  
                    @endif

                    <!-- Opción recaudos -->
                    @if (Auth::user()->roles[0]->name == 'admin'
                            || Auth::user()->roles[0]->name == 'contab'
                            || Auth::user()->roles[0]->name == 'comer')
                        <div class="ml-3 relative" x-data="{open_recaudos: false}">
                            {{-- Opción principal:  --}}
                            <div>
                                <button x-on:click="open_recaudos = true" class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-bold" id="recaudos-menu" aria-haspopup="true">
                                    <svg class="inline-block align-top  w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span class="align-top">Recaudos</span>
                                </button>
                            </div>
                            {{-- Submenú recaudos  --}}
                            <div x-show="open_recaudos" x-on:click.away="open_recaudos=false"  class="z-50 origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" role="recaudos_menu" aria-orientation="vertical" aria-labelledby="recaudos-menu">
                                @if(Auth::user()->hasRole('admin'))
                                    <a href="{{route('ver-recaudos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ingresar - Modificar - Anular</a>
                                    <a href="{{route('aprobar-recaudos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Aprobar para asentar</a>
                                    <a href="{{route('asentar-recaudos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Asentar&nbsp;recaudos</a>
                                    <a href="{{route('extractos-bancarios')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t-2 border-gray-300" role="menuitem">Extractos bancarios</a>
                                    {{-- <a href="{{route('info-recaudos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Reporte</a> --}}
                                    <a href="{{route('info-recaudos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t-2 border-gray-300" role="menuitem">Informe</a>
                                @elseif(Auth::user()->hasRole('contab'))
                                    <a href="{{route('ver-recaudos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ingresar - Modificar</a>
                                    <a href="{{route('asentar-recaudos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Asentar&nbsp;recaudos</a>
                                    <a href="{{route('info-recaudos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t-2 border-gray-300" role="menuitem">Informe</a>
                                @elseif(Auth::user()->hasRole('comer'))
                                    <a href="{{route('ver-recaudos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ingresar - Modificar</a>
                                    <a href="{{route('info-recaudos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t-2 border-gray-300" role="menuitem">Informe</a>                                        
                                @endif
                            </div>
                        </div>  
                    @endif  
                    
                    <!-- Opción productos -->
                    @if (Auth::user()->roles[0]->name == 'admin'
                            || Auth::user()->roles[0]->name == 'comer'
                            || Auth::user()->roles[0]->name == 'produ'
                            || Auth::user()->roles[0]->name == 'disen')                            
                        <div class="ml-3 relative" x-data="{open_productos: false}">
                            {{-- Opción principal:  --}}
                            <div>
                                <button x-on:click="open_productos = true" class="text-gray-700 hover:bg-gray-100 px-3 py-2  rounded-md text-sm font-bold" id="productos-menu" aria-haspopup="true">
                                    <svg class="inline-block align-top  w-4 h-4 " fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                                    <span class="align-top">Productos</span>
                                </button>
                            </div>
                            {{-- Submenú productos  --}} 
                            <div x-show="open_productos" x-on:click.away="open_productos=false"  class="z-50 origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" role="productos_menu" aria-orientation="vertical" aria-labelledby="productos-menu">
                                @if(Auth::user()->hasRole('admin'))
                                    <a href="{{route('generar-formu-admin')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t-2 border-gray-300" role="menuitem">Configurar Tipos de producto</a>
                                    <a href="{{route('escoger-tipo_producto')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Mantenimiento de productos</a>
                                @elseif(Auth::user()->hasRole('comer'))
                                    <a href="{{route('escoger-tipo_producto')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Mantenimiento de productos</a>
                                    {{-- <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ingresar - Modificar</a> --}}
                                @elseif(Auth::user()->hasRole('produ'))
                                    <a href="{{route('escoger-tipo_producto')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Mantenimiento de productos</a>
                                    {{-- <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ingresar - Modificar</a> --}}
                                @elseif(Auth::user()->hasRole('disen'))
                                    <a href="{{route('escoger-tipo_producto')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Mantenimiento de productos</a>
                                    {{-- <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ingresar - Modificar</a> --}}
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Opción pedidos --> 
                    @if (Auth::user()->roles[0]->name == 'admin'
                            || Auth::user()->roles[0]->name == 'comer'
                            || Auth::user()->roles[0]->name == 'produ'
                            || Auth::user()->roles[0]->name == 'disen'
                            || Auth::user()->roles[0]->name == 'contab')
                        <div class="ml-3 relative" x-data="{open_pedidos: false}">
                            {{-- Opción principal:  --}} 
                            <div>
                                <button x-on:click="open_pedidos = true" class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-bold" id="pedidos-menu" aria-haspopup="true">
                                    <svg class="inline-block align-top  w-4 h-6" fill="currentColor" viewBox="0 0 20 30" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M4.41 3L3 4.41L5.69 7.1C4.63 8.46 4 10.15 4 12C4 13.85 4.63 15.55 5.69 16.9L3 19.59L4.41 21L7.1 18.31C8.46 19.37 10.15 20 12 20C13.85 20 15.55 19.37 16.9 18.31L19.59 21L21 19.59L18.31 16.9C19.37 15.54 20 13.85 20 12C20 10.15 19.37 8.45 18.31 7.1L21 4.41L19.59 3L16.9 5.69C15.54 4.63 13.85 4 12 4C10.15 4 8.45 4.63 7.1 5.69L4.41 3M12 6C15.31 6 18 8.69 18 12C18 15.31 15.31 18 12 18C8.69 18 6 15.31 6 12C6 8.69 8.69 6 12 6Z"  clip-rule="evenodd"></path></svg>
                                    <span class="align-top">Pedidos</span>
                                </button>
                            </div>
                            {{-- Submenú pedidos  --}}
                            <div x-show="open_pedidos" x-on:click.away="open_pedidos=false"  class="z-50 origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" role="pedidos_menu" aria-orientation="vertical" aria-labelledby="pedidos-menu">
                                @if(Auth::user()->hasRole('admin')
                                        || Auth::user()->hasRole('comer'))
                                    <a href="{{route('ver-pedidos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ingresar - Modificar - Anular</a>
                                @elseif(Auth::user()->hasRole('produ'))
                                    <a href="{{route('ver-pedidos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Registrar producción</a>
                                @elseif(Auth::user()->hasRole('disen'))
                                    <a href="{{route('ver-pedidos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ver pedidos aprobados</a>
                                @elseif(Auth::user()->hasRole('contab'))
                                    <a href="{{route('ver-pedidos')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Registrar facturación</a>
                                @endif
                            </div>  
                        </div> 
                    @endif

                    <!-- Opción usuarios --> 
                    @if (Auth::user()->roles[0]->name == 'admin')
                        <div class="ml-3 relative" x-data="{open_usuarios: false}">
                            {{-- Opción principal:  --}}
                            <div>
                                <button x-on:click="open_usuarios = true" class="text-gray-700 hover:bg-gray-100 px-3 py-2  rounded-md text-sm font-bold" id="usuarios-menu" aria-haspopup="true">
                                    <svg class="inline-block align-top  w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"  clip-rule="evenodd"></path></svg>
                                    <span class="align-top">Usuarios</span>
                                </button>
                            </div>
                            {{-- Submenú usuarios  --}}
                            <div x-show="open_usuarios" x-on:click.away="open_usuarios=false"  class="z-50 origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" role="usuarios_menu" aria-orientation="vertical" aria-labelledby="usuarios-menu">
                                <a href="{{route('ver-usuarios')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Ingresar - Modificar - Inactivar</a>
                            </div>  
                        </div>  
                    @endif
                    

                </div>
            </div>
            <!-- USUARIO Y AYUDA --> 
            {{-- <div class="border border-green-500 w-1/3"> --}}
            <div class="w-1/3">
                {{-- <div class="flex items-end justify-end"> --}}
                <div class="flex items-center justify-center mt-4">
                    <!-- Nombre de usuario --> 
                    <div class="text-gray-700  px-3 py-2 rounded-md text-sm font-bold">
                        {{ ucwords(Auth::user()->name) }}
                        {{ "(" . ucwords(Auth::user()->roles->pluck('name')->first()) . ")" }}
                    </div>
                    <!-- Ícono de usuario --> 
                    <div class="relative" x-data="{open_perfil: false}">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button x-on:click="open_perfil = true" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu" aria-haspopup="true">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </button>
                        @else
                            <span class="inline-flex rounded-md">
                                <button x-on:click="open_perfil = true" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu" aria-haspopup="true">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        @endif              

                        <!-- Profile dropdown -->
                        <div x-show="open_perfil" x-on:click.away="open_perfil=false"  class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" role="user_menu" aria-orientation="vertical" aria-labelledby="user-menu">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">{{ __('Profile') }}</a>
                            <form method="POST" action="{{ route('logout-own') }}">
                                @csrf
                                <x-jet-dropdown-link href="{{ route('logout-own') }}"
                                    onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                    {{ __('Logout') }}
                                </x-jet-dropdown-link>
                            </form>
                        </div>
                    </div>
                    {{-- Botón ayuda --}}
                    <div class="ml-6 relative" x-data="{open_ayuda: false}">
                        <button x-on:click="open_ayuda = true" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="ayuda-menu" aria-haspopup="true">
                            <svg class="inline-block align-top  text-white fill-current w-8 h-8 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                        </button>

                        <!-- ayuda dropdown -->
                        <div x-show="open_ayuda" x-on:click.away="open_ayuda=false" x-on:click="open_ayuda=false"  class="z-50 origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" role="ayuda_menu" aria-orientation="vertical" aria-labelledby="ayuda-menu">
                            @php
                                foreach($arr_ayudas_pdfs as $un_pdf){
                                    $arr_aux_roles = explode('_@@@_' , $un_pdf->permisos);
                                    $nombre_pdf = asset('storage/ayudas_pdfs/'.$un_pdf->nombre_interno);
                                    if(Auth::user()->hasRole($arr_aux_roles)){
                                        echo '<a href="'. $nombre_pdf .'" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100  border-t-2 border-gray-300" role="menuitem" >' . $un_pdf->titulo . '</a>';
                                    }
                                }    
                            @endphp
                        </div>
                    </div>        

                </div>

            </div>
        </div>   
      
        {{-- Menú mobile (opciones sm) --}}
        <div x-show="open_mobil" x-on:click.away="open_mobil = false" class="sm:hidden" >
            <div class="px-2 pt-2 pb-3 space-y-1">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <a href="#" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Team111</a>
                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Projects</a>
                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Calendar</a>
            </div>
        </div>
    </nav>
</div>

