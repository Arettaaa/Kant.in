<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Laravel\Sanctum\Contracts\HasAbilities;

class PersonalAccessToken extends Model implements HasAbilities
{
    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';

    protected $fillable = [
        'name',
        'token',
        'abilities',
        'last_used_at', // Tambahkan ini juga jika ingin tracking penggunaan terakhir
        'expires_at',
        'tokenable_id',
        'tokenable_type',
    ];

    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Method WAJIB untuk Sanctum agar bisa mengenali token
    public static function findToken($token)
    {
        if (str_contains($token, '|')) {
            [$id, $token] = explode('|', $token, 2);
        }

        return static::where('token', hash('sha256', $token))->first();
    }

    public function can($ability)
    {
        return in_array('*', $this->abilities ?? []) || in_array($ability, $this->abilities ?? []);
    }

    public function cant($ability)
    {
        return !$this->can($ability);
    }
    
    // Relasi ke User/Model pemilik token
    public function tokenable()
    {
        return $this->morphTo('tokenable');
    }
}