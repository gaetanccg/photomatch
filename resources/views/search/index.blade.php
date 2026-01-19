<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rechercher des photographes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar Filters -->
                <div class="lg:w-80 flex-shrink-0">
                    <form action="{{ route('search.index') }}" method="GET" class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                        @if($project)
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                        @endif

                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h3>

                        <!-- Specialties -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Spécialités</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($specialties as $specialty)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="specialty_ids[]" value="{{ $specialty->id }}"
                                               class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                               {{ in_array($specialty->id, (array) ($filters['specialty_ids'] ?? [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $specialty->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-6">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Localisation</label>
                            <input type="text" name="location" id="location" value="{{ $filters['location'] ?? '' }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Ex: Paris">
                        </div>

                        <!-- Budget Range -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Budget (€/h)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_budget" value="{{ $filters['min_budget'] ?? '' }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Min" min="0">
                                <input type="number" name="max_budget" value="{{ $filters['max_budget'] ?? '' }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Max" min="0">
                            </div>
                        </div>

                        <!-- Available Date -->
                        <div class="mb-6">
                            <label for="available_date" class="block text-sm font-medium text-gray-700 mb-2">Disponible le</label>
                            <input type="date" name="available_date" id="available_date" value="{{ $filters['available_date'] ?? '' }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Min Rating -->
                        <div class="mb-6">
                            <label for="min_rating" class="block text-sm font-medium text-gray-700 mb-2">Note minimum</label>
                            <select name="min_rating" id="min_rating"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Toutes les notes</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ ($filters['min_rating'] ?? '') == $i ? 'selected' : '' }}>
                                        {{ $i }}+ étoile(s)
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-2">
                            <button type="submit"
                                    class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Appliquer les filtres
                            </button>
                            <a href="{{ route('search.index', $project ? ['project_id' => $project->id] : []) }}"
                               class="block w-full px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg text-center hover:bg-gray-50">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Results -->
                <div class="flex-1">
                    <!-- Project Info Banner -->
                    @if($project)
                        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-indigo-600 font-medium">Recherche pour le projet</p>
                                    <p class="text-lg font-semibold text-indigo-900">{{ $project->title }}</p>
                                </div>
                                <a href="{{ route('client.projects.show', $project) }}"
                                   class="text-sm text-indigo-600 hover:text-indigo-700">
                                    Voir le projet &rarr;
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Results Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">{{ $photographers->total() }}</span> photographe(s) trouvé(s)
                            </p>
                            @if($useMatching)
                                <p class="text-xs text-indigo-600">Triés par pertinence pour votre projet</p>
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
                        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun photographe trouvé</h3>
                            <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos critères de recherche.</p>
                        </div>
                    @endif
                </div>
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
                             class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                            <form action="{{ route('booking-requests.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="photographer_id" x-bind:value="photographerId">

                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Envoyer une demande à <span x-text="photographerName"></span>
                                        </h3>
                                        <div class="mt-4 space-y-4">
                                            <!-- Project Selection -->
                                            <div>
                                                <label for="project_id" class="block text-sm font-medium text-gray-700">Projet</label>
                                                <select name="project_id" id="project_id" required
                                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    @if($project)
                                                        <option value="{{ $project->id }}" selected>{{ $project->title }}</option>
                                                    @endif
                                                    @foreach(auth()->user()->photoProjects()->where('status', 'published')->get() as $userProject)
                                                        @if(!$project || $userProject->id !== $project->id)
                                                            <option value="{{ $userProject->id }}">{{ $userProject->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Message -->
                                            <div>
                                                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                                <textarea name="message" id="message" rows="4"
                                                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                          placeholder="Décrivez votre projet et vos attentes..."></textarea>
                                            </div>

                                            <!-- Proposed Rate -->
                                            <div>
                                                <label for="proposed_rate" class="block text-sm font-medium text-gray-700">
                                                    Prix proposé (optionnel)
                                                    <span class="text-xs text-gray-500 ml-1">Tarif du photographe: <span x-text="photographerRate"></span>€/h</span>
                                                </label>
                                                <div class="mt-1 relative rounded-lg shadow-sm">
                                                    <input type="number" name="proposed_rate" id="proposed_rate"
                                                           x-bind:placeholder="photographerRate"
                                                           min="0" step="10"
                                                           class="block w-full rounded-lg border-gray-300 pr-12 focus:border-indigo-500 focus:ring-indigo-500">
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                        <span class="text-gray-500 sm:text-sm">€</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <button type="submit"
                                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Envoyer la demande
                                    </button>
                                    <button type="button" @click="open = false"
                                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                        Annuler
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
