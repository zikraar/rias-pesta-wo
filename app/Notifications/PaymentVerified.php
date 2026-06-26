<?php
namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerified extends Notification implements ShouldQueue
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
            'message' => 'Pembayaran Rp ' . number_format($this->payment->amount, 0, ',', '.') . ' untuk booking ' . $this->payment->booking->booking_code . ' telah diverifikasi.',
            'url'     => route('customer.bookings.show', $this->payment->booking),
            'type'    => 'payment',
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pembayaran Terverifikasi - ' . $this->payment->booking->booking_code)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Pembayaran sebesar Rp ' . number_format($this->payment->amount, 0, ',', '.') . ' untuk booking ' . $this->payment->booking->booking_code . ' telah diverifikasi oleh admin.')
            ->action('Lihat Detail Booking', route('customer.bookings.show', $this->payment->booking))
            ->line('Terima kasih telah menggunakan layanan Rias Pesta Pekanbaru.');
    }
}
