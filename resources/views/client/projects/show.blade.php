<x-client-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('client.projects.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                    &larr; Retour aux projets
                </a>
                <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $project->title }}</h1>
                <div class="mt-2 flex items-center gap-3">
                    <x-status-badge :status="$project->status" />
                    <span class="text-sm text-gray-500">
                        Créé le {{ $project->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('search.index', ['project_id' => $project->id]) }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Rechercher des photographes
                </a>
                @if(in_array($project->status, ['draft', 'published']))
                    <a href="{{ route('client.projects.edit', $project) }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Project Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $project->description }}</p>
                </div>

                <!-- Matching Photographers -->
                @if($project->status === 'published' && $matchingPhotographers->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Photographes recommandés</h2>
                                <p class="text-sm text-gray-500">Basé sur votre projet et l'algorithme de matching</p>
                            </div>
                            <a href="{{ route('search.index', ['project_id' => $project->id]) }}"
                               class="text-sm text-indigo-600 hover:text-indigo-700">
                                Voir tous
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($matchingPhotographers as $photographer)
                                @php
                                    $alreadyRequested = $project->bookingRequests->contains('photographer_id', $photographer->id);
                                @endphp
                                <div class="border border-gray-100 rounded-lg p-4 hover:border-indigo-200 transition-colors {{ $alreadyRequested ? 'opacity-60' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center">
                                            <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                                @if($photographer->user->avatar ?? false)
                                                    <img src="{{ $photographer->user->avatar }}" alt="" class="h-12 w-12 rounded-full object-cover">
                                                @else
                                                    <span class="text-indigo-600 font-medium text-lg">
                                                        {{ substr($photographer->user->name ?? 'P', 0, 1) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900">{{ $photographer->user->name ?? 'Photographe' }}</p>
                                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                                    <span>{{ number_format($photographer->hourly_rate, 0, ',', ' ') }}€/h</span>
                                                    @if($photographer->rating)
                                                        <span>•</span>
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                            {{ number_format($photographer->rating, 1) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                                {{ $photographer->matching_score['total'] }}% match
                                            </span>
                                        </div>
                                    </div>

                                    @if($photographer->specialties->count() > 0)
                                        <div class="mt-3 flex flex-wrap gap-1">
                                            @foreach($photographer->specialties->take(3) as $specialty)
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">
                                                    {{ $specialty->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-3 flex items-center justify-between">
                                        @if($alreadyRequested)
                                            <span class="text-sm text-gray-500">Demande déjà envoyée</span>
                                        @else
                                            <button type="button"
                                                    onclick="openBookingModal({{ $photographer->id }}, '{{ addslashes($photographer->user->name ?? 'Photographe') }}')"
                                                    class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                                Contacter
                                            </button>
                                        @endif
                                        <a href="{{ route('photographers.show', $photographer) }}" class="text-sm text-gray-500 hover:text-gray-700">
                                            Voir profil
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($project->status === 'draft')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-yellow-800">Projet en brouillon</h3>
                                <p class="mt-1 text-sm text-yellow-700">
                                    Publiez votre projet pour voir les photographes recommandés et pouvoir envoyer des demandes.
                                </p>
                                <a href="{{ route('client.projects.edit', $project) }}"
                                   class="mt-2 inline-flex items-center text-sm font-medium text-yellow-800 hover:text-yellow-900">
                                    Modifier et publier
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Booking Requests -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Demandes envoyées</h2>
                        <span class="text-sm text-gray-500">{{ $project->bookingRequests->count() }} demande(s)</span>
                    </div>

                    @if($project->bookingRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($project->bookingRequests as $request)
                                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                            @if($request->photographer->user->avatar)
                                                <img src="{{ $request->photographer->user->avatar }}" alt="" class="h-12 w-12 rounded-full object-cover">
                                            @else
                                                <span class="text-indigo-600 font-medium text-lg">
                                                    {{ substr($request->photographer->user->name ?? 'P', 0, 1) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-medium text-gray-900">{{ $request->photographer->user->name ?? 'Photographe' }}</p>
                                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                                <span>Envoyée le {{ $request->created_at->format('d/m/Y') }}</span>
                                                @if($request->proposed_rate)
                                                    <span>•</span>
                                                    <span>{{ number_format($request->proposed_rate, 0, ',', ' ') }}€ proposé</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <x-status-badge :status="$request->status" />
                                        <a href="{{ route('client.requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-700 text-sm">
                                            Voir détail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Aucune demande envoyée pour ce projet</p>
                            <a href="{{ route('search.index', ['project_id' => $project->id]) }}"
                               class="mt-3 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700">
                                Rechercher des photographes
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Project Info Card -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Détails du projet</h2>

                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type de projet</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @php
                                    $types = [
                                        'event' => 'Événement',
                                        'product' => 'Produit',
                                        'real_estate' => 'Immobilier',
                                        'corporate' => 'Corporate',
                                        'portrait' => 'Portrait',
                                        'other' => 'Autre',
                                    ];
                                @endphp
                                {{ $types[$project->project_type] ?? $project->project_type }}
                            </dd>
                        </div>

                        @if($project->event_date)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de l'événement</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $project->event_date->format('d/m/Y') }}</dd>
                            </div>
                        @endif

                        @if($project->date_start)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Période souhaitée</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $project->date_start->format('d/m/Y') }}
                                    @if($project->date_end)
                                        - {{ $project->date_end->format('d/m/Y') }}
                                    @endif
                                </dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Localisation</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $project->location }}</dd>
                        </div>

                        @if($project->budget_min || $project->budget_max)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Budget</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($project->budget_min && $project->budget_max)
                                        {{ number_format($project->budget_min, 0, ',', ' ') }}€ - {{ number_format($project->budget_max, 0, ',', ' ') }}€
                                    @elseif($project->budget_min)
                                        À partir de {{ number_format($project->budget_min, 0, ',', ' ') }}€
                                    @else
                                        Jusqu'à {{ number_format($project->budget_max, 0, ',', ' ') }}€
                                    @endif
                                </dd>
                            </div>
                        @endif

                        @if($project->estimated_duration)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Durée estimée</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $project->estimated_duration }} heure(s)</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Danger Zone -->
                @if(!$project->bookingRequests()->where('status', 'accepted')->exists())
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-red-100">
                        <h2 class="text-lg font-semibold text-red-600 mb-4">Zone de danger</h2>
                        <p class="text-sm text-gray-500 mb-4">
                            La suppression du projet est définitive et supprimera également toutes les demandes associées.
                        </p>
                        <form action="{{ route('client.projects.destroy', $project) }}" method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Supprimer ce projet
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    @if($project->status === 'published')
    <div id="bookingModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeBookingModal()"></div>

            <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                <form action="{{ route('booking-requests.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <input type="hidden" name="photographer_id" id="modal_photographer_id">

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4" id="modal-photographer-name">Envoyer une demande</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700">Message (optionnel)</label>
                                <textarea name="message" id="message" rows="4"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Présentez votre projet et vos attentes..."></textarea>
                            </div>

                            <div>
                                <label for="proposed_rate" class="block text-sm font-medium text-gray-700">Tarif proposé (€, optionnel)</label>
                                <input type="number" name="proposed_rate" id="proposed_rate" min="0" step="0.01"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Ex: 500">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Envoyer la demande
                        </button>
                        <button type="button" onclick="closeBookingModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openBookingModal(photographerId, photographerName) {
            document.getElementById('modal_photographer_id').value = photographerId;
            document.getElementById('modal-photographer-name').textContent = 'Contacter ' + photographerName;
            document.getElementById('bookingModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeBookingModal();
        });
    </script>
    @endif
</x-client-layout>
