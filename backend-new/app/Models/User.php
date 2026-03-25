<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $connection = 'mongodb';
    protected $collection = 'users';

   protected $fillable = [
    'name',
    'email',
    'password',
    'phone',         
    'role',
    'status',
    'canteen_id',
    'photo_profile',  
];

    protected $hidden = ['password'];

 // Ganti fungsi createToken kamu dengan ini
    public function createToken(string $name, array $abilities = ['*'], ?\DateTimeInterface $expiresAt = null)
    {
        $plainTextToken = \Illuminate\Support\Str::random(40);

        // Buat token langsung menggunakan Model MongoDB kita
        $token = \App\Models\PersonalAccessToken::create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
            'tokenable_id' => (string) $this->_id,
            'tokenable_type' => static::class,
        ]);

        // Akali Sanctum dengan mengirimkan objek sederhana (bukan class NewAccessToken)
        return (object) [
            'accessToken' => $token,
            'plainTextToken' => $token->_id . '|' . $plainTextToken
        ];
    }
    const ROLE_ADMIN_GLOBAL = 'admin_global';
    const ROLE_ADMIN_KANTIN = 'admin_kantin';
    const ROLE_PEMBELI = 'pembeli';
}