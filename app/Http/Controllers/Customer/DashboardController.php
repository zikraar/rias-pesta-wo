<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $stats = [
            'total_bookings' => Booking::where('user_id', $userId)->count(),
            'pending'        => Booking::where('user_id', $userId)->where('status', 'pending')->count(),
            'in_progress'    => Booking::where('user_id', $userId)->whereIn('status', ['confirmed','in_progress'])->count(),
            'completed'      => Booking::where('user_id', $userId)->where('status', 'completed')->count(),
        ];

        $recentBookings = Booking::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('stats', 'recentBookings'));
    }

    public function profile()
    {
        return view('customer.profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($request->only('name', 'phone'));
        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}