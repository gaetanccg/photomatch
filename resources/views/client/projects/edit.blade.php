<x-client-layout>
    <div class="max-w-3xl">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('client.projects.show', $project) }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                &larr; Retour au projet
            </a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">Modifier le projet</h1>
            <p class="mt-1 text-sm text-gray-600">Mettez à jour les informations de votre projet</p>
        </div>

        <!-- Form -->
        <form action="{{ route('client.projects.update', $project) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Titre du projet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Ex: Mariage de Sophie et Marc">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project Type -->
                <div>
                    <label for="project_type" class="block text-sm font-medium text-gray-700">
                        Type de projet <span class="text-red-500">*</span>
                    </label>
                    <select name="project_type" id="project_type"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Sélectionnez un type</option>
                        @foreach($projectTypes as $value => $label)
                            <option value="{{ $value }}" {{ old('project_type', $project->project_type) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Décrivez votre projet en détail..."
                              maxlength="2000">{{ old('description', $project->description) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        <span id="description-count">0</span>/2000 caractères
                    </p>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div id="event-date-wrapper" class="{{ old('project_type', $project->project_type) === 'event' ? '' : 'hidden' }}">
                        <label for="event_date" class="block text-sm font-medium text-gray-700">
                            Date de l'événement <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="event_date" id="event_date"
                               value="{{ old('event_date', $project->event_date?->format('Y-m-d')) }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('event_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="date-start-wrapper" class="{{ old('project_type', $project->project_type) === 'event' ? 'hidden' : '' }}">
                        <label for="date_start" class="block text-sm font-medium text-gray-700">
                            Date de début souhaitée
                        </label>
                        <input type="date" name="date_start" id="date_start"
                               value="{{ old('date_start', $project->date_start?->format('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('date_start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="date-end-wrapper" class="{{ old('project_type', $project->project_type) === 'event' ? 'hidden' : '' }}">
                        <label for="date_end" class="block text-sm font-medium text-gray-700">
                            Date de fin souhaitée
                        </label>
                        <input type="date" name="date_end" id="date_end"
                               value="{{ old('date_end', $project->date_end?->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('date_end')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">
                        Localisation <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" id="location" value="{{ old('location', $project->location) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Ex: Paris 75008, France">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hidden lat/lng fields -->
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $project->latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $project->longitude) }}">

                <!-- Budget -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Budget</label>
                    <div class="mt-1 grid grid-cols-2 gap-4">
                        <div>
                            <div class="relative rounded-lg shadow-sm">
                                <input type="number" name="budget_min" id="budget_min"
                                       value="{{ old('budget_min', $project->budget_min) }}"
                                       min="0" step="10"
                                       class="block w-full rounded-lg border-gray-300 pr-12 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Min">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">€</span>
                                </div>
                            </div>
                            @error('budget_min')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <div class="relative rounded-lg shadow-sm">
                                <input type="number" name="budget_max" id="budget_max"
                                       value="{{ old('budget_max', $project->budget_max) }}"
                                       min="0" step="10"
                                       class="block w-full rounded-lg border-gray-300 pr-12 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Max">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">€</span>
                                </div>
                            </div>
                            @error('budget_max')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Indiquez votre fourchette de budget (optionnel)</p>
                </div>

                <!-- Duration -->
                <div>
                    <label for="estimated_duration" class="block text-sm font-medium text-gray-700">
                        Durée estimée (heures)
                    </label>
                    <input type="number" name="estimated_duration" id="estimated_duration"
                           value="{{ old('estimated_duration', $project->estimated_duration) }}"
                           min="1"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="Ex: 8">
                    @error('estimated_duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Toggle -->
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="status" class="text-sm font-medium text-gray-700">Publier le projet</label>
                            <p class="text-sm text-gray-500">Un projet publié sera visible pour la recherche de photographes</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="published" class="sr-only peer"
                                   {{ old('status', $project->status) === 'published' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('client.projects.show', $project) }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Annuler
                </a>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <script>
        // Description character counter
        const description = document.getElementById('description');
        const descriptionCount = document.getElementById('description-count');
        description.addEventListener('input', function() {
            descriptionCount.textContent = this.value.length;
        });
        descriptionCount.textContent = description.value.length;

        // Show/hide event date based on project type
        const projectType = document.getElementById('project_type');
        const eventDateWrapper = document.getElementById('event-date-wrapper');
        const dateStartWrapper = document.getElementById('date-start-wrapper');
        const dateEndWrapper = document.getElementById('date-end-wrapper');

        projectType.addEventListener('change', function() {
            if (this.value === 'event') {
                eventDateWrapper.classList.remove('hidden');
                dateStartWrapper.classList.add('hidden');
                dateEndWrapper.classList.add('hidden');
            } else {
                eventDateWrapper.classList.add('hidden');
                dateStartWrapper.classList.remove('hidden');
                dateEndWrapper.classList.remove('hidden');
            }
        });

        // Date validation
        const dateStart = document.getElementById('date_start');
        const dateEnd = document.getElementById('date_end');
        dateStart.addEventListener('change', function() {
            dateEnd.min = this.value;
        });
    </script>
</x-client-layout>
