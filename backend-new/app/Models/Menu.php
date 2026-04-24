<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Menu extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'menus';

    protected $fillable = [
        'canteen_id',
        'name',
        'description',
        'price',
        'category',
        'image',
        'is_available',
        'estimated_cooking_time',
        // Rating fields
        'average_rating',   // float, e.g. 4.3
        'total_reviews',    // int
        'reviews',          // array of { user_id, user_name, rating, order_id, created_at }
    ];

    public function canteen()
    {
        return $this->belongsTo(Canteen::class, 'canteen_id');
    }
}