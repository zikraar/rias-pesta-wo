<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id', 'payment_code', 'payment_type', 'amount',
        'bank_name', 'account_number', 'account_name',
        'transfer_proof', 'transfer_date', 'status', 'admin_notes',
        'verified_by', 'verified_at'
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($payment) {
            $payment->payment_code = 'PAY-' . date('Y') . '-' . str_pad(
                Payment::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT
            );
        });
    }

    public function booking() { return $this->belongsTo(Booking::class); }
    public function verifiedBy() { return $this->belongsTo(User::class, 'verified_by'); }
}