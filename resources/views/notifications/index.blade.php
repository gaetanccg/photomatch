<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if($unreadCount > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-700">
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-6 flex gap-4">
                <a href="{{ route('notifications.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('filter') ? 'bg-indigo-100 text-indigo-700' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    Toutes ({{ auth()->user()->notifications()->count() }})
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium {{ request('filter') === 'unread' ? 'bg-indigo-100 text-indigo-700' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                    Non lues ({{ $unreadCount }})
                </a>
            </div>

            <!-- Notifications List -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                @if($notifications->count() > 0)
                    <ul class="divide-y divide-gray-100">
                        @foreach($notifications as $notification)
                            <li class="relative {{ !$notification->read_at ? 'bg-indigo-50' : '' }}">
                                <div class="flex items-start gap-4 p-4">
                                    <!-- Icon based on type -->
                                    <div class="flex-shrink-0">
                                        @php
                                            $type = $notification->data['type'] ?? 'default';
                                            $iconClass = match($type) {
                                                'booking_request_received' => 'bg-blue-100 text-blue-600',
                                                'booking_request_accepted' => 'bg-green-100 text-green-600',
                                                'booking_request_declined' => 'bg-red-100 text-red-600',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                        @endphp
                                        <div class="h-10 w-10 rounded-full {{ $iconClass }} flex items-center justify-center">
                                            @if($type === 'booking_request_received')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                            @elseif($type === 'booking_request_accepted')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @elseif($type === 'booking_request_declined')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900">
                                            {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>

                                        @if(!$notification->read_at)
                                            <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                Nouveau
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-2">
                                        @if(!$notification->read_at)
                                            <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-700">
                                                    Voir
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST"
                                              onsubmit="return confirm('Supprimer cette notification ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Pagination -->
                    @if($notifications->hasPages())
                        <div class="px-4 py-3 border-t border-gray-100">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune notification</h3>
                        <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore reçu de notifications.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
