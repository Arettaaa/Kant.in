<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;


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
        'is_open',
        'status',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'canteen_id');
    }

    public function admin()
    {
        // Mengambil user yang memiliki role admin_kantin dan canteen_id yang cocok
        return $this->hasOne(User::class, 'canteen_id', '_id');
    }

    public function getIsOpenAttribute(): bool
    {
        // Cek is_active DAN is_open dulu
        if (!$this->is_active || !($this->attributes['is_open'] ?? true)) {
            return false;
        }

        if ($this->status !== 'active') {
            return false;
        }

        $hours = $this->operating_hours;
        if (empty($hours['open']) || empty($hours['close'])) {
            return false;
        }

        $now = Carbon::now('Asia/Jakarta');

        try {
            $open  = Carbon::createFromFormat('H:i', $hours['open'],  'Asia/Jakarta');
            $close = Carbon::createFromFormat('H:i', $hours['close'], 'Asia/Jakarta');
        } catch (\Exception $e) {
            return false;
        }

        $open->setDate($now->year, $now->month, $now->day);
        $close->setDate($now->year, $now->month, $now->day);

        if ($close->lessThan($open)) {
            $close->addDay();
        }

        return $now->between($open, $close);
    }
}
