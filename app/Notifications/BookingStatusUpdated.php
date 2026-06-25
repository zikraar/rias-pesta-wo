<?php
namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingStatusUpdated extends Notification
{
    public function __construct(public Booking $booking) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $statusLabel = match($this->booking->status) {
            'confirmed'   => 'Dikonfirmasi ✅',
            'in_progress' => 'Sedang Diproses 🔄',
            'completed'   => 'Selesai 🎉',
            'cancelled'   => 'Dibatalkan ❌',
            default       => ucfirst($this->booking->status),
        };

        return (new MailMessage)
            ->subject("Status Booking {$this->booking->booking_code} Diperbarui")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Status booking Anda telah diperbarui menjadi: **{$statusLabel}**")
            ->line("Kode Booking: {$this->booking->booking_code}")
            ->line("Acara: {$this->booking->groom_name} & {$this->booking->bride_name}")
            ->line("Tanggal: " . $this->booking->event_date->format('d F Y'))
            ->action('Lihat Detail Booking', route('customer.bookings.show', $this->booking))
            ->line('Terima kasih telah mempercayakan pernikahan Anda kepada Rias Pesta Pekanbaru.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'          => 'booking_status',
            'booking_code'  => $this->booking->booking_code,
            'status'        => $this->booking->status,
            'message'       => "Status booking {$this->booking->booking_code} diperbarui menjadi " . $this->booking->status,
            'url'           => route('customer.bookings.show', $this->booking),
        ];
    }
}