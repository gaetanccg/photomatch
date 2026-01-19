<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $query = $user->notifications();

        // Filter by read status
        if ($request->get('filter') === 'unread') {
            $query = $user->unreadNotifications();
        }

        $notifications = $query->paginate(15);

        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(DatabaseNotification $notification): RedirectResponse
    {
        // Verify ownership
        if ($notification->notifiable_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        // Redirect based on notification type
        $data = $notification->data;
        $redirectUrl = $this->getRedirectUrl($data);

        if ($redirectUrl) {
            return redirect($redirectUrl);
        }

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function destroy(DatabaseNotification $notification): RedirectResponse
    {
        // Verify ownership
        if ($notification->notifiable_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notification supprimée.');
    }

    private function getRedirectUrl(array $data): ?string
    {
        $type = $data['type'] ?? null;

        return match ($type) {
            'booking_request_received' => route('photographer.requests.show', $data['booking_request_id']),
            'booking_request_accepted', 'booking_request_declined' => route('client.requests.show', $data['booking_request_id']),
            default => null,
        };
    }
}
