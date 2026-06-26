<?php
namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payment $payment) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Pembayaran untuk booking ' . $this->payment->booking->booking_code . ' ditolak. Alasan: ' . ($this->payment->admin_notes ?? '-'),
            'url'     => route('customer.bookings.show', $this->payment->booking),
            'type'    => 'payment_rejected',
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembayaran Ditolak - ' . $this->payment->booking->booking_code)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Pembayaran Anda untuk booking ' . $this->payment->booking->booking_code . ' ditolak oleh admin.')
            ->line('Alasan: ' . ($this->payment->admin_notes ?? '-'))
            ->action('Lihat Detail Booking', route('customer.bookings.show', $this->payment->booking))
            ->line('Silakan upload ulang bukti pembayaran yang valid.');
    }
}
