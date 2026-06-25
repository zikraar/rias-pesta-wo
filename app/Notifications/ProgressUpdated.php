<?php
namespace App\Notifications;

use App\Models\Progress;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProgressUpdated extends Notification
{
    public function __construct(public Progress $progress) {}

    public function via($notifiable): array { return ['database']; }

    public function toDatabase($notifiable): array
    {
        $booking = $this->progress->booking;
        return [
            'type'    => 'progress_updated',
            'message' => "Progress '{$this->progress->title}' untuk booking {$booking->booking_code} diperbarui menjadi {$this->progress->status}.",
            'url'     => route('customer.bookings.show', $booking),
        ];
    }
}