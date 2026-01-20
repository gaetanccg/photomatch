<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Historique des missions</h1>
                <p class="mt-1 text-sm text-gray-600">Retrouvez toutes vos missions realisees avec des photographes</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-emerald-100 rounded-lg">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Missions realisees</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalMissions }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Budget total</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalSpent, 0, ',', ' ') }} €</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Avis donnes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $reviewsGiven }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            @if($years->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <form action="{{ route('client.history.index') }}" method="GET" class="flex items-center gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Annee</label>
                            <select name="year" class="rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Toutes</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                                Filtrer
                            </button>
                            @if(request('year'))
                                <a href="{{ route('client.history.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                                    Effacer
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            @endif

            <!-- Missions List -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Missions ({{ $missions->total() }})</h2>
                </div>

                @if($missions->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($missions as $mission)
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-4">
                                    <!-- Photographer Avatar -->
                                    <div class="flex-shrink-0">
                                        @if($mission->photographer->user->profile_photo_path)
                                            <img src="{{ Storage::url($mission->photographer->user->profile_photo_path) }}"
                                                 alt="{{ $mission->photographer->user->name }}"
                                                 class="w-14 h-14 rounded-full object-cover">
                                        @else
                                            <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center">
                                                <span class="text-emerald-700 font-bold text-lg">
                                                    {{ strtoupper(substr($mission->photographer->user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-1">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $mission->project->title }}</h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Terminee
                                            </span>
                                        </div>

                                        <p class="text-sm text-gray-600 mb-2">
                                            Avec <a href="{{ route('photographers.show', $mission->photographer) }}" class="text-emerald-600 hover:underline font-medium">{{ $mission->photographer->user->name }}</a>
                                        </p>

                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-3">
                                            @if($mission->project->event_date)
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $mission->project->event_date->format('d/m/Y') }}
                                                </span>
                                            @endif
                                            @if($mission->project->location)
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    </svg>
                                                    {{ $mission->project->location }}
                                                </span>
                                            @endif
                                            @if($mission->proposed_price)
                                                <span class="flex items-center gap-1 font-medium text-emerald-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ number_format($mission->proposed_price, 0, ',', ' ') }} €
                                                </span>
                                            @endif
                                        </div>

                                        @if($mission->review)
                                            <div class="flex items-center gap-2 text-sm">
                                                <span class="text-gray-500">Votre avis :</span>
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $mission->review->rating)
                                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                                        @else
                                                            <svg class="w-4 h-4 fill-current text-gray-300" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-gray-600">{{ $mission->review->rating }}/5</span>
                                            </div>
                                        @else
                                            <a href="{{ route('client.reviews.create', $mission) }}"
                                               class="inline-flex items-center text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                                Donner un avis
                                            </a>
                                        @endif
                                    </div>

                                    <a href="{{ route('client.requests.show', $mission) }}"
                                       class="flex-shrink-0 px-4 py-2 text-sm font-medium text-emerald-600 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition">
                                        Voir details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($missions->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $missions->links() }}
                        </div>
                    @endif
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune mission</h3>
                        <p class="mt-1 text-sm text-gray-500">Vos missions completees apparaitront ici.</p>
                        <a href="{{ route('search.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                            Trouver un photographe
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
