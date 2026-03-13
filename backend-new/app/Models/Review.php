<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Review extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reviews';

    protected $fillable = [
        'order_id',
        'user_id',
        'canteen_id',
        'menu_id',
        'rating',
        'comment',
    ];
}