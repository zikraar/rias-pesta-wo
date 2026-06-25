<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name', 'category', 'description', 'price',
        'max_guests', 'includes', 'thumbnail', 'is_active'
    ];

    protected $casts = [
        'includes' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function bookingPackages()
    {
        return $this->hasMany(BookingPackage::class);
    }
}