<x-photographer-layout>
    <div class="space-y-6">
        <!-- Back Link -->
        <a href="{{ route('photographer.requests.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Retour aux demandes
        </a>

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $bookingRequest->project->title }}</h1>
                <p class="mt-1 text-sm text-gray-600">Demande de {{ $bookingRequest->project->client->name }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $bookingRequest->status->badgeClasses() }}">
                {{ $bookingRequest->status->label() }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Project Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Détails du projet</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Description</h3>
                            <p class="mt-1 text-gray-900">{{ $bookingRequest->project->description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Type de projet</h3>
                                <p class="mt-1 text-gray-900 capitalize">
                                    @switch($bookingRequest->project->project_type)
                                        @case('event') Événementiel @break
                                        @case('product') Produit @break
                                        @case('real_estate') Immobilier @break
                                        @case('corporate') Entreprise @break
                                        @case('portrait') Portrait @break
                                        @default Autre
                                    @endswitch
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Lieu</h3>
                                <p class="mt-1 text-gray-900">{{ $bookingRequest->project->location }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            @if($bookingRequest->project->event_date)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Date de l'événement</h3>
                                    <p class="mt-1 text-gray-900">{{ $bookingRequest->project->event_date->format('d/m/Y') }}</p>
                                </div>
                            @endif
                            @if($bookingRequest->project->estimated_duration)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Durée estimée</h3>
                                    <p class="mt-1 text-gray-900">{{ $bookingRequest->project->estimated_duration }} heures</p>
                                </div>
                            @endif
                        </div>

                        @if($bookingRequest->project->budget_min || $bookingRequest->project->budget_max)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Budget client</h3>
                                <p class="mt-1 text-gray-900">
                                    @if($bookingRequest->project->budget_min && $bookingRequest->project->budget_max)
                                        {{ number_format($bookingRequest->project->budget_min, 0, ',', ' ') }}€ - {{ number_format($bookingRequest->project->budget_max, 0, ',', ' ') }}€
                                    @elseif($bookingRequest->project->budget_max)
                                        Jusqu'à {{ number_format($bookingRequest->project->budget_max, 0, ',', ' ') }}€
                                    @else
                                        À partir de {{ number_format($bookingRequest->project->budget_min, 0, ',', ' ') }}€
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Client Message -->
                @if($bookingRequest->client_message)
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Message du client</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700 whitespace-pre-line">{{ $bookingRequest->client_message }}</p>
                            <p class="mt-4 text-xs text-gray-400">Envoyé {{ $bookingRequest->sent_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endif

                <!-- Response Form -->
                @if($bookingRequest->status === \App\Enums\BookingStatus::Pending)
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Répondre à la demande</h2>
                        </div>
                        <form action="{{ route('photographer.requests.update', $bookingRequest) }}" method="POST" class="p-6 space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="photographer_response" class="block text-sm font-medium text-gray-700">Votre message (optionnel)</label>
                                <textarea id="photographer_response" name="photographer_response" rows="4"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Répondez au client...">{{ old('photographer_response') }}</textarea>
                                @error('photographer_response')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="proposed_price" class="block text-sm font-medium text-gray-700">Votre tarif proposé (€)</label>
                                <input type="number" id="proposed_price" name="proposed_price" min="0" step="0.01"
                                    value="{{ old('proposed_price') }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Ex: 500">
                                @error('proposed_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-3 pt-4">
                                <button type="submit" name="status" value="accepted"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Accepter la demande
                                </button>
                                <button type="submit" name="status" value="declined"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Décliner
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Your Response -->
                @if($bookingRequest->photographer_response)
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Votre réponse</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700 whitespace-pre-line">{{ $bookingRequest->photographer_response }}</p>
                            @if($bookingRequest->proposed_price)
                                <p class="mt-3 text-sm font-medium text-gray-900">
                                    Tarif proposé : {{ number_format($bookingRequest->proposed_price, 2, ',', ' ') }}€
                                </p>
                            @endif
                            <p class="mt-4 text-xs text-gray-400">Répondu {{ $bookingRequest->responded_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar - Client Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Client</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-semibold text-lg">
                                    {{ substr($bookingRequest->project->client->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">{{ $bookingRequest->project->client->name }}</p>
                                <p class="text-sm text-gray-500">{{ $bookingRequest->project->client->email }}</p>
                            </div>
                        </div>
                        @if($bookingRequest->project->client->phone)
                            <div class="mt-4 flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $bookingRequest->project->client->phone }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Récapitulatif</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Demande reçue</span>
                            <span class="text-gray-900">{{ $bookingRequest->sent_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($bookingRequest->responded_at)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Répondu le</span>
                                <span class="text-gray-900">{{ $bookingRequest->responded_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                        @if($bookingRequest->proposed_price)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Tarif proposé</span>
                                <span class="font-medium text-gray-900">{{ number_format($bookingRequest->proposed_price, 2, ',', ' ') }}€</span>
                            </div>
                        @endif
                    </div>

                    @if($bookingRequest->status === \App\Enums\BookingStatus::Pending)
                        <div class="px-6 pb-6">
                            <form action="{{ route('photographer.requests.destroy', $bookingRequest) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ? Le client sera notifié.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer la demande
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-photographer-layout>
