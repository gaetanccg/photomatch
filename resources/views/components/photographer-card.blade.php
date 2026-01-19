@props(['photographer'])

@if($photographer->user)
<div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
    <!-- Image/Avatar -->
    <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
        @if($photographer->user->avatar)
            <img src="{{ $photographer->user->avatar }}" alt="{{ $photographer->user->name }}" class="h-full w-full object-cover">
        @else
            <span class="text-white font-bold text-5xl">
                {{ substr($photographer->user->name, 0, 1) }}
            </span>
        @endif
    </div>

    <!-- Content -->
    <div class="p-5">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-gray-900">{{ $photographer->user->name }}</h3>
            @if($photographer->is_verified)
                <span class="inline-flex items-center text-green-600" title="Profil vérifié">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </span>
            @endif
        </div>

        <!-- Rating -->
        @if($photographer->rating)
            <div class="mb-3">
                <x-rating-stars :rating="$photographer->getRawOriginal('rating')" />
            </div>
        @endif

        <!-- Specialties -->
        <div class="flex flex-wrap gap-1 mb-4">
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
        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
            <span>{{ $photographer->experience_years }} ans exp.</span>
            <span>{{ $photographer->total_missions }} missions</span>
        </div>

        <!-- Price & CTA -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <div>
                <span class="text-sm text-gray-500">À partir de</span>
                <p class="text-lg font-bold text-gray-900">{{ number_format($photographer->getRawOriginal('hourly_rate'), 0, ',', ' ') }}€<span class="text-sm font-normal text-gray-500">/h</span></p>
            </div>
            <a href="{{ route('photographers.show', $photographer) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                Voir profil
            </a>
        </div>
    </div>
</div>
@endif
