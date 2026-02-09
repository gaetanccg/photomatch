<?php

namespace App\Http\Controllers;

use App\Actions\Booking\CancelBookingRequestAction;
use App\Actions\Booking\CreateBookingRequestAction;
use App\Actions\Booking\RespondToBookingRequestAction;
use App\Http\Requests\StoreBookingRequestRequest;
use App\Http\Requests\UpdateBookingRequestRequest;
use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\PhotoProject;
use App\Services\PhotographerStatisticsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookingRequestController extends Controller
{
    public function __construct(
        private CreateBookingRequestAction $createAction,
        private RespondToBookingRequestAction $respondAction,
        private CancelBookingRequestAction $cancelAction,
        private PhotographerStatisticsService $statisticsService
    ) {}

    public function store(StoreBookingRequestRequest $request): RedirectResponse
    {
        $project = PhotoProject::findOrFail($request->project_id);
        $photographer = Photographer::findOrFail($request->photographer_id);

        if ($project->client_id !== auth()->id()) {
            abort(403, 'Ce projet ne vous appartient pas.');
        }

        $errors = $this->createAction->canCreate($project, $photographer);
        if (! empty($errors)) {
            return back()->with('error', $errors[0]);
        }

        $this->createAction->execute(
            $project,
            $photographer,
            $request->message,
            $request->proposed_rate
        );

        return redirect()
            ->route('client.projects.show', $project)
            ->with('success', 'Votre demande a été envoyée au photographe.');
    }

    public function index(): View
    {
        $photographer = auth()->user()->photographer;

        $query = $photographer->bookingRequests()
            ->with(['project.client']);

        if (request('status') && in_array(request('status'), ['pending', 'accepted', 'declined', 'cancelled'])) {
            $query->where('status', request('status'));
        }

        $requests = $query->latest('sent_at')->paginate(10);

        return view('photographer.requests.index', compact('requests'));
    }

    public function history(): View
    {
        $photographer = auth()->user()->photographer;

        $query = $photographer->bookingRequests()
            ->with(['project.client', 'review'])
            ->where('status', 'accepted');

        if (request('from')) {
            $query->whereDate('responded_at', '>=', request('from'));
        }
        if (request('to')) {
            $query->whereDate('responded_at', '<=', request('to'));
        }
        if (request('year')) {
            $query->whereYear('responded_at', request('year'));
        }

        $missions = $query->latest('responded_at')->paginate(15);

        $stats = $this->statisticsService->getHistoryStats($photographer);
        $years = $this->statisticsService->getAvailableYears($photographer);

        return view('photographer.history.index', [
            'missions' => $missions,
            'totalMissions' => $stats['total_missions'],
            'totalEarnings' => $stats['total_earnings'],
            'avgRating' => $stats['avg_rating'],
            'reviewCount' => $stats['review_count'],
            'years' => $years,
        ]);
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

        if (! $this->respondAction->canRespond($bookingRequest)) {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $this->respondAction->execute(
            $bookingRequest,
            $request->status,
            $request->photographer_response,
            $request->filled('proposed_price') ? $request->proposed_price : null
        );

        $message = $request->status === 'accepted'
            ? 'Demande acceptée avec succès.'
            : 'Demande déclinée.';

        return redirect()->route('photographer.requests.index')->with('success', $message);
    }

    public function destroy(BookingRequest $bookingRequest): RedirectResponse
    {
        Gate::authorize('delete', $bookingRequest);

        $user = auth()->user();
        $redirectRoute = $this->cancelAction->getRedirectRoute($user);

        $this->cancelAction->execute($bookingRequest, $user);

        return redirect()->route($redirectRoute)->with('success', 'La demande a été supprimée avec succès.');
    }
}
