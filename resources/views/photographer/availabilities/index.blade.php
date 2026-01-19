<x-photographer-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes disponibilités</h1>
            <p class="mt-1 text-sm text-gray-600">Gérez votre calendrier pour les 60 prochains jours</p>
        </div>

        <!-- Quick Add Form -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Ajouter une disponibilité</h2>
            </div>
            <form action="{{ route('photographer.availabilities.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" id="date" name="date"
                            min="{{ now()->format('Y-m-d') }}"
                            max="{{ now()->addDays(60)->format('Y-m-d') }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="is_available" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select id="is_available" name="is_available" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="1">Disponible</option>
                            <option value="0">Indisponible</option>
                        </select>
                    </div>

                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-700">Note (optionnel)</label>
                        <input type="text" id="note" name="note" maxlength="255"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Ex: Disponible matin uniquement">
                    </div>

                    <div>
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bulk Update Form -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Mise à jour groupée</h2>
                <p class="mt-1 text-sm text-gray-500">Marquez plusieurs dates en une seule fois</p>
            </div>
            <form action="{{ route('photographer.availabilities.bulk') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sélectionnez les dates</label>
                        <div class="grid grid-cols-7 gap-2" id="bulk-calendar">
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

                            @for($i = 0; $i < 63; $i++)
                                @php
                                    $date = $startDay->copy()->addDays($i);
                                    $isPast = $date->lt($today->startOfDay());
                                    $existingAvailability = $photographer->availabilities->firstWhere('date', $date->format('Y-m-d'));
                                @endphp

                                <label class="relative {{ $isPast ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}">
                                    <input type="checkbox"
                                        name="dates[]"
                                        value="{{ $date->format('Y-m-d') }}"
                                        class="peer sr-only"
                                        {{ $isPast ? 'disabled' : '' }}>
                                    <div class="flex flex-col items-center justify-center p-2 rounded-lg border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:bg-gray-50 transition-colors
                                        @if($existingAvailability)
                                            {{ $existingAvailability->is_available ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}
                                        @endif">
                                        <span class="text-xs text-gray-500">{{ $date->format('M') }}</span>
                                        <span class="text-sm font-semibold {{ $date->isToday() ? 'text-indigo-600' : 'text-gray-900' }}">{{ $date->format('d') }}</span>
                                    </div>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <select name="is_available" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="1">Marquer comme disponible</option>
                                <option value="0">Marquer comme indisponible</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <input type="text" name="note" maxlength="255"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Note (optionnel)">
                        </div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Appliquer
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Legend -->
        <div class="flex items-center gap-6 text-sm">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-green-100 border border-green-300"></div>
                <span class="text-gray-600">Disponible</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-red-100 border border-red-300"></div>
                <span class="text-gray-600">Indisponible</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-gray-100 border border-gray-300"></div>
                <span class="text-gray-600">Non défini</span>
            </div>
        </div>

        <!-- Existing Availabilities List -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Disponibilités enregistrées</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($availabilities as $month => $monthAvailabilities)
                    <div class="px-6 py-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</h3>
                        <div class="space-y-2">
                            @foreach($monthAvailabilities as $availability)
                                <div class="flex items-center justify-between p-3 rounded-lg {{ $availability->is_available ? 'bg-green-50' : 'bg-red-50' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full {{ $availability->is_available ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        <span class="font-medium text-gray-900">{{ $availability->date->translatedFormat('l d F') }}</span>
                                        @if($availability->note)
                                            <span class="text-sm text-gray-500">- {{ $availability->note }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('photographer.availabilities.update', $availability) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="is_available" value="{{ $availability->is_available ? '0' : '1' }}">
                                            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-700">
                                                {{ $availability->is_available ? 'Marquer indisponible' : 'Marquer disponible' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('photographer.availabilities.destroy', $availability) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-700" onclick="return confirm('Supprimer cette disponibilité ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <p>Aucune disponibilité enregistrée</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-photographer-layout>
