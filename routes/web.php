<?php

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoProjectController;
use App\Http\Controllers\PhotographerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPhotographerController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirect to role-specific page
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'photographer' => redirect()->route('photographer.dashboard'),
        'client' => redirect()->route('search.index'), // Clients go to photographer search
        default => view('dashboard'), // Admin fallback
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Public routes
Route::get('/photographers', [PublicPhotographerController::class, 'index'])->name('photographers.index');
Route::get('/photographers/{photographer}', [PublicPhotographerController::class, 'show'])->name('photographers.show');
Route::get('/search-photographers', [SearchController::class, 'index'])->name('search.index');

// Client authenticated routes
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');

    // Projects - Resource routes
    Route::resource('projects', PhotoProjectController::class);

    // Booking requests
    Route::get('/requests', [ClientController::class, 'requests'])->name('requests.index');
    Route::get('/requests/{bookingRequest}', [ClientController::class, 'showRequest'])->name('requests.show');
});

// Booking request creation (accessible from photographer profile)
Route::middleware(['auth', 'role:client'])->post('/booking-requests', [BookingRequestController::class, 'store'])->name('booking-requests.store');

// Photographer authenticated routes
Route::middleware(['auth', 'role:photographer'])->prefix('photographer')->name('photographer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PhotographerController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [PhotographerController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [PhotographerController::class, 'update'])->name('profile.update');
    Route::put('/profile/specialties', [PhotographerController::class, 'updateSpecialties'])->name('profile.specialties');

    // Availabilities
    Route::get('/availabilities', [AvailabilityController::class, 'index'])->name('availabilities.index');
    Route::post('/availabilities', [AvailabilityController::class, 'store'])->name('availabilities.store');
    Route::put('/availabilities/{availability}', [AvailabilityController::class, 'update'])->name('availabilities.update');
    Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');
    Route::post('/availabilities/bulk', [AvailabilityController::class, 'bulkUpdate'])->name('availabilities.bulk');

    // Booking requests
    Route::get('/requests', [BookingRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{bookingRequest}', [BookingRequestController::class, 'show'])->name('requests.show');
    Route::put('/requests/{bookingRequest}', [BookingRequestController::class, 'update'])->name('requests.update');
});

require __DIR__.'/auth.php';

// Fallback route for 404 - redirect to home or appropriate page
Route::fallback(function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match ($user->role) {
            'photographer' => redirect()->route('photographer.dashboard')->with('error', 'Page non trouvee.'),
            'client' => redirect()->route('search.index')->with('error', 'Page non trouvee.'),
            default => redirect('/')->with('error', 'Page non trouvee.'),
        };
    }

    return redirect('/')->with('error', 'Page non trouvee.');
});
