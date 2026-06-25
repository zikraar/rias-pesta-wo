<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'user_id', 'event_date', 'event_location',
        'guest_count', 'event_type', 'groom_name', 'bride_name',
        'special_requests', 'total_price', 'status', 'admin_notes'
    ];

    protected $casts = [
        'event_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            $booking->booking_code = 'WO-' . date('Y') . '-' . str_pad(
                Booking::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT
            );
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function packages() { return $this->hasMany(BookingPackage::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function progress() { return $this->hasMany(Progress::class)->orderBy('order'); }
    public function events() { return $this->hasMany(Event::class); }

    // Hitung total yang sudah dibayar
    public function totalPaid()
    {
        return $this->payments()->where('status', 'verified')->sum('amount');
    }

    // Sisa tagihan
    public function remainingPayment()
    {
        return $this->total_price - $this->totalPaid();
    }
}