<?php
namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function statusLabel(): string
    {
        $statusLabel = [
            'pending'     => 'Menunggu Konfirmasi',
            'confirmed'   => 'Dikonfirmasi',
            'in_progress' => 'Sedang Diproses',
            'completed'   => 'Selesai',
            'cancelled'   => 'Dibatalkan',
        ];

        return $statusLabel[$this->booking->status] ?? $this->booking->status;
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Status booking ' . $this->booking->booking_code . ' diubah menjadi: ' . $this->statusLabel(),
            'url'     => route('customer.bookings.show', $this->booking),
            'type'    => 'booking',
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update Status Booking ' . $this->booking->booking_code)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Status booking ' . $this->booking->booking_code . ' Anda telah diubah menjadi: ' . $this->statusLabel() . '.')
            ->action('Lihat Detail Booking', route('customer.bookings.show', $this->booking))
            ->line('Terima kasih telah menggunakan layanan Rias Pesta Pekanbaru.');
    }
}
