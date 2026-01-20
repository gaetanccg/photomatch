<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('seo.pages.home.title') }}</title>

    {{-- Primary Meta Tags --}}
    <meta name="title" content="{{ config('seo.pages.home.title') }}">
    <meta name="description" content="{{ config('seo.pages.home.description') }}">
    <meta name="keywords" content="{{ config('seo.default.keywords') }}">
    <meta name="author" content="{{ config('seo.default.author') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ config('seo.site_url') }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('seo.site_url') }}">
    <meta property="og:title" content="{{ config('seo.pages.home.title') }}">
    <meta property="og:description" content="{{ config('seo.pages.home.description') }}">
    <meta property="og:image" content="{{ config('seo.site_url') }}{{ config('seo.og.image') }}">
    <meta property="og:site_name" content="{{ config('seo.site_name') }}">
    <meta property="og:locale" content="fr_FR">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ config('seo.site_url') }}">
    <meta name="twitter:title" content="{{ config('seo.pages.home.title') }}">
    <meta name="twitter:description" content="{{ config('seo.pages.home.description') }}">
    <meta name="twitter:image" content="{{ config('seo.site_url') }}{{ config('seo.og.image') }}">
    @if(config('seo.twitter.site'))
    <meta name="twitter:site" content="{{ config('seo.twitter.site') }}">
    @endif

    {{-- Theme Color --}}
    <meta name="theme-color" content="#10b981">
    <meta name="msapplication-TileColor" content="#10b981">

    {{-- Favicon & Icons --}}
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="TrouveTonPhotographe" />
    <link rel="manifest" href="/site.webmanifest" />

    {{-- Schema.org JSON-LD --}}
    @if(isset($schema))
    <script type="application/ld+json">
    {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <x-application-logo class="h-8 w-8 text-emerald-600" />
                        <span class="text-xl font-bold text-gray-900">Trouve Ton Photographe</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isClient())
                            <a href="{{ route('client.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                                Mon espace
                            </a>
                        @elseif(auth()->user()->isPhotographer())
                            <a href="{{ route('photographer.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                                Mon espace
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
                                Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">
                                Deconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition">
                            Inscription
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-50"></div>
        <div class="max-w-7xl mx-auto relative">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                            <span class="block">Trouvez le photographe</span>
                            <span class="block text-emerald-200">parfait pour votre projet</span>
                        </h1>
                        <p class="mt-3 text-base text-emerald-100 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Trouve Ton Photographe vous connecte avec des photographes professionnels specialises dans votre type de projet. Mariage, evenementiel, produit, immobilier... trouvez l'expert qu'il vous faut.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('search.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-emerald-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10 transition">
                                    Rechercher un photographe
                                </a>
                            </div>
                            @guest
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-white/10 md:py-4 md:text-lg md:px-10 transition">
                                    Creer mon compte
                                </a>
                            </div>
                            @endguest
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <div class="h-56 w-full sm:h-72 md:h-96 lg:w-full lg:h-full flex items-center justify-center opacity-20">
                <x-application-logo class="h-64 w-64 text-white" />
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Comment ca marche ?
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    En quelques etapes simples, trouvez le photographe ideal
                </p>
            </div>

            <div class="mt-12">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Step 1 -->
                    <div class="pt-6">
                        <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div>
                                    <span class="inline-flex items-center justify-center p-3 bg-emerald-600 rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">1. Decrivez votre projet</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Creez votre projet photo en precisant le type, la date, le lieu et votre budget.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="pt-6">
                        <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div>
                                    <span class="inline-flex items-center justify-center p-3 bg-emerald-600 rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">2. Trouvez le bon photographe</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Notre algorithme vous propose les photographes les plus adaptes a votre projet.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="pt-6">
                        <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div>
                                    <span class="inline-flex items-center justify-center p-3 bg-emerald-600 rounded-md shadow-lg">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </span>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">3. Envoyez votre demande</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Contactez directement le photographe et organisez votre seance photo.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Specialties Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Nos specialites
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    Des photographes experts dans chaque domaine
                </p>
            </div>

            <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @php
                    $specialties = [
                        ['name' => 'Mariage', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                        ['name' => 'Evenementiel', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['name' => 'Produit', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        ['name' => 'Immobilier', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['name' => 'Corporate', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                        ['name' => 'Portrait', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['name' => 'Culinaire', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['name' => 'Autre', 'icon' => 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z'],
                    ];
                @endphp

                @foreach($specialties as $specialty)
                <a href="{{ route('search.index') }}" class="group relative rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md hover:border-emerald-200 transition flex flex-col items-center text-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 group-hover:bg-emerald-100 transition">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $specialty['icon'] }}"></path>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-sm font-medium text-gray-900">{{ $specialty['name'] }}</h3>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recommended Photographers Section -->
    @if(isset($topPhotographers) && $topPhotographers->count() > 0)
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Photographes recommandes
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    Decouvrez nos photographes les mieux notes
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($topPhotographers as $photographer)
                <a href="{{ route('photographers.show', $photographer) }}" class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-full bg-emerald-100 flex items-center justify-center">
                                <span class="text-emerald-700 font-semibold text-lg">
                                    {{ substr($photographer->user->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-emerald-600 transition">
                                    {{ $photographer->user->name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $photographer->experience_years ?? 0 }} ans d'experience
                                </p>
                            </div>
                        </div>

                        @if($photographer->rating)
                        <div class="mt-4 flex items-center">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $photographer->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-600">{{ number_format($photographer->rating, 1) }}</span>
                        </div>
                        @endif

                        @if($photographer->specialties->count() > 0)
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($photographer->specialties->take(3) as $specialty)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                {{ $specialty->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        @if($photographer->hourly_rate)
                        <div class="mt-4 text-sm text-gray-600">
                            A partir de <span class="font-semibold text-emerald-600">{{ number_format($photographer->hourly_rate, 0) }} EUR/h</span>
                        </div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('photographers.index') }}" class="inline-flex items-center px-6 py-3 border border-emerald-600 text-base font-medium rounded-md text-emerald-700 bg-white hover:bg-emerald-50 transition">
                    Voir tous les photographes
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- CTA Section -->
    <div class="bg-emerald-700">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Pret a trouver votre photographe ?</span>
                <span class="block text-emerald-200">Commencez des maintenant.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-emerald-700 bg-white hover:bg-emerald-50 transition">
                        Creer un compte gratuit
                    </a>
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="{{ route('photographers.index') }}" class="inline-flex items-center justify-center px-5 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-white/10 transition">
                        Voir les photographes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo & Description -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <x-application-logo class="h-8 w-8 text-emerald-500" />
                        <span class="text-xl font-bold text-white">Trouve Ton Photographe</span>
                    </div>
                    <p class="text-gray-400 text-sm max-w-md">
                        La plateforme de mise en relation entre clients et photographes professionnels. Trouvez le photographe ideal pour vos projets.
                    </p>
                </div>

                <!-- Navigation -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navigation</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('search.index') }}" class="text-gray-400 hover:text-emerald-400 text-sm transition">Rechercher</a></li>
                        <li><a href="{{ route('photographers.index') }}" class="text-gray-400 hover:text-emerald-400 text-sm transition">Photographes</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-emerald-400 text-sm transition">Connexion</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-emerald-400 text-sm transition">Inscription</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Informations</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('legal.mentions') }}" class="text-gray-400 hover:text-emerald-400 text-sm transition">Mentions legales</a></li>
                        <li><a href="{{ route('legal.cgu') }}" class="text-gray-400 hover:text-emerald-400 text-sm transition">CGU</a></li>
                        <li><a href="{{ route('legal.privacy') }}" class="text-gray-400 hover:text-emerald-400 text-sm transition">Confidentialite</a></li>
                        <li><a href="{{ route('legal.cookies') }}" class="text-gray-400 hover:text-emerald-400 text-sm transition">Cookies</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-800">
                <p class="text-center text-gray-400 text-sm">
                    &copy; {{ date('Y') }} {{ config('seo.site_name') }}. Tous droits reserves.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
