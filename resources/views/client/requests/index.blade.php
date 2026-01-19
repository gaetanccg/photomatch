<x-client-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes demandes</h1>
            <p class="mt-1 text-sm text-gray-600">Suivez l'état de vos demandes aux photographes</p>
        </div>

        <!-- Requests Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($requests->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Photographe
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Projet
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date d'envoi
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Prix proposé
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requests as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            @if($request->photographer->user->avatar ?? false)
                                                <img src="{{ $request->photographer->user->avatar }}" alt="" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <span class="text-indigo-600 font-medium">
                                                    {{ substr($request->photographer->user->name ?? 'P', 0, 1) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $request->photographer->user->name ?? 'Photographe' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $request->project->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $request->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($request->proposed_price)
                                        {{ number_format($request->proposed_price, 0, ',', ' ') }}€
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$request->status" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('client.requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Voir détail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($requests->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $requests->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune demande</h3>
                    <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore envoyé de demande à un photographe.</p>
                    <div class="mt-6">
                        <a href="{{ route('search.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher des photographes
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-client-layout>
