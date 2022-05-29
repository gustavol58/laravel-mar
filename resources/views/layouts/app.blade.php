<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="https://markka-pruebas22.tavohen.com/css/app.css">
        {{-- libreria pikaday: Para pedir fechas con datetimepicker: --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">

        <style>
            .imagen_ini{
                background-image: url("{{asset('img/first_option.png')}}");
                width: 100%;
                height: 100%;
            }
            .imagen_login_own{
                background-image: url("{{asset('img/fondo_login.jpg')}}");
                width: 100%;
                height: 100%;
            }
        </style> 

        @livewireStyles

        <!-- Scripts -->
        <script src="https://markka-pruebas22.tavohen.com/js/app.js" defer></script>
        {{-- <script src="https://markka-pruebas22.tavohen.com/js/app.js" ></script> --}}
    </head>
    <body class="font-sans antialiased">
        {{-- <x-jet-banner /> --}}

        <div class="min-h-screen bg-gray-100">
            {{-- @livewire('navigation-menu') --}}
            <p class="text-font-bold text-3xl text-center text-red-500">AMBIENTE DE PRUEBAS 22</p>
            <!-- Page Heading -->
            {{-- <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header> --}}

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        {{-- librerias moment.js y pikaday: Para pedir fechas con datetimepicker: --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>  
     
    </body>
</html>
