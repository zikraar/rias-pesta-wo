<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'booking_id', 'title', 'event_date',
        'location', 'type', 'color',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}