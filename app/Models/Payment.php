<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id', 'payment_code',
        'payment_type', 'amount',
        'bank_name', 'account_number',
        'account_name', 'sender_name',
        'transfer_proof', 'proof_image',
        'transfer_date', 'notes', 'status',
        'admin_notes', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'verified_at'   => 'datetime',
        'amount'        => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}