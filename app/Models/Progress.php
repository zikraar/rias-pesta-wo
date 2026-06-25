<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $fillable = [
        'booking_id', 'title', 'description', 'status',
        'order', 'target_date', 'completed_date', 'attachment'
    ];

    protected $casts = [
        'target_date' => 'date',
        'completed_date' => 'date',
    ];

    public function booking() { return $this->belongsTo(Booking::class); }

    // Persentase progress booking
    public static function getPercentage($bookingId): int
    {
        $total = self::where('booking_id', $bookingId)->count();
        if ($total === 0) return 0;
        $done = self::where('booking_id', $bookingId)->where('status', 'done')->count();
        return (int)(($done / $total) * 100);
    }
}