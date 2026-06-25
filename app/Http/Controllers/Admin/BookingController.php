<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Progress, Event};
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

        $oldStatus = $booking->status;
        $booking->update($request->only('status', 'admin_notes'));

        // Auto-buat progress template saat confirmed
        if ($oldStatus === 'pending' && $request->status === 'confirmed') {
            $this->createDefaultProgress($booking);
            $this->createWeddingEvent($booking);
        }

        // Kirim notifikasi ke customer
        $booking->user->notify(new \App\Notifications\BookingStatusUpdated($booking));

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    private function createDefaultProgress(Booking $booking): void
    {
        $defaultSteps = [
            ['title' => 'Konfirmasi Booking',        'order' => 1],
            ['title' => 'Survey Lokasi',              'order' => 2],
            ['title' => 'Fitting Busana Pengantin',   'order' => 3],
            ['title' => 'Persiapan Dekorasi',         'order' => 4],
            ['title' => 'Gladi Resik',                'order' => 5],
            ['title' => 'Hari Pernikahan',            'order' => 6],
            ['title' => 'Dokumentasi Selesai',        'order' => 7],
        ];

        foreach ($defaultSteps as $step) {
            Progress::create([
                'booking_id' => $booking->id,
                'title'      => $step['title'],
                'status'     => $step['order'] === 1 ? 'done' : 'pending',
                'order'      => $step['order'],
            ]);
        }
    }

    private function createWeddingEvent(Booking $booking): void
    {
        Event::create([
            'booking_id' => $booking->id,
            'title'      => "Pernikahan {$booking->groom_name} & {$booking->bride_name}",
            'event_date' => $booking->event_date,
            'location'   => $booking->event_location,
            'type'       => 'wedding',
            'color'      => '#e11d48',
        ]);
    }

    public function invoice(Booking $booking)
    {
        $booking->load(['user', 'packages.package', 'payments']);
        $pdf = Pdf::loadView('pdf.invoice', compact('booking'));
        return $pdf->stream("Invoice-{$booking->booking_code}.pdf");
    }
}