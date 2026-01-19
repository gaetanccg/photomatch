<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @auth
                        @if(auth()->user()->isClient())
                            <a href="{{ route('client.dashboard') }}" class="text-xl font-bold text-indigo-600">
                                PhotoMatch
                            </a>
                        @elseif(auth()->user()->isPhotographer())
                            <a href="{{ route('photographer.dashboard') }}" class="text-xl font-bold text-indigo-600">
                                PhotoMatch
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                                PhotoMatch
                            </a>
                        @endif
                    @else
                        <a href="{{ url('/') }}" class="text-xl font-bold text-indigo-600">
                            PhotoMatch
                        </a>
                    @endauth
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        {{-- Client Navigation --}}
                        @if(auth()->user()->isClient())
                            <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                                Tableau de bord
                            </x-nav-link>
                            <x-nav-link :href="route('client.projects.index')" :active="request()->routeIs('client.projects.*')">
                                Mes projets
                            </x-nav-link>
                            <x-nav-link :href="route('client.requests.index')" :active="request()->routeIs('client.requests.*')">
                                Mes demandes
                            </x-nav-link>
                            <x-nav-link :href="route('search.index')" :active="request()->routeIs('search.*')">
                                Rechercher
                            </x-nav-link>

                        {{-- Photographer Navigation --}}
                        @elseif(auth()->user()->isPhotographer())
                            <x-nav-link :href="route('photographer.dashboard')" :active="request()->routeIs('photographer.dashboard')">
                                Tableau de bord
                            </x-nav-link>
                            <x-nav-link :href="route('photographer.requests.index')" :active="request()->routeIs('photographer.requests.*')">
                                Demandes
                                @php
                                    $pendingCount = auth()->user()->photographer?->bookingRequests()->where('status', 'pending')->count() ?? 0;
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="ml-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </x-nav-link>
                            <x-nav-link :href="route('photographer.availabilities.index')" :active="request()->routeIs('photographer.availabilities.*')">
                                Disponibilités
                            </x-nav-link>
                            <x-nav-link :href="route('photographer.profile.edit')" :active="request()->routeIs('photographer.profile.*')">
                                Mon profil
                            </x-nav-link>

                        {{-- Admin Navigation --}}
                        @elseif(auth()->user()->isAdmin())
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                Administration
                            </x-nav-link>
                        @endif
                    @else
                        {{-- Guest Navigation --}}
                        <x-nav-link :href="route('photographers.index')" :active="request()->routeIs('photographers.*')">
                            Photographes
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Notifications Dropdown -->
                @php
                    $unreadNotifications = auth()->user()->unreadNotifications()->take(5)->get();
                    $unreadCount = auth()->user()->unreadNotifications()->count();
                @endphp
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <span class="text-sm font-semibold text-gray-900">Notifications</span>
                            @if($unreadCount > 0)
                                <span class="ml-2 text-xs text-gray-500">({{ $unreadCount }} non lues)</span>
                            @endif
                        </div>

                        @if($unreadNotifications->count() > 0)
                            <div class="max-h-64 overflow-y-auto">
                                @foreach($unreadNotifications as $notification)
                                    <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-50 border-b border-gray-50">
                                            <p class="text-sm text-gray-900 truncate">{{ $notification->data['message'] ?? 'Nouvelle notification' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        @else
                            <div class="px-4 py-6 text-center text-sm text-gray-500">
                                Aucune notification non lue
                            </div>
                        @endif

                        <div class="border-t border-gray-100">
                            <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm text-center text-indigo-600 hover:bg-gray-50">
                                Voir toutes les notifications
                            </a>
                        </div>
                    </x-slot>
                </x-dropdown>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2">
                                    <span class="text-indigo-600 font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs text-gray-500">Connecté en tant que</p>
                            <p class="text-sm font-medium text-gray-900 capitalize">{{ auth()->user()->role }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            Mon compte
                        </x-dropdown-link>

                        @if(auth()->user()->isPhotographer())
                            <x-dropdown-link :href="route('photographers.show', auth()->user()->photographer)">
                                Voir mon profil public
                            </x-dropdown-link>
                        @endif

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                Déconnexion
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @else
            <!-- Guest Links -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Connexion</a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700">Inscription</a>
            </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                {{-- Client Mobile Navigation --}}
                @if(auth()->user()->isClient())
                    <x-responsive-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                        Tableau de bord
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('client.projects.index')" :active="request()->routeIs('client.projects.*')">
                        Mes projets
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('client.requests.index')" :active="request()->routeIs('client.requests.*')">
                        Mes demandes
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('search.index')" :active="request()->routeIs('search.*')">
                        Rechercher des photographes
                    </x-responsive-nav-link>

                {{-- Photographer Mobile Navigation --}}
                @elseif(auth()->user()->isPhotographer())
                    <x-responsive-nav-link :href="route('photographer.dashboard')" :active="request()->routeIs('photographer.dashboard')">
                        Tableau de bord
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('photographer.requests.index')" :active="request()->routeIs('photographer.requests.*')">
                        Demandes reçues
                        @php
                            $pendingCount = auth()->user()->photographer?->bookingRequests()->where('status', 'pending')->count() ?? 0;
                        @endphp
                        @if($pendingCount > 0)
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('photographer.availabilities.index')" :active="request()->routeIs('photographer.availabilities.*')">
                        Mes disponibilités
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('photographer.profile.edit')" :active="request()->routeIs('photographer.profile.*')">
                        Mon profil photographe
                    </x-responsive-nav-link>

                {{-- Admin Mobile Navigation --}}
                @elseif(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Administration
                    </x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('photographers.index')" :active="request()->routeIs('photographers.*')">
                    Photographes
                </x-responsive-nav-link>
            @endauth
        </div>

        @auth
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                <div class="text-xs text-gray-400 capitalize mt-1">{{ auth()->user()->role }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Mon compte
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('notifications.index')">
                    Notifications
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                            {{ auth()->user()->unreadNotifications()->count() }}
                        </span>
                    @endif
                </x-responsive-nav-link>

                @if(auth()->user()->isPhotographer())
                    <x-responsive-nav-link :href="route('photographers.show', auth()->user()->photographer)">
                        Voir mon profil public
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        Déconnexion
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <!-- Guest Links -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('login')">
                    Connexion
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    Inscription
                </x-responsive-nav-link>
            </div>
        </div>
        @endauth
    </div>
</nav>
