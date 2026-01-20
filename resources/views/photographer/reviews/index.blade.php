<x-photographer-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes avis clients</h1>
            <p class="mt-1 text-sm text-gray-600">Consultez et répondez aux avis de vos clients</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-4">
                <p class="text-sm text-gray-500">Total des avis</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_reviews'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4">
                <p class="text-sm text-gray-500">Note moyenne</p>
                <div class="flex items-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['average_rating'] ? number_format($stats['average_rating'], 1) : '-' }}</p>
                    <svg class="w-6 h-6 text-yellow-400 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4">
                <p class="text-sm text-gray-500">Avis 5 étoiles</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $stats['five_star'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4">
                <p class="text-sm text-gray-500">En attente de réponse</p>
                <p class="text-2xl font-bold text-orange-500">{{ $stats['pending_responses'] }}</p>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($reviews->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($reviews as $review)
                        <div class="p-6">
                            <div class="flex items-start justify-between">
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
                                        <p class="text-sm text-gray-500">
                                            {{ $review->bookingRequest->project->title }} - {{ $review->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($review->comment)
                                <p class="mt-4 text-gray-700">{{ $review->comment }}</p>
                            @endif

                            @if($review->photographer_response)
                                <div class="mt-4 pl-4 border-l-4 border-emerald-200 bg-emerald-50 p-4 rounded-r-lg">
                                    <p class="text-sm font-medium text-emerald-800">Votre réponse</p>
                                    <p class="mt-1 text-gray-700">{{ $review->photographer_response }}</p>
                                    <p class="mt-2 text-xs text-gray-500">Répondu {{ $review->responded_at->diffForHumans() }}</p>
                                </div>
                            @else
                                <div class="mt-4">
                                    <a href="{{ route('photographer.reviews.respond', $review) }}" class="inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                        </svg>
                                        Répondre à cet avis
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($reviews->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $reviews->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun avis</h3>
                    <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore reçu d'avis clients.</p>
                </div>
            @endif
        </div>
    </div>
</x-photographer-layout>
