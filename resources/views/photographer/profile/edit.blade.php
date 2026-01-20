<x-photographer-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mon profil</h1>
            <p class="mt-1 text-sm text-gray-600">Gérez vos informations et vos spécialités</p>
        </div>

        <!-- Profile Form -->
        <div class="bg-white rounded-xl shadow-sm">
            <form action="{{ route('photographer.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informations générales</h2>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700">Biographie</label>
                        <textarea id="bio" name="bio" rows="4"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Présentez-vous aux clients potentiels...">{{ old('bio', $photographer->bio) }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keywords -->
                    <div>
                        <label for="keywords" class="block text-sm font-medium text-gray-700">Mots-clés</label>
                        <textarea id="keywords" name="keywords" rows="2"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="mariage, reportage corporate, portrait studio, événementiel...">{{ old('keywords', $photographer->keywords) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Séparez vos mots-clés par des virgules. Ces mots-clés ne sont pas visibles publiquement mais améliorent la correspondance avec les projets des clients.</p>
                        @error('keywords')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Experience & Phone -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="experience_years" class="block text-sm font-medium text-gray-700">Années d'expérience</label>
                            <input type="number" id="experience_years" name="experience_years" min="0" max="50"
                                value="{{ old('experience_years', $photographer->experience_years) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('experience_years')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="tel" id="phone" name="phone"
                                value="{{ old('phone', auth()->user()->phone) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="+33 6 00 00 00 00">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Rates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Tarif horaire (€)</label>
                            <input type="number" id="hourly_rate" name="hourly_rate" min="0" step="0.01"
                                value="{{ old('hourly_rate', $photographer->hourly_rate) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('hourly_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="daily_rate" class="block text-sm font-medium text-gray-700">Tarif journalier (€)</label>
                            <input type="number" id="daily_rate" name="daily_rate" min="0" step="0.01"
                                value="{{ old('daily_rate', $photographer->daily_rate) }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('daily_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Portfolio URL -->
                    <div>
                        <label for="portfolio_url" class="block text-sm font-medium text-gray-700">URL du portfolio</label>
                        <input type="url" id="portfolio_url" name="portfolio_url"
                            value="{{ old('portfolio_url', $photographer->portfolio_url) }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="https://monportfolio.com">
                        @error('portfolio_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>

        <!-- Specialties Form -->
        <div class="bg-white rounded-xl shadow-sm">
            <form action="{{ route('photographer.profile.specialties') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Mes spécialités</h2>
                    <p class="mt-1 text-sm text-gray-500">Sélectionnez vos domaines d'expertise et indiquez votre niveau</p>
                </div>

                <div class="p-6">
                    @error('specialties')
                        <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="space-y-4">
                        @foreach($specialties as $specialty)
                            @php
                                $photographerSpecialty = $photographer->specialties->firstWhere('id', $specialty->id);
                                $isSelected = $photographerSpecialty !== null;
                                $level = $photographerSpecialty?->pivot->experience_level ?? 'intermediate';
                            @endphp

                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg {{ $isSelected ? 'bg-indigo-50 border-indigo-200' : '' }}">
                                <label class="flex items-center cursor-pointer flex-1">
                                    <input type="checkbox"
                                        name="specialties[{{ $loop->index }}][id]"
                                        value="{{ $specialty->id }}"
                                        {{ $isSelected ? 'checked' : '' }}
                                        class="specialty-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                        data-index="{{ $loop->index }}">
                                    <span class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">{{ $specialty->name }}</span>
                                        <span class="block text-sm text-gray-500">{{ $specialty->description }}</span>
                                    </span>
                                </label>

                                <select name="specialties[{{ $loop->index }}][level]"
                                    class="specialty-level ml-4 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 {{ !$isSelected ? 'hidden' : '' }}"
                                    data-index="{{ $loop->index }}">
                                    <option value="beginner" {{ $level === 'beginner' ? 'selected' : '' }}>Débutant</option>
                                    <option value="intermediate" {{ $level === 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                                    <option value="expert" {{ $level === 'expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Mettre à jour mes spécialités
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.specialty-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const index = this.dataset.index;
                const levelSelect = document.querySelector(`.specialty-level[data-index="${index}"]`);
                const container = this.closest('.flex');

                if (this.checked) {
                    levelSelect.classList.remove('hidden');
                    container.classList.add('bg-indigo-50', 'border-indigo-200');
                } else {
                    levelSelect.classList.add('hidden');
                    container.classList.remove('bg-indigo-50', 'border-indigo-200');
                }
            });
        });
    </script>
</x-photographer-layout>
