<x-client-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tableau de bord</h1>
                <p class="mt-1 text-sm text-gray-600">Bienvenue, {{ auth()->user()->name }}</p>
            </div>
            <a href="{{ route('client.projects.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouveau projet
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-indigo-100 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total projets</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_projects'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Projets publiés</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['published_projects'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Demandes en attente</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_requests'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Demandes acceptées</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['accepted_requests'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Projects -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Projets récents</h2>
                    <a href="{{ route('client.projects.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                        Voir tous
                    </a>
                </div>

                @if($recentProjects->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentProjects as $project)
                            <a href="{{ route('client.projects.show', $project) }}" class="block p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $project->title }}</h3>
                                        <p class="text-sm text-gray-500">{{ $project->location }}</p>
                                    </div>
                                    <div class="text-right">
                                        <x-status-badge :status="$project->status" />
                                        <p class="text-xs text-gray-500 mt-1">{{ $project->booking_requests_count }} demande(s)</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Aucun projet pour le moment</p>
                        <a href="{{ route('client.projects.create') }}" class="mt-3 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700">
                            Créer votre premier projet
                        </a>
                    </div>
                @endif
            </div>

            <!-- Recent Requests -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Demandes récentes</h2>
                    <a href="{{ route('client.requests.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                        Voir toutes
                    </a>
                </div>

                @if($recentRequests->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentRequests as $request)
                            <a href="{{ route('client.requests.show', $request) }}" class="block p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-medium">
                                                {{ substr($request->photographer->user->name ?? 'P', 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900">{{ $request->photographer->user->name ?? 'Photographe' }}</p>
                                            <p class="text-sm text-gray-500">{{ $request->project->title }}</p>
                                        </div>
                                    </div>
                                    <x-status-badge :status="$request->status" />
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Aucune demande envoyée</p>
                        <a href="{{ route('search.index') }}" class="mt-3 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700">
                            Rechercher des photographes
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-client-layout>
