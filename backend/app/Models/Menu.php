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
        'stock',
        'image',
        'is_available',
        'estimated_cooking_time',
    ];

    public function canteen()
    {
        return $this->belongsTo(Canteen::class, 'canteen_id');
    }
}