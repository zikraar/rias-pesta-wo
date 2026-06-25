<?php
namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentRejected extends Notification
{
    public function __construct(public Payment $payment) {}

    public function via($notifiable): array { return ['database', 'mail']; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Pembayaran {$this->payment->payment_code} Ditolak ❌")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Maaf, pembayaran Anda ditolak dengan alasan:")
            ->line($this->payment->admin_notes)
            ->line("Silakan upload ulang bukti transfer yang benar.")
            ->action('Upload Ulang', route('customer.payments.create', [
                'booking_id' => $this->payment->booking_id
            ]));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'    => 'payment_rejected',
            'message' => "Pembayaran {$this->payment->payment_code} ditolak: {$this->payment->admin_notes}",
            'url'     => route('customer.payments.index'),
        ];
    }
}