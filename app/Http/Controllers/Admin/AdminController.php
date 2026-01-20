<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\Photographer;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::count(),
            'clients' => User::where('role', 'client')->count(),
            'photographers' => Photographer::count(),
            'photographers_verified' => Photographer::where('is_verified', true)->count(),
            'photographers_pending' => Photographer::where('is_verified', false)->count(),
            'booking_requests' => BookingRequest::count(),
            'booking_requests_pending' => BookingRequest::where('status', 'pending')->count(),
            'booking_requests_accepted' => BookingRequest::where('status', 'accepted')->count(),
            'reviews' => Review::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function photographers(Request $request): View
    {
        $query = Photographer::with('user');

        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_verified', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $photographers = $query->latest()->paginate(20)->withQueryString();
        $pendingCount = Photographer::where('is_verified', false)->count();

        return view('admin.photographers.index', compact('photographers', 'pendingCount'));
    }

    public function toggleVerification(Photographer $photographer): RedirectResponse
    {
        $photographer->update([
            'is_verified' => !$photographer->is_verified,
        ]);

        $status = $photographer->is_verified ? 'vérifié' : 'non vérifié';
        $message = "Le photographe {$photographer->user->name} a été marqué comme {$status}.";

        return back()->with('success', $message);
    }
}
