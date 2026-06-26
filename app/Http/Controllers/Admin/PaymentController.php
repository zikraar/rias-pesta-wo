<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('booking.user')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(10)->withQueryString();
        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load('booking.user');
        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Payment $payment)
    {
        if ($payment->booking->status === 'cancelled') {
            return back()->with('error', 'Booking ini sudah dibatalkan, pembayaran tidak bisa diverifikasi.');
        }

        $payment->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Verifikasi pertama pada booking pending = bukti booking serius -> auto-confirm
        $booking = $payment->booking;
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'confirmed']);
            $booking->createDefaultProgress();
            $booking->createWeddingEvent();
        }

        // Lunas saat sudah confirmed -> lanjut diproses (completed tetap manual oleh admin)
        if ($booking->status === 'confirmed' && $booking->remainingPayment() <= 0) {
            $booking->update(['status' => 'in_progress']);
        }

        $booking->user->notify(new \App\Notifications\PaymentVerified($payment));

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['admin_notes' => 'required|string']);

        $payment->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        $payment->booking->user->notify(new \App\Notifications\PaymentRejected($payment));

        return back()->with('error', 'Pembayaran ditolak.');
    }
}