@php
    $metaTitle = $photographer->user->name . ' - Photographe ' . ($photographer->location ? 'a ' . $photographer->location : '') . ' | Trouve Ton Photographe';
    $metaDescription = Str::limit($photographer->bio ?? 'Photographe professionnel specialise en ' . $photographer->specialties->pluck('name')->join(', '), 160);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $photographer->user->name }}
            </h2>
            <a href="{{ route('photographers.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                &larr; Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Profile Header -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-start gap-6">
                            <!-- Avatar -->
                            <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                @if($photographer->user->avatar)
                                    <img src="{{ $photographer->user->avatar }}" alt="{{ $photographer->user->name }}" class="h-24 w-24 rounded-full object-cover">
                                @else
                                    <span class="text-indigo-600 font-bold text-3xl">
                                        {{ substr($photographer->user->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h1 class="text-2xl font-bold text-gray-900">{{ $photographer->user->name }}</h1>
                                    @if($photographer->is_verified)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Vérifié
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-2 flex items-center gap-4 text-sm text-gray-600">
                                    @if($photographer->rating)
                                        <x-rating-stars :rating="$photographer->getRawOriginal('rating')" />
                                    @endif
                                    <span>{{ $photographer->experience_years }} ans d'expérience</span>
                                    <span>{{ $photographer->total_missions }} missions</span>
                                </div>

                                <!-- Specialties -->
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($photographer->specialties as $specialty)
                                        <x-specialty-badge :specialty="$specialty" :level="$specialty->pivot->experience_level" />
                                    @endforeach
                                </div>

                                @if($photographer->tags->count() > 0)
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($photographer->tags as $tag)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">À propos</h2>
                        <p class="text-gray-700 whitespace-pre-line">{{ $photographer->bio ?? 'Aucune biographie disponible.' }}</p>

                        @if($photographer->portfolio_url)
                            <div class="mt-4">
                                <a href="{{ $photographer->portfolio_url }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Voir le portfolio externe
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Portfolio Gallery -->
                    @if($photographer->portfolioImages->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Portfolio</h2>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($photographer->portfolioImages->take(9) as $image)
                                    <div class="relative group cursor-pointer" onclick="openLightbox('{{ $image->url }}', '{{ $image->caption ?? '' }}')">
                                        <div class="aspect-square overflow-hidden rounded-lg bg-gray-100">
                                            <img src="{{ $image->thumbnail_url }}"
                                                 alt="{{ $image->caption ?? 'Photo portfolio' }}"
                                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                                 loading="lazy">
                                        </div>
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                            </svg>
                                        </div>
                                        @if($image->caption)
                                            <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/60 to-transparent rounded-b-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <p class="text-white text-xs truncate">{{ $image->caption }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if($photographer->portfolioImages->count() > 9)
                                <p class="mt-4 text-sm text-gray-500 text-center">
                                    + {{ $photographer->portfolioImages->count() - 9 }} autres photos
                                </p>
                            @endif
                        </div>

                        <!-- Lightbox Modal -->
                        <div id="lightbox" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center" onclick="closeLightbox()">
                            <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            <div class="max-w-4xl max-h-[90vh] p-4" onclick="event.stopPropagation()">
                                <img id="lightbox-image" src="" alt="" class="max-w-full max-h-[80vh] object-contain rounded-lg">
                                <p id="lightbox-caption" class="text-white text-center mt-4"></p>
                            </div>
                        </div>

                        @push('scripts')
                        <script>
                            function openLightbox(imageUrl, caption) {
                                const lightbox = document.getElementById('lightbox');
                                const lightboxImage = document.getElementById('lightbox-image');
                                const lightboxCaption = document.getElementById('lightbox-caption');

                                lightboxImage.src = imageUrl;
                                lightboxCaption.textContent = caption;
                                lightbox.classList.remove('hidden');
                                document.body.style.overflow = 'hidden';
                            }

                            function closeLightbox() {
                                const lightbox = document.getElementById('lightbox');
                                lightbox.classList.add('hidden');
                                document.body.style.overflow = '';
                            }

                            // Close lightbox on escape key
                            document.addEventListener('keydown', function(e) {
                                if (e.key === 'Escape') {
                                    closeLightbox();
                                }
                            });
                        </script>
                        @endpush
                    @endif

                    <!-- Availabilities Calendar -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Disponibilités (30 prochains jours)</h2>

                        <div class="grid grid-cols-7 gap-2">
                            @php
                                $today = now();
                                $daysOfWeek = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                            @endphp

                            @foreach($daysOfWeek as $day)
                                <div class="text-center text-xs font-medium text-gray-500 py-2">{{ $day }}</div>
                            @endforeach

                            @php
                                $startDay = $today->copy()->startOfWeek();
                            @endphp

                            @for($i = 0; $i < 35; $i++)
                                @php
                                    $date = $startDay->copy()->addDays($i);
                                    $isPast = $date->lt($today->startOfDay());
                                    $dateKey = $date->format('Y-m-d');
                                    $availability = $availabilities->get($dateKey);
                                @endphp

                                <div class="flex flex-col items-center justify-center p-2 rounded-lg
                                    {{ $isPast ? 'opacity-40' : '' }}
                                    @if($availability)
                                        {{ $availability->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}
                                    @else
                                        bg-gray-50 text-gray-600
                                    @endif">
                                    <span class="text-xs">{{ $date->format('M') }}</span>
                                    <span class="text-sm font-semibold {{ $date->isToday() ? 'ring-2 ring-indigo-500 rounded-full px-1' : '' }}">{{ $date->format('d') }}</span>
                                </div>
                            @endfor
                        </div>

                        <div class="mt-4 flex items-center gap-6 text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded bg-green-100"></div>
                                <span>Disponible</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded bg-red-100"></div>
                                <span>Indisponible</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded bg-gray-100"></div>
                                <span>Non renseigné</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Rates Card -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tarifs</h2>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-600">Tarif horaire</span>
                                <span class="text-xl font-bold text-gray-900">{{ number_format($photographer->getRawOriginal('hourly_rate'), 0, ',', ' ') }}€</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Tarif journalier</span>
                                <span class="text-xl font-bold text-gray-900">{{ number_format($photographer->getRawOriginal('daily_rate'), 0, ',', ' ') }}€</span>
                            </div>
                        </div>

                        @auth
                            @if(auth()->user()->isClient())
                                <div class="mt-6">
                                    @if($clientProjects && $clientProjects->count() > 0)
                                        <a href="#send-request" class="block w-full text-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                            Envoyer une demande
                                        </a>
                                    @else
                                        <p class="text-sm text-gray-500 text-center">
                                            Créez d'abord un projet pour envoyer une demande
                                        </p>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="mt-6">
                                <a href="{{ route('login') }}" class="block w-full text-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    Connectez-vous pour envoyer une demande
                                </a>
                            </div>
                        @endauth
                    </div>

                    <!-- Stats Card -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistiques</h2>

                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Missions réalisées</span>
                                <span class="font-medium text-gray-900">{{ $photographer->total_missions }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Années d'expérience</span>
                                <span class="font-medium text-gray-900">{{ $photographer->experience_years }}</span>
                            </div>
                            @if($photographer->rating)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Note moyenne</span>
                                    <span class="font-medium text-gray-900">{{ $photographer->rating }}/5</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Card -->
                    @if($photographer->user->phone)
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact</h2>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $photographer->user->phone }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
