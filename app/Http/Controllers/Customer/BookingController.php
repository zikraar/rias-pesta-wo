<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Package, BookingPackage};
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with('packages.package')
            ->latest()
            ->paginate(10);
        return view('customer.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $packages = Package::where('is_active', true)->get();
        return view('customer.bookings.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone'           => 'required|string|max:20',
            'event_date'      => 'required|date|after:today',
            'event_location'  => 'required|string|max:255',
            'guest_count'     => 'required|integer|min:1',
            'event_type'      => 'required|in:akad,resepsi,akad_resepsi',
            'groom_name'      => 'required|string|max:255',
            'bride_name'      => 'required|string|max:255',
            'package_id'      => 'required|exists:packages,id',
            'special_requests'=> 'nullable|string|max:1000',
        ]);

        // Cek ketersediaan tanggal
        $conflict = Booking::where('event_date', $request->event_date)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['event_date' => 'Tanggal tersebut sudah dipesan. Silakan pilih tanggal lain.']);
        }

        // Sinkronkan nomor HP ke profil
        if ($request->phone !== auth()->user()->phone) {
            auth()->user()->update(['phone' => $request->phone]);
        }

        $package = Package::findOrFail($request->package_id);

        $booking = Booking::create([
            'user_id'          => auth()->id(),
            'event_date'       => $request->event_date,
            'event_location'   => $request->event_location,
            'guest_count'      => $request->guest_count,
            'event_type'       => $request->event_type,
            'groom_name'       => $request->groom_name,
            'bride_name'       => $request->bride_name,
            'special_requests' => $request->special_requests,
            'total_price'      => 0,
            'status'           => 'pending',
        ]);

        // Simpan paket yang dipilih (price_snapshot adalah sumber kebenaran total_price)
        BookingPackage::create([
            'booking_id'     => $booking->id,
            'package_id'     => $package->id,
            'price_snapshot' => $package->price,
        ]);

        $booking->update(['total_price' => $booking->packages()->sum('price_snapshot')]);

        return redirect()
            ->route('customer.bookings.show', $booking)
            ->with('success', 'Booking berhasil dibuat! Tunggu konfirmasi dari admin.');
    }

    public function show(Booking $booking)
    {
        // Pastikan customer hanya bisa lihat bookingnya sendiri
        abort_if($booking->user_id !== auth()->id(), 403);

        $booking->load(['packages.package', 'payments', 'progress']);
        return view('customer.bookings.show', compact('booking'));
    }
}