<x-app-layout>
    <div class="bg-gradient-to-b from-emerald-50 to-white">
        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if($project)
                <!-- Project Context Banner -->
                <div class="bg-white border-2 border-emerald-200 rounded-2xl p-6 mb-8 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-emerald-600">Recherche personnalisee pour votre projet</p>
                                <h2 class="text-xl font-bold text-gray-900">{{ $project->title }}</h2>
                                <p class="text-sm text-gray-500 mt-1">Les photographes sont tries par pertinence selon vos criteres</p>
                            </div>
                        </div>
                        <a href="{{ route('client.projects.show', $project) }}"
                           class="inline-flex items-center px-4 py-2 border border-emerald-600 text-emerald-600 rounded-lg hover:bg-emerald-50 transition text-sm font-medium">
                            Voir mon projet
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @else
                <!-- Welcome Banner with CTA -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">Trouvez le photographe ideal</h1>
                    <p class="text-gray-600 max-w-2xl mx-auto mb-6">
                        Parcourez notre selection de photographes professionnels ou creez un projet pour obtenir des recommandations personnalisees.
                    </p>
                    @auth
                        @if(auth()->user()->isClient())
                            <a href="{{ route('client.projects.create') }}"
                               class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-medium shadow-lg shadow-emerald-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Creer mon projet pour des recommandations sur-mesure
                            </a>
                        @endif
                    @else
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-medium shadow-lg shadow-emerald-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Creer un compte gratuit
                            </a>
                            <span class="text-gray-400">ou</span>
                            <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                                Se connecter
                            </a>
                        </div>
                    @endauth
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:w-72 flex-shrink-0">
                <form action="{{ route('search.index') }}" method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    @if($project)
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                    @endif

                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Affiner ma recherche
                    </h3>

                    <!-- Specialties -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type de photographie</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($specialties as $specialty)
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="specialty_ids[]" value="{{ $specialty->id }}"
                                           class="h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                                           {{ in_array($specialty->id, (array) ($filters['specialty_ids'] ?? [])) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-900">{{ $specialty->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="mb-6">
                        <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Ville ou region</label>
                        <input type="text" name="location" id="location" value="{{ $filters['location'] ?? '' }}"
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm"
                               placeholder="Ex: Paris, Lyon...">
                    </div>

                    <!-- Budget Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Budget horaire</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="relative">
                                <input type="number" name="min_budget" value="{{ $filters['min_budget'] ?? '' }}"
                                       class="w-full rounded-lg border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm pr-8"
                                       placeholder="Min" min="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">€</span>
                            </div>
                            <div class="relative">
                                <input type="number" name="max_budget" value="{{ $filters['max_budget'] ?? '' }}"
                                       class="w-full rounded-lg border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm pr-8"
                                       placeholder="Max" min="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">€</span>
                            </div>
                        </div>
                    </div>

                    <!-- Available Date -->
                    <div class="mb-6">
                        <label for="available_date" class="block text-sm font-semibold text-gray-700 mb-2">Disponible le</label>
                        <input type="date" name="available_date" id="available_date" value="{{ $filters['available_date'] ?? '' }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    </div>

                    <!-- Min Rating -->
                    <div class="mb-6">
                        <label for="min_rating" class="block text-sm font-semibold text-gray-700 mb-2">Note minimum</label>
                        <select name="min_rating" id="min_rating"
                                class="w-full rounded-lg border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                            <option value="">Toutes les notes</option>
                            @for($i = 4; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ ($filters['min_rating'] ?? '') == $i ? 'selected' : '' }}>
                                    {{ $i }}+ etoile(s)
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-2">
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                            Rechercher
                        </button>
                        <a href="{{ route('search.index', $project ? ['project_id' => $project->id] : []) }}"
                           class="block w-full px-4 py-2.5 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg text-center hover:bg-gray-50 transition">
                            Effacer les filtres
                        </a>
                    </div>
                </form>

                <!-- CTA Card -->
                @auth
                    @if(auth()->user()->isClient() && !$project)
                        <div class="mt-6 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white">
                            <div class="flex items-center mb-3">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                <h4 class="font-bold">Astuce</h4>
                            </div>
                            <p class="text-emerald-50 text-sm mb-4">
                                Creez un projet pour recevoir des recommandations personnalisees basees sur vos besoins.
                            </p>
                            <a href="{{ route('client.projects.create') }}"
                               class="block w-full px-4 py-2.5 bg-white text-emerald-700 text-sm font-semibold rounded-lg text-center hover:bg-emerald-50 transition">
                                Creer mon projet
                            </a>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Results -->
            <div class="flex-1">
                <!-- Results Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <div>
                        <p class="text-gray-900">
                            <span class="font-bold text-2xl">{{ $photographers->total() }}</span>
                            <span class="text-gray-600">photographe{{ $photographers->total() > 1 ? 's' : '' }} disponible{{ $photographers->total() > 1 ? 's' : '' }}</span>
                        </p>
                        @if($useMatching)
                            <p class="text-sm text-emerald-600 flex items-center mt-1">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Tries par pertinence pour votre projet
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Results Grid -->
                @if($photographers->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($photographers as $photographer)
                            <x-search-photographer-card
                                :photographer="$photographer"
                                :useMatching="$useMatching"
                                :project="$project"
                            />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($photographers->hasPages())
                        <div class="mt-8">
                            {{ $photographers->links() }}
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun photographe trouve</h3>
                        <p class="text-gray-500 mb-6">Essayez d'elargir vos criteres de recherche pour voir plus de resultats.</p>
                        <a href="{{ route('search.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
                            Voir tous les photographes
                        </a>
                    </div>
                @endif

                <!-- Bottom CTA for non-authenticated users -->
                @guest
                    <div class="mt-12 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-8 text-center text-white">
                        <h3 class="text-2xl font-bold mb-3">Pret a trouver votre photographe ?</h3>
                        <p class="text-emerald-100 mb-6 max-w-xl mx-auto">
                            Creez un compte gratuit pour contacter les photographes, creer vos projets et recevoir des recommandations personnalisees.
                        </p>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-8 py-3 bg-white text-emerald-700 rounded-xl hover:bg-emerald-50 transition font-semibold">
                            Commencer gratuitement
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>

    <!-- Booking Request Modal -->
    @auth
        @if(auth()->user()->isClient())
            <div x-data="{ open: false, photographerId: null, photographerName: '', photographerRate: 0 }"
                 x-on:open-booking-modal.window="open = true; photographerId = $event.detail.photographerId; photographerName = $event.detail.photographerName; photographerRate = $event.detail.photographerRate">

                <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <!-- Background overlay -->
                        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <!-- Modal panel -->
                        <div x-show="open" x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                            <form action="{{ route('booking-requests.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="photographer_id" x-bind:value="photographerId">

                                <div class="text-center mb-6">
                                    <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-emerald-100 mb-4">
                                        <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Contacter <span x-text="photographerName"></span></h3>
                                    <p class="text-gray-500 text-sm mt-1">Envoyez votre demande de reservation</p>
                                </div>

                                <div class="space-y-4">
                                    <!-- Project Selection -->
                                    <div>
                                        <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-1">Pour quel projet ?</label>
                                        <select name="project_id" id="project_id" required
                                                class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="">Selectionnez un projet</option>
                                            @if($project)
                                                <option value="{{ $project->id }}" selected>{{ $project->title }}</option>
                                            @endif
                                            @foreach(auth()->user()->photoProjects()->where('status', 'published')->get() as $userProject)
                                                @if(!$project || $userProject->id !== $project->id)
                                                    <option value="{{ $userProject->id }}">{{ $userProject->title }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Pas encore de projet ?
                                            <a href="{{ route('client.projects.create') }}" class="text-emerald-600 hover:underline">Creer un projet</a>
                                        </p>
                                    </div>

                                    <!-- Message -->
                                    <div>
                                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-1">Votre message</label>
                                        <textarea name="message" id="message" rows="4"
                                                  class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                                  placeholder="Presentez-vous et decrivez vos attentes..."></textarea>
                                    </div>

                                    <!-- Proposed Rate -->
                                    <div>
                                        <label for="proposed_rate" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Budget propose (optionnel)
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="proposed_rate" id="proposed_rate"
                                                   x-bind:placeholder="'Tarif indicatif: ' + photographerRate + '€/h'"
                                                   min="0" step="10"
                                                   class="block w-full rounded-lg border-gray-200 pr-8 focus:border-emerald-500 focus:ring-emerald-500">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">€</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                    <button type="button" @click="open = false"
                                            class="w-full sm:w-auto px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                        Annuler
                                    </button>
                                    <button type="submit"
                                            class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                                        Envoyer ma demande
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
</x-app-layout>
