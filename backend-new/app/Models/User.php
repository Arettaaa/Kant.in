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

        $token = new PersonalAccessToken([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
            'tokenable_id' => (string) $this->_id,
            'tokenable_type' => get_class($this),
        ]);

        $token->save();

        $tokenId = (string) $token->_id;

        return new NewAccessToken($token, $tokenId . '|' . $plainTextToken);
    }
}