<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PhotoMatch') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">
            <!-- Left side - Branding -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 relative">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-50"></div>
                <div class="relative z-10 flex flex-col justify-center items-center w-full p-12">
                    <div class="text-center">
                        <x-application-logo class="h-24 w-24 text-white mx-auto mb-6" />
                        <h1 class="text-4xl font-bold text-white mb-4">PhotoMatch</h1>
                        <p class="text-emerald-100 text-lg max-w-md">
                            La plateforme qui connecte clients et photographes professionnels pour des projets reussis.
                        </p>
                    </div>
                    <div class="mt-12 grid grid-cols-3 gap-8 text-center">
                        <div>
                            <div class="text-3xl font-bold text-white">500+</div>
                            <div class="text-emerald-200 text-sm">Photographes</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white">2000+</div>
                            <div class="text-emerald-200 text-sm">Projets</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white">98%</div>
                            <div class="text-emerald-200 text-sm">Satisfaction</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side - Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 bg-gray-50">
                <!-- Mobile logo -->
                <div class="lg:hidden mb-8">
                    <a href="/" class="flex items-center space-x-2">
                        <x-application-logo class="h-10 w-10 text-emerald-600" />
                        <span class="text-2xl font-bold text-gray-900">PhotoMatch</span>
                    </a>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center text-sm text-gray-500">
                    <a href="/" class="hover:text-emerald-600 transition">Retour a l'accueil</a>
                </div>
            </div>
        </div>
    </body>
</html>
