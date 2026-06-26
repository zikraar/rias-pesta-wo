<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('user')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('booking_code', 'like', "%{$request->search}%")
                  ->orWhere('groom_name', 'like', "%{$request->search}%")
                  ->orWhere('bride_name', 'like', "%{$request->search}%");
            });
        }

        $bookings = $query->paginate(10)->withQueryString();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'packages.package', 'payments', 'progress', 'events']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status'      => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if (!$booking->canTransitionTo($request->status)) {
            return back()->with('error', 'Perubahan status tidak valid.');
        }

        $oldStatus = $booking->status;
        $booking->update($request->only('status', 'admin_notes'));

        // Auto-buat progress template saat confirmed
        if ($oldStatus === 'pending' && $request->status === 'confirmed') {
            $booking->createDefaultProgress();
            $booking->createWeddingEvent();
        }

        // Kirim notifikasi ke customer
        $booking->user->notify(new \App\Notifications\BookingStatusUpdated($booking));

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function invoice(Booking $booking)
    {
        $booking->load(['user', 'packages.package', 'payments']);
        $pdf = Pdf::loadView('pdf.invoice', compact('booking'));
        return $pdf->stream("Invoice-{$booking->booking_code}.pdf");
    }
}