<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Payment, Booking};
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::whereHas('booking', fn($q) => $q->where('user_id', auth()->id()))
            ->with('booking')
            ->latest()
            ->paginate(10);
        return view('customer.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->where('id', $request->booking_id)
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->firstOrFail();

        // Info rekening tujuan pembayaran
        $bankAccounts = [
            ['bank' => 'BCA',     'number' => '1234567890', 'name' => 'Rias Pesta Pekanbaru'],
            ['bank' => 'Mandiri', 'number' => '0987654321', 'name' => 'Rias Pesta Pekanbaru'],
            ['bank' => 'BNI',     'number' => '1122334455', 'name' => 'Rias Pesta Pekanbaru'],
        ];

        return view('customer.payments.create', compact('booking', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id'     => 'required|exists:bookings,id',
            'payment_type'   => 'required|in:dp,pelunasan,full',
            'amount'         => 'required|numeric|min:100000',
            'bank_name'      => 'required|string',
            'account_number' => 'required|string',
            'account_name'   => 'required|string',
            'transfer_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'transfer_date'  => 'required|date|before_or_equal:today',
        ]);

        $booking = Booking::where('user_id', auth()->id())
            ->findOrFail($request->booking_id);

        $proofPath = $request->file('transfer_proof')
            ->store('payments/proofs', 'public');

        Payment::create([
            'booking_id'     => $booking->id,
            'payment_type'   => $request->payment_type,
            'amount'         => $request->amount,
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'transfer_proof' => $proofPath,
            'transfer_date'  => $request->transfer_date,
            'status'         => 'pending',
        ]);

        return redirect()
            ->route('customer.payments.index')
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    public function show(Payment $payment)
    {
        abort_if($payment->booking->user_id !== auth()->id(), 403);
        return view('customer.payments.show', compact('payment'));
    }
}