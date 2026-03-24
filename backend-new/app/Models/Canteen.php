<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Canteen extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'canteens';
    protected $fillable = [
        'name',
        'description',
        'location',
        'phone',
        'image',
        'qris_image',
        'delivery_fee_flat',
        'operating_hours',
        'is_active',
        'status',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'canteen_id');
    }
}