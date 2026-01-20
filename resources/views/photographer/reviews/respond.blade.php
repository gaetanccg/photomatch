<x-photographer-layout>
    <div class="max-w-2xl mx-auto">
        <x-breadcrumb :items="[
            ['label' => 'Mes avis', 'url' => route('photographer.reviews.index')],
            ['label' => 'Répondre'],
        ]" />

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-xl font-semibold text-gray-900">Répondre à un avis</h1>
            </div>

            <!-- Original Review -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-start">
                    <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                        <span class="text-gray-600 font-medium">
                            {{ substr($review->client->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="ml-4">
                        <div class="flex items-center">
                            <p class="font-medium text-gray-900">{{ $review->client->name }}</p>
                            <span class="mx-2 text-gray-300">|</span>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if($review->comment)
                    <p class="mt-4 text-gray-700">{{ $review->comment }}</p>
                @endif
            </div>

            <form action="{{ route('photographer.reviews.respond.store', $review) }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="photographer_response" class="block text-sm font-medium text-gray-700 mb-2">
                        Votre réponse <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="photographer_response"
                        id="photographer_response"
                        rows="5"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Répondez à cet avis de manière professionnelle..."
                        required
                    >{{ old('photographer_response') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Maximum 1000 caractères</p>
                    @error('photographer_response')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('photographer.reviews.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                        Annuler
                    </a>
                    <x-button type="submit" loading-text="Publication...">
                        Publier ma réponse
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-photographer-layout>
