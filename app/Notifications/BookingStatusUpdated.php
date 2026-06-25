<?php
namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Notification;

class BookingStatusUpdated extends Notification
{
    public function __construct(public Booking $booking) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $statusLabel = [
            'pending'     => 'Menunggu Konfirmasi',
            'confirmed'   => 'Dikonfirmasi',
            'in_progress' => 'Sedang Diproses',
            'completed'   => 'Selesai',
            'cancelled'   => 'Dibatalkan',
        ];

        return [
            'message' => 'Status booking ' . $this->booking->booking_code . ' diubah menjadi: ' . ($statusLabel[$this->booking->status] ?? $this->booking->status),
            'url'     => route('customer.bookings.show', $this->booking),
            'type'    => 'booking',
        ];
    }
}