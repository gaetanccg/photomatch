<?php

namespace App\Http\Controllers;

use App\Events\BookingRequestCreated;
use App\Events\BookingRequestResponded;
use App\Http\Requests\StoreBookingRequestRequest;
use App\Http\Requests\UpdateBookingRequestRequest;
use App\Models\BookingRequest;
use App\Models\PhotoProject;
use App\Models\Photographer;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookingRequestController extends Controller
{
    /**
     * Store a new booking request (client creating a request to a photographer).
     */
    public function store(StoreBookingRequestRequest $request): RedirectResponse
    {
        $project = PhotoProject::findOrFail($request->project_id);
        $photographer = Photographer::findOrFail($request->photographer_id);

        // Check that the project belongs to the authenticated user
        if ($project->client_id !== auth()->id()) {
            abort(403, 'Ce projet ne vous appartient pas.');
        }

        // Check that the project is published
        if ($project->status !== 'published') {
            return back()->with('error', 'Le projet doit être publié pour envoyer une demande.');
        }

        // Check if a request already exists for this project-photographer combination
        $existingRequest = BookingRequest::where('project_id', $project->id)
            ->where('photographer_id', $photographer->id)
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Une demande a déjà été envoyée à ce photographe pour ce projet.');
        }

        $bookingRequest = BookingRequest::create([
            'project_id' => $project->id,
            'photographer_id' => $photographer->id,
            'status' => 'pending',
            'client_message' => $request->message,
            'proposed_price' => $request->proposed_rate,
            'sent_at' => Carbon::now(),
        ]);

        // Dispatch event for notifications
        event(new BookingRequestCreated($bookingRequest));

        return redirect()
            ->route('client.projects.show', $project)
            ->with('success', 'Votre demande a été envoyée au photographe.');
    }

    public function index(): View
    {
        $photographer = auth()->user()->photographer;

        $query = $photographer->bookingRequests()
            ->with(['project.client']);

        // Apply status filter
        if (request('status') && in_array(request('status'), ['pending', 'accepted', 'declined', 'cancelled'])) {
            $query->where('status', request('status'));
        }

        $requests = $query->latest('sent_at')->paginate(10);

        return view('photographer.requests.index', compact('requests'));
    }

    public function history(): View
    {
        $photographer = auth()->user()->photographer;

        // Get completed missions (accepted requests)
        $query = $photographer->bookingRequests()
            ->with(['project.client', 'review'])
            ->where('status', 'accepted');

        // Date range filter
        if (request('from')) {
            $query->whereDate('responded_at', '>=', request('from'));
        }
        if (request('to')) {
            $query->whereDate('responded_at', '<=', request('to'));
        }

        // Year filter
        if (request('year')) {
            $query->whereYear('responded_at', request('year'));
        }

        $missions = $query->latest('responded_at')->paginate(15);

        // Statistics
        $totalMissions = $photographer->bookingRequests()->where('status', 'accepted')->count();
        $totalEarnings = $photographer->bookingRequests()
            ->where('status', 'accepted')
            ->whereNotNull('proposed_price')
            ->sum('proposed_price');
        $avgRating = $photographer->reviews()->avg('rating');
        $reviewCount = $photographer->reviews()->count();

        // Get available years for filter
        $years = $photographer->bookingRequests()
            ->where('status', 'accepted')
            ->whereNotNull('responded_at')
            ->selectRaw('YEAR(responded_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('photographer.history.index', compact(
            'missions',
            'totalMissions',
            'totalEarnings',
            'avgRating',
            'reviewCount',
            'years'
        ));
    }

    public function show(BookingRequest $bookingRequest): View
    {
        Gate::authorize('view', $bookingRequest);

        $bookingRequest->load(['project.client', 'photographer.user']);

        return view('photographer.requests.show', compact('bookingRequest'));
    }

    public function update(UpdateBookingRequestRequest $request, BookingRequest $bookingRequest): RedirectResponse
    {
        Gate::authorize('update', $bookingRequest);

        // Verify the request is still pending
        if ($bookingRequest->status !== 'pending') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $data = [
            'status' => $request->status,
            'photographer_response' => $request->photographer_response,
            'responded_at' => Carbon::now(),
        ];

        if ($request->status === 'accepted' && $request->filled('proposed_price')) {
            $data['proposed_price'] = $request->proposed_price;
        }

        $bookingRequest->update($data);

        // Dispatch event for notifications
        event(new BookingRequestResponded($bookingRequest));

        $message = $request->status === 'accepted'
            ? 'Demande acceptée avec succès.'
            : 'Demande déclinée.';

        return redirect()->route('photographer.requests.index')->with('success', $message);
    }
}
