<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Queries\ClientBookingRequestsQuery;
use App\Services\ClientStatisticsService;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct(
        private ClientStatisticsService $statisticsService
    ) {}

    public function dashboard(): View
    {
        $user = auth()->user();

        $stats = $this->statisticsService->getDashboardStats($user);

        $recentProjects = $user->photoProjects()
            ->withCount('bookingRequests')
            ->latest()
            ->take(5)
            ->get();

        $query = new ClientBookingRequestsQuery($user);
        $recentRequests = $query->withRelations()
            ->latest()
            ->take(5)
            ->get();

        return view('client.dashboard', compact('stats', 'recentProjects', 'recentRequests'));
    }

    public function requests(): View
    {
        $user = auth()->user();

        $query = new ClientBookingRequestsQuery($user);
        $requests = $query->withRelations()
            ->latest()
            ->paginate(15);

        return view('client.requests.index', compact('requests'));
    }

    public function showRequest(BookingRequest $bookingRequest): View
    {
        if ($bookingRequest->project->client_id !== auth()->id()) {
            abort(403);
        }

        $bookingRequest->load(['photographer.user', 'photographer.specialties', 'project']);

        return view('client.requests.show', compact('bookingRequest'));
    }

    public function history(): View
    {
        $user = auth()->user();
        $query = new ClientBookingRequestsQuery($user);

        $missions = $query->forYear(request('year'))
            ->with(['photographer.user', 'photographer.specialties', 'project', 'review'])
            ->latest('responded_at')
            ->paginate(15);

        $historyStats = $this->statisticsService->getHistoryStats($user);
        $years = $query->availableYears();

        return view('client.history.index', [
            'missions' => $missions,
            'totalMissions' => $historyStats['total_missions'],
            'totalSpent' => $historyStats['total_spent'],
            'reviewsGiven' => $historyStats['reviews_given'],
            'years' => $years,
        ]);
    }
}
