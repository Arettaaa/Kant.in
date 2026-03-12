<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'order_code',
        'customer_snapshot',
        'canteen_id',
        'items',
        'subtotal_amount',
        'delivery_details',
        'total_amount',
        'payment',
        'status',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_READY = 'ready';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
}