<?php
namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentVerified extends Notification
{
    public function __construct(public Payment $payment) {}

    public function via($notifiable): array { return ['database', 'mail']; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Pembayaran {$this->payment->payment_code} Terverifikasi ✅")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Pembayaran Anda telah berhasil diverifikasi.")
            ->line("Kode Pembayaran: {$this->payment->payment_code}")
            ->line("Jumlah: Rp " . number_format($this->payment->amount, 0, ',', '.'))
            ->action('Lihat Detail', route('customer.payments.show', $this->payment))
            ->line('Terima kasih!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'           => 'payment_verified',
            'payment_code'   => $this->payment->payment_code,
            'amount'         => $this->payment->amount,
            'message'        => "Pembayaran {$this->payment->payment_code} telah diverifikasi.",
            'url'            => route('customer.payments.show', $this->payment),
        ];
    }
}