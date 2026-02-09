<x-photographer-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Bonjour, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Bienvenue dans votre espace photographe</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Pending Requests -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Demandes en attente</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingRequestsCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Missions This Month -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Missions ce mois</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $acceptedThisMonth }}</p>
                    </div>
                </div>
            </div>

            <!-- Rating -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Note moyenne</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $photographer->rating ?? '-' }}
                            @if($photographer->rating)
                                <span class="text-sm text-gray-500">/ 5</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mini Calendrier des disponibilités -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Mes disponibilités</h2>
                <a href="{{ route('photographer.availabilities.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">Gérer</a>
            </div>

            <!-- Légende -->
            <div class="flex items-center gap-4 mb-4 text-xs">
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded bg-green-500"></div>
                    <span class="text-gray-600">Disponible</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded bg-red-500"></div>
                    <span class="text-gray-600">Indisponible</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded bg-gray-200"></div>
                    <span class="text-gray-600">Non défini</span>
                </div>
            </div>

            <!-- Jours de la semaine -->
            <div class="grid grid-cols-7 gap-1 mb-1">
                @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $day)
                    <div class="text-center text-xs font-medium text-gray-500 py-1">{{ $day }}</div>
                @endforeach
            </div>

            <!-- Calendrier -->
            @php
                $firstDate = $calendarDays->first()['date'];
                $startDayOfWeek = $firstDate->dayOfWeekIso; // 1 = Lundi, 7 = Dimanche
            @endphp
            <div class="grid grid-cols-7 gap-1">
                @for($i = 1; $i < $startDayOfWeek; $i++)
                    <div></div>
                @endfor
                @foreach($calendarDays as $day)
                    @php
                        $isToday = $day['date']->isToday();
                        $availability = $day['availability'];
                        $bgClass = 'bg-gray-100';
                        if ($availability) {
                            $bgClass = $availability->is_available ? 'bg-green-500 text-white' : 'bg-red-500 text-white';
                        }
                    @endphp
                    <div class="relative aspect-square flex items-center justify-center rounded text-xs font-medium {{ $bgClass }} {{ $isToday ? 'ring-2 ring-indigo-500' : '' }}"
                         title="{{ $day['date']->translatedFormat('l d F') }}{{ $availability ? ($availability->is_available ? ' - Disponible' : ' - Indisponible') : '' }}">
                        {{ $day['date']->format('d') }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Latest Requests -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Dernières demandes</h2>
                    <a href="{{ route('photographer.requests.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">Voir tout</a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($latestRequests as $request)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <p class="font-medium text-gray-900">{{ $request->project->title }}</p>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status->badgeClasses() }}">
                                    {{ $request->status->label() }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $request->project->client->name }} &bull; {{ $request->sent_at->diffForHumans() }}
                            </p>
                        </div>
                        <a href="{{ route('photographer.requests.show', $request) }}" class="ml-4 text-indigo-600 hover:text-indigo-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <p>Aucune demande pour le moment</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-photographer-layout>
