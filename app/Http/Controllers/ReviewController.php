<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewResponseRequest;
use App\Models\BookingRequest;
use App\Models\Review;
use App\Services\PhotographerStatisticsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __construct(
        private PhotographerStatisticsService $statisticsService
    ) {}

    public function create(BookingRequest $bookingRequest): View|RedirectResponse
    {
        if ($bookingRequest->project->client_id !== auth()->id()) {
            abort(403);
        }

        if (! $bookingRequest->canBeReviewed()) {
            return redirect()
                ->route('client.requests.show', $bookingRequest)
                ->with('error', 'Cette mission ne peut pas être évaluée.');
        }

        $bookingRequest->load(['photographer.user', 'project']);

        return view('client.reviews.create', compact('bookingRequest'));
    }

    public function store(StoreReviewRequest $request, BookingRequest $bookingRequest): RedirectResponse
    {
        if ($bookingRequest->project->client_id !== auth()->id()) {
            abort(403);
        }

        if (! $bookingRequest->canBeReviewed()) {
            return redirect()
                ->route('client.requests.show', $bookingRequest)
                ->with('error', 'Cette mission ne peut pas être évaluée.');
        }

        Review::create([
            'booking_request_id' => $bookingRequest->id,
            'client_id' => auth()->id(),
            'photographer_id' => $bookingRequest->photographer_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Note: Photographer rating is automatically updated via ReviewObserver

        return redirect()
            ->route('client.requests.show', $bookingRequest)
            ->with('success', 'Votre avis a été publié avec succès.');
    }

    public function showResponse(Review $review): View
    {
        if ($review->photographer_id !== auth()->user()->photographer?->id) {
            abort(403);
        }

        $review->load(['client', 'bookingRequest.project']);

        return view('photographer.reviews.respond', compact('review'));
    }

    public function storeResponse(UpdateReviewResponseRequest $request, Review $review): RedirectResponse
    {
        if ($review->photographer_id !== auth()->user()->photographer?->id) {
            abort(403);
        }

        $review->update([
            'photographer_response' => $request->photographer_response,
            'responded_at' => now(),
        ]);

        return redirect()
            ->route('photographer.reviews.index')
            ->with('success', 'Votre réponse a été publiée avec succès.');
    }

    public function photographerIndex(): View
    {
        $photographer = auth()->user()->photographer;

        $reviews = $photographer->reviews()
            ->with(['client', 'bookingRequest.project'])
            ->latest()
            ->paginate(10);

        $stats = $this->statisticsService->getReviewStats($photographer);

        return view('photographer.reviews.index', compact('reviews', 'stats'));
    }
}
