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
        'role',
        'phone',
        'photo_profile',
        'canteen_id',
    ];

    protected $hidden = ['password'];

    public function createToken(string $name, array $abilities = ['*'], ?\DateTimeInterface $expiresAt = null)
    {
        $plainTextToken = $this->generateTokenString();
        $hashedToken = hash('sha256', $plainTextToken);

        $token = new PersonalAccessToken();
        $token->name = $name;
        $token->token = $hashedToken;
        $token->abilities = $abilities;
        $token->expires_at = $expiresAt;
        $token->tokenable_id = (string) $this->_id;
        $token->tokenable_type = get_class($this);
        $token->save();

        // Ambil fresh dari DB pakai token hash
        $fresh = PersonalAccessToken::where('token', $hashedToken)->latest()->first();
        $tokenId = (string) $fresh->_id;

        return new NewAccessToken($fresh, $tokenId . '|' . $plainTextToken);
    }

    public function generateTokenString()
    {
        return Str::random(40);
    }

    const ROLE_ADMIN_GLOBAL = 'admin_global';
    const ROLE_ADMIN_KANTIN = 'admin_kantin';
    const ROLE_PEMBELI = 'pembeli';
}