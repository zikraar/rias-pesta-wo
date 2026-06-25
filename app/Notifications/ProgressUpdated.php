<?php
namespace App\Notifications;

use App\Models\Progress;
use Illuminate\Notifications\Notification;

class ProgressUpdated extends Notification
{
    public function __construct(public Progress $progress) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $statusLabel = [
            'pending'     => 'Belum Dimulai',
            'on_progress' => 'Sedang Berlangsung',
            'done'        => 'Selesai',
        ];

        return [
            'message' => 'Progress "' . $this->progress->title . '" pada booking ' . $this->progress->booking->booking_code . ' diupdate menjadi: ' . ($statusLabel[$this->progress->status] ?? $this->progress->status),
            'url'     => route('customer.bookings.show', $this->progress->booking),
            'type'    => 'progress',
        ];
    }
}