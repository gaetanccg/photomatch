<x-photographer-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Demandes de réservation</h1>
            <p class="mt-1 text-sm text-gray-600">Gérez les demandes des clients</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('photographer.requests.index') }}"
                   class="px-3 py-1.5 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    Toutes
                </a>
                <a href="{{ route('photographer.requests.index', ['status' => 'pending']) }}"
                   class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request('status') === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    En attente
                </a>
                <a href="{{ route('photographer.requests.index', ['status' => 'accepted']) }}"
                   class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request('status') === 'accepted' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    Acceptées
                </a>
                <a href="{{ route('photographer.requests.index', ['status' => 'declined']) }}"
                   class="px-3 py-1.5 rounded-lg text-sm font-medium {{ request('status') === 'declined' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-100' }}">
                    Refusées
                </a>
            </div>
        </div>

        <!-- Requests List -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="divide-y divide-gray-200">
                @forelse($requests as $request)
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $request->project->title }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $request->status === 'declined' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $request->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ $request->status === 'pending' ? 'En attente' : '' }}
                                        {{ $request->status === 'accepted' ? 'Acceptée' : '' }}
                                        {{ $request->status === 'declined' ? 'Refusée' : '' }}
                                        {{ $request->status === 'cancelled' ? 'Annulée' : '' }}
                                    </span>
                                </div>

                                <div class="mt-2 flex items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $request->project->client->name }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $request->project->location }}
                                    </span>
                                    @if($request->project->event_date)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $request->project->event_date->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>

                                @if($request->client_message)
                                    <p class="mt-3 text-sm text-gray-600 line-clamp-2">{{ $request->client_message }}</p>
                                @endif

                                <p class="mt-2 text-xs text-gray-400">Reçue {{ $request->sent_at->diffForHumans() }}</p>
                            </div>

                            <div class="ml-4 flex items-center gap-2">
                                @if($request->project->budget_min && $request->project->budget_max)
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ number_format($request->project->budget_min, 0, ',', ' ') }}€ - {{ number_format($request->project->budget_max, 0, ',', ' ') }}€
                                    </span>
                                @endif
                                <a href="{{ route('photographer.requests.show', $request) }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune demande</h3>
                        <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore reçu de demandes de réservation.</p>
                    </div>
                @endforelse
            </div>

            @if($requests->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-photographer-layout>
