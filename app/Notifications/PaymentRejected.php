<?php
namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Notifications\Notification;

class PaymentRejected extends Notification
{
    public function __construct(public Payment $payment) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Pembayaran untuk booking ' . $this->payment->booking->booking_code . ' ditolak. Alasan: ' . ($this->payment->admin_notes ?? '-'),
            'url'     => route('customer.bookings.show', $this->payment->booking),
            'type'    => 'payment_rejected',
        ];
    }
}