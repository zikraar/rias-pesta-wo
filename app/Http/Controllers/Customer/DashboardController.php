<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Payment};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_bookings'  => Booking::where('user_id', $user->id)->count(),
            'active_bookings' => Booking::where('user_id', $user->id)
                ->whereIn('status', ['confirmed', 'in_progress'])->count(),
            'completed'       => Booking::where('user_id', $user->id)
                ->where('status', 'completed')->count(),
        ];

        $activeBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->with(['packages.package', 'progress'])
            ->latest()
            ->get();

        $recentPayments = Payment::whereHas('booking', fn($q) =>
            $q->where('user_id', $user->id)
        )->latest()->take(3)->get();

        $notifications = $user->notifications()->take(5)->get();

        return view('customer.dashboard', compact(
            'stats', 'activeBookings', 'recentPayments', 'notifications'
        ));
    }

    public function profile()
    {
        return view('customer.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|max:20',
            'address'=> 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $data = $request->only('name', 'phone', 'address');

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}