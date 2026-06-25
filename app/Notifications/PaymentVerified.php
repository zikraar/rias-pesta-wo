<?php
namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Notifications\Notification;

class PaymentVerified extends Notification
{
    public function __construct(public Payment $payment) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Pembayaran Rp ' . number_format($this->payment->amount, 0, ',', '.') . ' untuk booking ' . $this->payment->booking->booking_code . ' telah diverifikasi.',
            'url'     => route('customer.bookings.show', $this->payment->booking),
            'type'    => 'payment',
        ];
    }
}