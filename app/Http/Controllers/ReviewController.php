<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewResponseRequest;
use App\Models\BookingRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Show form to create a review for a booking request (Client)
     */
    public function create(BookingRequest $bookingRequest): View
    {
        // Verify ownership and eligibility
        if ($bookingRequest->project->client_id !== auth()->id()) {
            abort(403);
        }

        if (!$bookingRequest->canBeReviewed()) {
            return redirect()
                ->route('client.requests.show', $bookingRequest)
                ->with('error', 'Cette mission ne peut pas être évaluée.');
        }

        $bookingRequest->load(['photographer.user', 'project']);

        return view('client.reviews.create', compact('bookingRequest'));
    }

    /**
     * Store a new review (Client)
     */
    public function store(StoreReviewRequest $request, BookingRequest $bookingRequest): RedirectResponse
    {
        // Verify ownership and eligibility
        if ($bookingRequest->project->client_id !== auth()->id()) {
            abort(403);
        }

        if (!$bookingRequest->canBeReviewed()) {
            return redirect()
                ->route('client.requests.show', $bookingRequest)
                ->with('error', 'Cette mission ne peut pas être évaluée.');
        }

        $review = Review::create([
            'booking_request_id' => $bookingRequest->id,
            'client_id' => auth()->id(),
            'photographer_id' => $bookingRequest->photographer_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Update photographer's average rating
        $bookingRequest->photographer->updateRating();

        return redirect()
            ->route('client.requests.show', $bookingRequest)
            ->with('success', 'Votre avis a été publié avec succès.');
    }

    /**
     * Show form to respond to a review (Photographer)
     */
    public function showResponse(Review $review): View
    {
        // Verify ownership
        if ($review->photographer_id !== auth()->user()->photographer?->id) {
            abort(403);
        }

        $review->load(['client', 'bookingRequest.project']);

        return view('photographer.reviews.respond', compact('review'));
    }

    /**
     * Store photographer response to a review
     */
    public function storeResponse(UpdateReviewResponseRequest $request, Review $review): RedirectResponse
    {
        // Verify ownership
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

    /**
     * List all reviews for the photographer
     */
    public function photographerIndex(): View
    {
        $photographer = auth()->user()->photographer;

        $reviews = $photographer->reviews()
            ->with(['client', 'bookingRequest.project'])
            ->latest()
            ->paginate(10);

        $stats = [
            'total_reviews' => $photographer->reviews()->count(),
            'average_rating' => $photographer->reviews()->avg('rating'),
            'five_star' => $photographer->reviews()->where('rating', 5)->count(),
            'pending_responses' => $photographer->reviews()->whereNull('photographer_response')->count(),
        ];

        return view('photographer.reviews.index', compact('reviews', 'stats'));
    }
}
