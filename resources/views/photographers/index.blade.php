<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Photographes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <form action="{{ route('photographers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Specialty Filter -->
                    <div>
                        <label for="specialty" class="block text-sm font-medium text-gray-700">Spécialité</label>
                        <select id="specialty" name="specialty" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Toutes les spécialités</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" {{ request('specialty') == $specialty->id ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Min Rate -->
                    <div>
                        <label for="min_rate" class="block text-sm font-medium text-gray-700">Tarif min (€/h)</label>
                        <input type="number" id="min_rate" name="min_rate" min="0" step="10"
                            value="{{ request('min_rate') }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="0">
                    </div>

                    <!-- Max Rate -->
                    <div>
                        <label for="max_rate" class="block text-sm font-medium text-gray-700">Tarif max (€/h)</label>
                        <input type="number" id="max_rate" name="max_rate" min="0" step="10"
                            value="{{ request('max_rate') }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="200">
                    </div>

                    <!-- Submit -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">
                            Filtrer
                        </button>
                        @if(request()->hasAny(['specialty', 'min_rate', 'max_rate', 'location']))
                            <a href="{{ route('photographers.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Results Count -->
            <div class="mb-4 text-sm text-gray-600">
                {{ $photographers->total() }} photographe(s) trouvé(s)
            </div>

            <!-- Photographers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($photographers as $photographer)
                    <x-photographer-card :photographer="$photographer" />
                @empty
                    <div class="col-span-3 bg-white rounded-xl shadow-sm p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun photographe trouvé</h3>
                        <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos critères de recherche.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($photographers->hasPages())
                <div class="mt-6">
                    {{ $photographers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
