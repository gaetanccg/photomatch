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

        <!-- Tags Form -->
        <div class="bg-white rounded-xl shadow-sm">
            <form action="{{ route('photographer.profile.tags') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Mes tags</h2>
                    <p class="mt-1 text-sm text-gray-500">Ajoutez des tags pour améliorer la correspondance avec les projets des clients (max 15)</p>
                </div>

                <div class="p-6">
                    @error('tags')
                        <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('tags.*')
                        <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div id="tags-container" class="space-y-3">
                        @forelse($photographer->tags as $tag)
                            <div class="tag-row flex items-center gap-2">
                                <input type="text" name="tags[]" value="{{ $tag->name }}"
                                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    placeholder="Ex: mariage, portrait, retouche HDR..."
                                    minlength="2" maxlength="50">
                                <button type="button" onclick="this.closest('.tag-row').remove(); updateTagCount()"
                                    class="inline-flex items-center p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @empty
                            <p id="no-tags-message" class="text-sm text-gray-500">Aucun tag pour le moment.</p>
                        @endforelse
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        <button type="button" id="add-tag-btn" onclick="addTag()"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajouter un tag
                        </button>
                        <span id="tag-count" class="text-xs text-gray-500">{{ $photographer->tags->count() }}/15</span>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Mettre à jour mes tags
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($specialties as $specialty)
                            @php
                                $photographerSpecialty = $photographer->specialties->firstWhere('id', $specialty->id);
                                $isSelected = $photographerSpecialty !== null;
                                $level = $photographerSpecialty?->pivot->experience_level ?? 'intermediate';
                            @endphp

                            <div class="specialty-card relative rounded-lg border-2 p-4 transition-all cursor-pointer
                                {{ $isSelected ? 'border-indigo-500 bg-indigo-50/50' : 'border-gray-200 hover:border-gray-300' }}"
                                data-index="{{ $loop->index }}">

                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox"
                                        name="specialties[{{ $loop->index }}][id]"
                                        value="{{ $specialty->id }}"
                                        {{ $isSelected ? 'checked' : '' }}
                                        class="specialty-checkbox sr-only"
                                        data-index="{{ $loop->index }}">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-semibold text-gray-900">{{ $specialty->name }}</span>
                                            <span class="specialty-check w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 transition-colors
                                                {{ $isSelected ? 'bg-indigo-500 text-white' : 'border-2 border-gray-300' }}">
                                                <svg class="w-3 h-3 {{ $isSelected ? '' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        @if($specialty->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ $specialty->description }}</p>
                                        @endif
                                    </div>
                                </label>

                                <div class="specialty-level mt-3 flex gap-1 {{ !$isSelected ? 'hidden' : '' }}" data-index="{{ $loop->index }}">
                                    @foreach(['beginner' => 'Débutant', 'intermediate' => 'Intermédiaire', 'expert' => 'Expert'] as $value => $label)
                                        <label class="flex-1">
                                            <input type="radio"
                                                name="specialties[{{ $loop->parent->index }}][level]"
                                                value="{{ $value }}"
                                                {{ $level === $value ? 'checked' : '' }}
                                                class="sr-only peer">
                                            <span class="block text-center text-xs py-1.5 rounded-md cursor-pointer transition-all border
                                                peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500
                                                bg-white text-gray-600 border-gray-200 hover:border-gray-300">
                                                {{ $label }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
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
        const MAX_TAGS = 15;

        function updateTagCount() {
            const count = document.querySelectorAll('.tag-row').length;
            document.getElementById('tag-count').textContent = count + '/15';
            document.getElementById('add-tag-btn').disabled = count >= MAX_TAGS;

            const noTagsMsg = document.getElementById('no-tags-message');
            if (noTagsMsg && count > 0) {
                noTagsMsg.remove();
            }
        }

        function addTag() {
            const container = document.getElementById('tags-container');
            const count = container.querySelectorAll('.tag-row').length;
            if (count >= MAX_TAGS) return;

            const noTagsMsg = document.getElementById('no-tags-message');
            if (noTagsMsg) noTagsMsg.remove();

            const row = document.createElement('div');
            row.className = 'tag-row flex items-center gap-2';
            row.innerHTML = `
                <input type="text" name="tags[]" value=""
                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    placeholder="Ex: mariage, portrait, retouche HDR..."
                    minlength="2" maxlength="50">
                <button type="button" onclick="this.closest('.tag-row').remove(); updateTagCount()"
                    class="inline-flex items-center p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            container.appendChild(row);
            row.querySelector('input').focus();
            updateTagCount();
        }

        document.querySelectorAll('.specialty-card').forEach(card => {
            const checkbox = card.querySelector('.specialty-checkbox');
            const check = card.querySelector('.specialty-check');
            const checkSvg = check.querySelector('svg');
            const levelGroup = card.querySelector('.specialty-level');

            card.addEventListener('click', function(e) {
                if (e.target.closest('.specialty-level')) return;
                checkbox.checked = !checkbox.checked;
                updateCard();
            });

            function updateCard() {
                if (checkbox.checked) {
                    card.classList.add('border-indigo-500', 'bg-indigo-50/50');
                    card.classList.remove('border-gray-200');
                    check.classList.add('bg-indigo-500', 'text-white');
                    check.classList.remove('border-2', 'border-gray-300');
                    checkSvg.classList.remove('hidden');
                    levelGroup.classList.remove('hidden');
                } else {
                    card.classList.remove('border-indigo-500', 'bg-indigo-50/50');
                    card.classList.add('border-gray-200');
                    check.classList.remove('bg-indigo-500', 'text-white');
                    check.classList.add('border-2', 'border-gray-300');
                    checkSvg.classList.add('hidden');
                    levelGroup.classList.add('hidden');
                }
            }
        });
    </script>
</x-photographer-layout>
