<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name', 'category', 'price',
        'description', 'image', 'is_active',
        'includes', 'thumbnail', 'max_guests',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
        'includes'  => 'array',
    ];
}