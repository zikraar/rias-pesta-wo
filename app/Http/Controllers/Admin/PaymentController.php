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
        $payment->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Update status booking jika lunas
        $booking = $payment->booking;
        if ($booking->remainingPayment() <= 0) {
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