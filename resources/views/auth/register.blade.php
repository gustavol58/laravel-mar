<x-guest-layout>
    @livewire('menu-own')
    <div class="text-gray-600 text-4xl text-center">
        CREACIÓN DE UN NUEVO USUARIO
    </div>
    

    <x-jet-authentication-card>

        <x-slot name="logo" >
            {{-- <x-jet-authentication-card-logo /> --}}
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                {{-- <x-jet-label for="user_name" value="{{ __('UserName') }}" /> --}}
                {{-- <x-jet-label for="user_name" value="{{ __('username') }}" /> --}}
                <x-jet-label for="user_name" value="Nombre de acceso" />
                {{-- <x-jet-label for="user_name" value="Nombre de acceso" /> --}}
                <x-jet-input id="user_name" class="block mt-1 w-full" type="text" name="user_name" :value="old('user_name')" required autocomplete="user_name" />
            </div>

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            {{-- ROL  --}}
            <div class="mt-4">
                <x-jet-label for="idrol_id" value="Rol" />

                <div class="relative flex w-full flex-wrap items-stretch mb-3">
                    <select wire:change="llenar_dir_entrega($event.target.value)" name="php_rol" id="idrol_id" required class="w-11/12 shadow-lg px-3 py-2 mb-1 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors">
                        <option value="">Seleccione rol...&nbsp;&nbsp;&nbsp;&nbsp;</option>
                        @foreach ($arr_para_roles as $un_rol)
                            {{-- <option value="{{$un_cliente->id}}">{{$un_cliente->nom_cliente}}&nbsp;&nbsp;&nbsp;&nbsp;</option> --}}
                        <option value="{{$un_rol['id']}}">{{$un_rol['rol']}}&nbsp;&nbsp;&nbsp;&nbsp;</option>
                        @endforeach
                    </select>
                </div>
            </div>              

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{-- {{ __('Already registered?') }} --}}
                    Cancelar
                </a>

                <x-jet-button class="ml-4">
                    {{-- {{ __('Register') }} --}}
                    Crear usuario
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>

</x-guest-layout>
