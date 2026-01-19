@props(['photographer', 'useMatching' => false, 'project' => null])

@if($photographer->user)
<div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
    <!-- Matching Score Badge -->
    @if($useMatching && isset($photographer->matching_score))
        @php
            $score = $photographer->matching_score['total'] ?? 0;
            $matchClass = match(true) {
                $score >= 80 => 'bg-green-100 text-green-800 border-green-200',
                $score >= 60 => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                default => 'bg-gray-100 text-gray-800 border-gray-200',
            };
            $matchLabel = match(true) {
                $score >= 80 => 'Excellent match',
                $score >= 60 => 'Bon match',
                default => 'Match possible',
            };
        @endphp
        <div class="px-4 py-2 {{ $matchClass }} border-b flex items-center justify-between">
            <span class="text-sm font-medium">{{ $matchLabel }}</span>
            <span class="text-lg font-bold">{{ round($score) }}%</span>
        </div>
    @endif

    <!-- Image/Avatar -->
    <div class="h-40 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
        @if($photographer->user->avatar)
            <img src="{{ $photographer->user->avatar }}" alt="{{ $photographer->user->name }}" class="h-full w-full object-cover">
        @else
            <span class="text-white font-bold text-4xl">
                {{ substr($photographer->user->name, 0, 1) }}
            </span>
        @endif
    </div>

    <!-- Content -->
    <div class="p-4">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-base font-semibold text-gray-900">{{ $photographer->user->name }}</h3>
            @if($photographer->is_verified)
                <span class="inline-flex items-center text-green-600" title="Profil vérifié">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            @endif
        </div>

        <!-- Rating -->
        @if($photographer->rating)
            <div class="mb-2">
                <x-rating-stars :rating="$photographer->getRawOriginal('rating')" />
            </div>
        @endif

        <!-- Score Breakdown (if matching) -->
        @if($useMatching && isset($photographer->matching_score['breakdown']))
            <div class="mb-3 p-2 bg-gray-50 rounded-lg">
                <p class="text-xs font-medium text-gray-600 mb-1">Détail du score</p>
                <div class="grid grid-cols-2 gap-x-2 gap-y-1 text-xs">
                    @foreach($photographer->matching_score['breakdown'] as $criteria => $score)
                        @php
                            $criteriaLabels = [
                                'specialty' => 'Spécialité',
                                'distance' => 'Distance',
                                'rating' => 'Note',
                                'experience' => 'Expérience',
                                'price' => 'Prix',
                            ];
                        @endphp
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ $criteriaLabels[$criteria] ?? ucfirst($criteria) }}</span>
                            <span class="font-medium text-gray-700">{{ round($score) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Specialties -->
        <div class="flex flex-wrap gap-1 mb-3">
            @foreach($photographer->specialties->take(3) as $specialty)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                    {{ $specialty->name }}
                </span>
            @endforeach
            @if($photographer->specialties->count() > 3)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                    +{{ $photographer->specialties->count() - 3 }}
                </span>
            @endif
        </div>

        <!-- Stats -->
        <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
            <span>{{ $photographer->experience_years }} ans exp.</span>
            <span>{{ $photographer->total_missions }} missions</span>
        </div>

        <!-- Price & CTA -->
        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <div>
                <span class="text-xs text-gray-500">À partir de</span>
                <p class="text-base font-bold text-gray-900">{{ number_format($photographer->getRawOriginal('hourly_rate'), 0, ',', ' ') }}€<span class="text-xs font-normal text-gray-500">/h</span></p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('photographers.show', $photographer) }}"
                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">
                    Profil
                </a>
                @auth
                    @if(auth()->user()->isClient())
                        <button type="button"
                                x-data
                                @click="$dispatch('open-booking-modal', { photographerId: {{ $photographer->id }}, photographerName: '{{ $photographer->user->name }}', photographerRate: {{ $photographer->getRawOriginal('hourly_rate') }} })"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-lg text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            Contacter
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-lg text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Contacter
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endif
