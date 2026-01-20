<x-photographer-layout>
    <div class="space-y-6" x-data="calendarApp()">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mes disponibilités</h1>
                <p class="mt-1 text-sm text-gray-600">Gérez votre calendrier et vos réservations</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Calendar -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm">
                <!-- Calendar Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <button @click="previousMonth()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h2 class="text-lg font-semibold text-gray-900" x-text="currentMonthName + ' ' + currentYear"></h2>
                    <button @click="nextMonth()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <!-- Calendar Grid -->
                <div class="p-6">
                    <!-- Days of Week -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <template x-for="day in daysOfWeek" :key="day">
                            <div class="text-center text-xs font-semibold text-gray-500 py-2" x-text="day"></div>
                        </template>
                    </div>

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7 gap-1">
                        <template x-for="(day, index) in calendarDays" :key="index">
                            <button
                                @click="day.date && selectDate(day)"
                                :disabled="!day.date || day.isPast"
                                :class="{
                                    'opacity-30 cursor-not-allowed': !day.date || day.isPast,
                                    'hover:bg-gray-100': day.date && !day.isPast,
                                    'ring-2 ring-emerald-500': selectedDate === day.dateStr,
                                    'bg-emerald-50 border-emerald-200': day.isAvailable === true,
                                    'bg-red-50 border-red-200': day.isAvailable === false,
                                    'bg-white': day.isAvailable === null && day.date
                                }"
                                class="aspect-square p-1 rounded-lg border border-gray-200 flex flex-col items-center justify-center transition relative">
                                <span
                                    :class="{
                                        'text-emerald-600 font-bold': day.isToday,
                                        'text-gray-400': !day.isCurrentMonth,
                                        'text-gray-900': day.isCurrentMonth && !day.isToday
                                    }"
                                    class="text-sm"
                                    x-text="day.day"></span>
                                <!-- Booking indicator -->
                                <template x-if="day.hasBooking">
                                    <span class="absolute bottom-1 w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                </template>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Legend -->
                <div class="px-6 pb-4 flex flex-wrap items-center gap-4 text-xs">
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded bg-emerald-100 border border-emerald-300"></div>
                        <span class="text-gray-600">Disponible</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded bg-red-100 border border-red-300"></div>
                        <span class="text-gray-600">Indisponible</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded bg-white border border-gray-300"></div>
                        <span class="text-gray-600">Non défini</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                        <span class="text-gray-600">Réservation</span>
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="space-y-6">
                <!-- Selected Date Details -->
                <div class="bg-white rounded-xl shadow-sm p-6" x-show="selectedDate" x-cloak>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4" x-text="selectedDateFormatted"></h3>

                    <!-- Current Status -->
                    <div class="mb-4 p-3 rounded-lg" :class="{
                        'bg-emerald-50': selectedDayData?.isAvailable === true,
                        'bg-red-50': selectedDayData?.isAvailable === false,
                        'bg-gray-50': selectedDayData?.isAvailable === null
                    }">
                        <p class="text-sm font-medium" :class="{
                            'text-emerald-700': selectedDayData?.isAvailable === true,
                            'text-red-700': selectedDayData?.isAvailable === false,
                            'text-gray-700': selectedDayData?.isAvailable === null
                        }">
                            <span x-show="selectedDayData?.isAvailable === true">✓ Disponible</span>
                            <span x-show="selectedDayData?.isAvailable === false">✗ Indisponible</span>
                            <span x-show="selectedDayData?.isAvailable === null">Non défini</span>
                        </p>
                        <p x-show="selectedDayData?.note" class="text-xs text-gray-500 mt-1" x-text="selectedDayData?.note"></p>
                    </div>

                    <!-- Booking Info -->
                    <template x-if="selectedDayData?.booking">
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm font-medium text-blue-700">Réservation</p>
                            <p class="text-xs text-blue-600 mt-1" x-text="selectedDayData.booking.client"></p>
                            <p class="text-xs text-blue-500" x-text="selectedDayData.booking.project"></p>
                            <a :href="selectedDayData.booking.url" class="inline-block mt-2 text-xs text-blue-600 hover:underline">
                                Voir les détails →
                            </a>
                        </div>
                    </template>

                    <!-- Quick Actions -->
                    <div class="space-y-2">
                        <form :action="'{{ route('photographer.availabilities.store') }}'" method="POST">
                            @csrf
                            <input type="hidden" name="date" :value="selectedDate">
                            <input type="hidden" name="is_available" value="1">
                            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-emerald-700 bg-emerald-100 rounded-lg hover:bg-emerald-200 transition">
                                Marquer disponible
                            </button>
                        </form>
                        <form :action="'{{ route('photographer.availabilities.store') }}'" method="POST">
                            @csrf
                            <input type="hidden" name="date" :value="selectedDate">
                            <input type="hidden" name="is_available" value="0">
                            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition">
                                Marquer indisponible
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Quick Add with Note -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ajouter avec note</h3>
                    <form action="{{ route('photographer.availabilities.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" id="date" name="date"
                                min="{{ now()->format('Y-m-d') }}"
                                max="{{ now()->addDays(90)->format('Y-m-d') }}"
                                :value="selectedDate"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                            @error('date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_available" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select id="is_available" name="is_available" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                <option value="1">Disponible</option>
                                <option value="0">Indisponible</option>
                            </select>
                        </div>

                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                            <input type="text" id="note" name="note" maxlength="255"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm"
                                placeholder="Ex: Disponible matin uniquement">
                        </div>

                        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                            Enregistrer
                        </button>
                    </form>
                </div>

                <!-- Stats -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ce mois-ci</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Jours disponibles</span>
                            <span class="text-sm font-semibold text-emerald-600" x-text="monthStats.available"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Jours indisponibles</span>
                            <span class="text-sm font-semibold text-red-600" x-text="monthStats.unavailable"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Réservations</span>
                            <span class="text-sm font-semibold text-blue-600" x-text="monthStats.bookings"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Update Section -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between cursor-pointer" @click="showBulkUpdate = !showBulkUpdate">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Mise à jour groupée</h2>
                    <p class="text-sm text-gray-500">Marquez plusieurs dates en une seule fois</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': showBulkUpdate }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div x-show="showBulkUpdate" x-collapse>
                <form action="{{ route('photographer.availabilities.bulk') }}" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sélectionnez les dates</label>
                            <div class="grid grid-cols-7 sm:grid-cols-14 gap-1" id="bulk-calendar">
                                @php
                                    $today = now();
                                    $daysOfWeek = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
                                @endphp

                                @foreach($daysOfWeek as $day)
                                    <div class="text-center text-xs font-medium text-gray-400 py-1">{{ $day }}</div>
                                @endforeach
                                @foreach($daysOfWeek as $day)
                                    <div class="text-center text-xs font-medium text-gray-400 py-1 hidden sm:block">{{ $day }}</div>
                                @endforeach

                                @php
                                    $startDay = $today->copy()->startOfWeek();
                                @endphp

                                @for($i = 0; $i < 28; $i++)
                                    @php
                                        $date = $startDay->copy()->addDays($i);
                                        $isPast = $date->lt($today->startOfDay());
                                        $existingAvailability = $photographer->availabilities->firstWhere('date', $date->format('Y-m-d'));
                                    @endphp

                                    <label class="relative {{ $isPast ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer' }}">
                                        <input type="checkbox"
                                            name="dates[]"
                                            value="{{ $date->format('Y-m-d') }}"
                                            class="peer sr-only"
                                            {{ $isPast ? 'disabled' : '' }}>
                                        <div class="flex flex-col items-center justify-center p-1 sm:p-2 rounded-lg border border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-gray-50 transition-colors text-xs
                                            @if($existingAvailability)
                                                {{ $existingAvailability->is_available ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' }}
                                            @endif">
                                            <span class="text-gray-400">{{ $date->format('d') }}</span>
                                            <span class="text-gray-600 font-medium">{{ $date->translatedFormat('M') }}</span>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <div class="flex-1">
                                <select name="is_available" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                    <option value="1">Marquer comme disponible</option>
                                    <option value="0">Marquer comme indisponible</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <input type="text" name="note" maxlength="255"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm"
                                    placeholder="Note (optionnel)">
                            </div>
                            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                                Appliquer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Upcoming List -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Prochaines disponibilités</h2>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($availabilities->take(20) as $availability)
                    <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full {{ $availability->is_available ? 'bg-emerald-500' : 'bg-red-500' }}"></div>
                            <div>
                                <span class="text-sm font-medium text-gray-900">{{ $availability->date->translatedFormat('l d F') }}</span>
                                @if($availability->note)
                                    <span class="text-xs text-gray-500 ml-2">- {{ $availability->note }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('photographer.availabilities.update', $availability) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_available" value="{{ $availability->is_available ? '0' : '1' }}">
                                <button type="submit" class="text-xs text-gray-500 hover:text-gray-700">
                                    {{ $availability->is_available ? 'Indispo' : 'Dispo' }}
                                </button>
                            </form>
                            <form action="{{ route('photographer.availabilities.destroy', $availability) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700" onclick="return confirm('Supprimer ?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm">Aucune disponibilité enregistrée</p>
                        <p class="text-xs text-gray-400 mt-1">Cliquez sur une date du calendrier pour commencer</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function calendarApp() {
            const availabilities = @json($availabilitiesData ?? []);
            const bookings = @json($bookingsData ?? []);
            const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

            return {
                currentMonth: new Date().getMonth(),
                currentYear: new Date().getFullYear(),
                selectedDate: null,
                selectedDayData: null,
                showBulkUpdate: false,
                daysOfWeek: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],

                get currentMonthName() {
                    return monthNames[this.currentMonth];
                },

                get calendarDays() {
                    const days = [];
                    const firstDay = new Date(this.currentYear, this.currentMonth, 1);
                    const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    // Get the day of week (0 = Sunday, adjust to Monday = 0)
                    let startDayOfWeek = firstDay.getDay() - 1;
                    if (startDayOfWeek < 0) startDayOfWeek = 6;

                    // Add empty days for previous month
                    for (let i = 0; i < startDayOfWeek; i++) {
                        days.push({ date: null, day: '', isCurrentMonth: false });
                    }

                    // Add days of current month
                    for (let d = 1; d <= lastDay.getDate(); d++) {
                        const date = new Date(this.currentYear, this.currentMonth, d);
                        const dateStr = this.formatDate(date);
                        const availability = availabilities[dateStr];
                        const booking = bookings[dateStr];

                        days.push({
                            date: date,
                            day: d,
                            dateStr: dateStr,
                            isCurrentMonth: true,
                            isToday: date.getTime() === today.getTime(),
                            isPast: date < today,
                            isAvailable: availability !== undefined ? availability.is_available : null,
                            hasBooking: !!booking,
                            note: availability?.note || null,
                            booking: booking || null
                        });
                    }

                    return days;
                },

                get selectedDateFormatted() {
                    if (!this.selectedDate) return '';
                    const date = new Date(this.selectedDate);
                    return date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                },

                get monthStats() {
                    let available = 0, unavailable = 0, bookingsCount = 0;
                    this.calendarDays.forEach(day => {
                        if (day.isCurrentMonth && !day.isPast) {
                            if (day.isAvailable === true) available++;
                            if (day.isAvailable === false) unavailable++;
                            if (day.hasBooking) bookingsCount++;
                        }
                    });
                    return { available, unavailable, bookings: bookingsCount };
                },

                formatDate(date) {
                    return date.getFullYear() + '-' +
                        String(date.getMonth() + 1).padStart(2, '0') + '-' +
                        String(date.getDate()).padStart(2, '0');
                },

                previousMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                },

                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                },

                selectDate(day) {
                    this.selectedDate = day.dateStr;
                    this.selectedDayData = day;
                }
            };
        }
    </script>
    @endpush
</x-photographer-layout>
