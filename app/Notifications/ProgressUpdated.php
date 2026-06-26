<?php
namespace App\Notifications;

use App\Models\Progress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProgressUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Progress $progress) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function statusLabel(): string
    {
        $statusLabel = [
            'pending'     => 'Belum Dimulai',
            'on_progress' => 'Sedang Berlangsung',
            'done'        => 'Selesai',
        ];

        return $statusLabel[$this->progress->status] ?? $this->progress->status;
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Progress "' . $this->progress->title . '" pada booking ' . $this->progress->booking->booking_code . ' diupdate menjadi: ' . $this->statusLabel(),
            'url'     => route('customer.bookings.show', $this->progress->booking),
            'type'    => 'progress',
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Update Progress Booking ' . $this->progress->booking->booking_code)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Progress "' . $this->progress->title . '" pada booking ' . $this->progress->booking->booking_code . ' diupdate menjadi: ' . $this->statusLabel() . '.')
            ->action('Lihat Detail Booking', route('customer.bookings.show', $this->progress->booking))
            ->line('Terima kasih telah menggunakan layanan Rias Pesta Pekanbaru.');
    }
}
