<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\PhotoProject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();

        $stats = [
            'total_projects' => $user->photoProjects()->count(),
            'published_projects' => $user->photoProjects()->where('status', 'published')->count(),
            'pending_requests' => BookingRequest::whereHas('project', function ($query) use ($user) {
                $query->where('client_id', $user->id);
            })->where('status', 'pending')->count(),
            'accepted_requests' => BookingRequest::whereHas('project', function ($query) use ($user) {
                $query->where('client_id', $user->id);
            })->where('status', 'accepted')->count(),
        ];

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
}
