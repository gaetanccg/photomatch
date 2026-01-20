<x-client-layout>
    <div class="max-w-2xl mx-auto">
        <x-breadcrumb :items="[
            ['label' => 'Mes demandes', 'url' => route('client.requests.index')],
            ['label' => 'Évaluer'],
        ]" />

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-xl font-semibold text-gray-900">Évaluer votre expérience</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Partagez votre avis sur votre collaboration avec {{ $bookingRequest->photographer->user->name }}
                </p>
            </div>

            <!-- Mission Info -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full bg-emerald-100 flex items-center justify-center">
                        <span class="text-emerald-700 font-semibold text-lg">
                            {{ substr($bookingRequest->photographer->user->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="ml-4">
                        <p class="font-medium text-gray-900">{{ $bookingRequest->photographer->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $bookingRequest->project->title }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('client.reviews.store', $bookingRequest) }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Note globale <span class="text-red-500">*</span>
                    </label>
                    <div x-data="{ rating: {{ old('rating', 0) }}, hoverRating: 0 }" class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button
                                type="button"
                                @click="rating = {{ $i }}"
                                @mouseenter="hoverRating = {{ $i }}"
                                @mouseleave="hoverRating = 0"
                                class="p-1 focus:outline-none"
                            >
                                <svg
                                    class="w-8 h-8 transition-colors"
                                    :class="{
                                        'text-yellow-400': {{ $i }} <= (hoverRating || rating),
                                        'text-gray-300': {{ $i }} > (hoverRating || rating)
                                    }"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                        <input type="hidden" name="rating" x-model="rating">
                        <span x-show="rating > 0" class="ml-2 text-sm text-gray-600" x-text="rating + '/5'"></span>
                    </div>
                    @error('rating')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                        Votre commentaire
                    </label>
                    <textarea
                        name="comment"
                        id="comment"
                        rows="5"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Décrivez votre expérience avec ce photographe..."
                    >{{ old('comment') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Maximum 2000 caractères</p>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('client.requests.show', $bookingRequest) }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                        Annuler
                    </a>
                    <x-button type="submit" loading-text="Publication...">
                        Publier mon avis
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-client-layout>
