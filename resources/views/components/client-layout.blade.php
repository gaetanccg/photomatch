<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Espace Client</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <div class="flex">
                <!-- Sidebar -->
                <aside class="w-64 min-h-[calc(100vh-4rem)] bg-white shadow-sm">
                    <nav class="mt-6 px-4">
                        <div class="space-y-1">
                            <a href="{{ route('client.dashboard') }}"
                               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('client.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                Tableau de bord
                            </a>

                            <a href="{{ route('client.projects.index') }}"
                               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('client.projects.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Mes projets
                            </a>

                            <a href="{{ route('client.requests.index') }}"
                               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('client.requests.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path>
                                </svg>
                                Mes demandes
                            </a>

                            <a href="{{ route('search.index') }}"
                               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('search.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Rechercher
                            </a>
                        </div>
                    </nav>
                </aside>

                <!-- Main Content -->
                <main class="flex-1 p-8">
                    @if(session('success'))
                        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
