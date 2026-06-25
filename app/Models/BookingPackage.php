<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPackage extends Model
{
    protected $fillable = [
        'booking_id',
        'package_id',
        'price_snapshot',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}