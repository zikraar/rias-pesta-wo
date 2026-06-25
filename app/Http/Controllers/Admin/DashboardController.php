<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Payment, User, Package, Event};
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $stats = [
            'total_bookings'    => Booking::count(),
            'pending_bookings'  => Booking::where('status', 'pending')->count(),
            'confirmed'         => Booking::where('status', 'confirmed')->count(),
            'completed'         => Booking::where('status', 'completed')->count(),
            'total_customers'   => User::where('role', 'customer')->count(),
            'revenue_month'     => Payment::where('status', 'verified')
                                    ->whereMonth('verified_at', now()->month)
                                    ->whereYear('verified_at', now()->year)
                                    ->sum('amount'),
            'pending_payments'  => Payment::where('status', 'pending')->count(),
        ];

        // Booking terbaru
        $recentBookings = Booking::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Event mendatang (7 hari ke depan)
        $upcomingEvents = Event::whereBetween('event_date', [
            Carbon::today(),
            Carbon::today()->addDays(7)
        ])->orderBy('event_date')->get();

        // Grafik revenue 6 bulan terakhir
        $revenueChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenueChart[] = [
                'month'   => $month->translatedFormat('M Y'),
                'revenue' => Payment::where('status', 'verified')
                    ->whereMonth('verified_at', $month->month)
                    ->whereYear('verified_at', $month->year)
                    ->sum('amount'),
            ];
        }

        // Booking per status (pie chart)
        $bookingStatus = Booking::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact(
            'stats', 'recentBookings', 'upcomingEvents',
            'revenueChart', 'bookingStatus'
        ));
    }
}