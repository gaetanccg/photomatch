<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\PhotoProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();
        $userId = $user->id;

        // Cache stats for 5 minutes
        $stats = Cache::remember("client_stats:{$userId}", 300, function () use ($user, $userId) {
            return [
                'total_projects' => $user->photoProjects()->count(),
                'published_projects' => $user->photoProjects()->where('status', 'published')->count(),
                'pending_requests' => BookingRequest::whereHas('project', fn($q) => $q->where('client_id', $userId))
                    ->where('status', 'pending')->count(),
                'accepted_requests' => BookingRequest::whereHas('project', fn($q) => $q->where('client_id', $userId))
                    ->where('status', 'accepted')->count(),
            ];
        });

        $recentProjects = $user->photoProjects()
            ->withCount('bookingRequests')
            ->latest()
            ->take(5)
            ->get();

        $recentRequests = BookingRequest::whereHas('project', function ($query) use ($user) {
            $query->where('client_id', $user->id);
        })
            ->with(['photographer.user', 'project'])
            ->latest()
            ->take(5)
            ->get();

        return view('client.dashboard', compact('stats', 'recentProjects', 'recentRequests'));
    }

    public function requests(): View
    {
        $user = auth()->user();

        $requests = BookingRequest::whereHas('project', function ($query) use ($user) {
            $query->where('client_id', $user->id);
        })
            ->with(['photographer.user', 'project'])
            ->latest()
            ->paginate(15);

        return view('client.requests.index', compact('requests'));
    }

    public function showRequest(BookingRequest $bookingRequest): View
    {
        // Verify ownership
        if ($bookingRequest->project->client_id !== auth()->id()) {
            abort(403);
        }

        $bookingRequest->load(['photographer.user', 'photographer.specialties', 'project']);

        return view('client.requests.show', compact('bookingRequest'));
    }

    public function history(): View
    {
        $user = auth()->user();

        // Get completed missions (accepted requests)
        $query = BookingRequest::whereHas('project', function ($q) use ($user) {
                $q->where('client_id', $user->id);
            })
            ->with(['photographer.user', 'photographer.specialties', 'project', 'review'])
            ->where('status', 'accepted');

        // Year filter
        if (request('year')) {
            $query->whereYear('responded_at', request('year'));
        }

        $missions = $query->latest('responded_at')->paginate(15);

        // Statistics
        $totalMissions = BookingRequest::whereHas('project', fn($q) => $q->where('client_id', $user->id))
            ->where('status', 'accepted')->count();
        $totalSpent = BookingRequest::whereHas('project', fn($q) => $q->where('client_id', $user->id))
            ->where('status', 'accepted')
            ->whereNotNull('proposed_price')
            ->sum('proposed_price');
        $reviewsGiven = \App\Models\Review::where('client_id', $user->id)->count();

        // Get available years for filter
        $years = BookingRequest::whereHas('project', fn($q) => $q->where('client_id', $user->id))
            ->where('status', 'accepted')
            ->whereNotNull('responded_at')
            ->selectRaw('YEAR(responded_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('client.history.index', compact(
            'missions',
            'totalMissions',
            'totalSpent',
            'reviewsGiven',
            'years'
        ));
    }
}
