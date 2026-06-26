<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'booking_id', 'title', 'description', 'event_date',
        'start_time', 'end_time', 'location', 'type', 'color',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}