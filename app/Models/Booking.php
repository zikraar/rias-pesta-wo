<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // Status hanya boleh maju satu langkah; 'cancelled' bisa dari status mana pun selain completed/cancelled
    const TRANSITIONS = [
        'pending'     => ['confirmed', 'cancelled'],
        'confirmed'   => ['in_progress', 'cancelled'],
        'in_progress' => ['completed', 'cancelled'],
        'completed'   => [],
        'cancelled'   => [],
    ];

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

    public function canTransitionTo(string $status): bool
    {
        return in_array($status, self::TRANSITIONS[$this->status] ?? []);
    }

    public function createDefaultProgress(): void
    {
        $defaultSteps = [
            ['title' => 'Konfirmasi Booking',        'order' => 1],
            ['title' => 'Survey Lokasi',              'order' => 2],
            ['title' => 'Fitting Busana Pengantin',   'order' => 3],
            ['title' => 'Persiapan Dekorasi',         'order' => 4],
            ['title' => 'Gladi Resik',                'order' => 5],
            ['title' => 'Hari Pernikahan',            'order' => 6],
            ['title' => 'Dokumentasi Selesai',        'order' => 7],
        ];

        foreach ($defaultSteps as $step) {
            Progress::create([
                'booking_id' => $this->id,
                'title'      => $step['title'],
                'status'     => $step['order'] === 1 ? 'done' : 'pending',
                'order'      => $step['order'],
            ]);
        }
    }

    public function createWeddingEvent(): void
    {
        Event::create([
            'booking_id' => $this->id,
            'title'      => "Pernikahan {$this->groom_name} & {$this->bride_name}",
            'event_date' => $this->event_date,
            'location'   => $this->event_location,
            'type'       => 'wedding',
            'color'      => '#e11d48',
        ]);
    }
}