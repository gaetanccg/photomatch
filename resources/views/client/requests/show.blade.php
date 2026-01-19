<x-client-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <a href="{{ route('client.requests.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                &larr; Retour aux demandes
            </a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">Détail de la demande</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Details -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Informations de la demande</h2>
                        <x-status-badge :status="$bookingRequest->status" />
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Projet concerné</dt>
                            <dd class="mt-1">
                                <a href="{{ route('client.projects.show', $bookingRequest->project) }}"
                                   class="text-indigo-600 hover:text-indigo-700">
                                    {{ $bookingRequest->project->title }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date d'envoi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $bookingRequest->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                        @if($bookingRequest->proposed_price)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tarif proposé</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($bookingRequest->proposed_price, 0, ',', ' ') }}€</dd>
                            </div>
                        @endif
                        @if($bookingRequest->responded_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de réponse</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $bookingRequest->responded_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                        @endif
                    </dl>

                    @if($bookingRequest->client_message)
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Votre message</h3>
                            <p class="text-sm text-gray-900 whitespace-pre-line bg-gray-50 p-4 rounded-lg">{{ $bookingRequest->client_message }}</p>
                        </div>
                    @endif

                    @if($bookingRequest->photographer_response)
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Réponse du photographe</h3>
                            <p class="text-sm text-gray-900 whitespace-pre-line bg-gray-50 p-4 rounded-lg">{{ $bookingRequest->photographer_response }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar - Photographer Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Photographe</h2>

                    <div class="flex items-center mb-4">
                        <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                            @if($bookingRequest->photographer->user->avatar ?? false)
                                <img src="{{ $bookingRequest->photographer->user->avatar }}" alt="" class="h-16 w-16 rounded-full object-cover">
                            @else
                                <span class="text-indigo-600 font-bold text-2xl">
                                    {{ substr($bookingRequest->photographer->user->name ?? 'P', 0, 1) }}
                                </span>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold text-gray-900">{{ $bookingRequest->photographer->user->name ?? 'Photographe' }}</p>
                            @if($bookingRequest->photographer->is_verified)
                                <span class="inline-flex items-center text-xs text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Profil vérifié
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($bookingRequest->photographer->rating)
                        <div class="mb-4">
                            <x-rating-stars :rating="$bookingRequest->photographer->getRawOriginal('rating')" />
                        </div>
                    @endif

                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Tarif horaire</dt>
                            <dd class="font-medium text-gray-900">{{ number_format($bookingRequest->photographer->getRawOriginal('hourly_rate'), 0, ',', ' ') }}€</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Expérience</dt>
                            <dd class="font-medium text-gray-900">{{ $bookingRequest->photographer->experience_years }} ans</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Missions réalisées</dt>
                            <dd class="font-medium text-gray-900">{{ $bookingRequest->photographer->total_missions }}</dd>
                        </div>
                    </dl>

                    @if($bookingRequest->photographer->specialties->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Spécialités</h3>
                            <div class="flex flex-wrap gap-1">
                                @foreach($bookingRequest->photographer->specialties as $specialty)
                                    <x-specialty-badge :specialty="$specialty" :level="$specialty->pivot->experience_level" />
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('photographers.show', $bookingRequest->photographer) }}"
                           class="block w-full text-center py-2 px-4 border border-indigo-600 rounded-lg text-sm font-medium text-indigo-600 hover:bg-indigo-50">
                            Voir le profil complet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-client-layout>
