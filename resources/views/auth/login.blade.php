<x-guest-layout>
    {{-- <div class="imagen_login_own bg-cover bg-no-repeat bg-center fixed px-8 py-2"> --}}
    <div class="imagen_ini bg-cover bg-no-repeat bg-center fixed px-8 py-2">
       
        {{-- 
            19sep2021: 
            Cambios al diseño de la pantalla login: 
                Unificación de Pedidos y Recaudos
                El título multicolor va dentro del formulario login
        --}}
        <div class="h-full flex justify-center items-center">
            <div class="card bg-white shadow-md rounded-lg px-4 py-4 mb-6 w-11/12 sm:w-4/5  md:w-3/5  lg:w-2/5  ">
                <x-jet-validation-errors class="mb-4" />
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <img src={{url(asset('img/logo.png'))}} style="margin-bottom: -10%;" >
                    <h2 class="mt-4 mb-4 text-xl text-center font-semibold text-gray-800 ">
                        Aplicación de Pedidos y Recaudos
                    </h2>
                    <div>
                        <x-jet-label for="email" value="{{ __('Email_usuario') }}" />
                        <x-jet-input 
                            id="email" 
                            class="block mt-1 w-full placeholder-gray-300" 
                            type="text" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            placeholder="Ej: juanz, anita.florez@gmail.com, etc..."
                            oninvalid="this.setCustomValidity('Debe digitar un nombre de usuario o email correcto');" 
                            oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Por favor digite un nombre de usuario o email correcto');" 
                            title=""  
                            autofocus 
                        />
                    </div>
    
                    <div class="mt-4">
                        <x-jet-label for="password" value="{{ __('Password') }}" />
                        <x-jet-input 
                            id="password" 
                            class="block mt-1 w-full" 
                            type="password" 
                            name="password" 
                            required 
                            oninvalid="this.setCustomValidity('Debe digitar una contraseña');" 
                            oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Por favor digite una contraseña');" 
                            title=""  
                            autocomplete="current-password" />
                    </div>
    
                    <div class="mt-4 flex items-center justify-between">
                        <label for="remember_me" class="flex items-start">
                            <input id="remember_me" type="checkbox" class="form-checkbox items-left" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
    
                        <a href="#" class="text-gray-600">¿Olvidó la contraseña?</a>
                        
                        <button type="submit" class="bg-blue-500 text-white hover:bg-blue-700  px-2 py-1 rounded">Iniciar sesión</button>
                    </div>                    
                    
                </form>
            </div>


        </div>
    </div>
</x-guest-layout>