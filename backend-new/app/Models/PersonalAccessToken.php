<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as MongoModel;
use Laravel\Sanctum\Contracts\HasAbilities;

class PersonalAccessToken extends MongoModel implements HasAbilities
{
    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';
    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 'token', 'abilities',
        'expires_at', 'tokenable_id', 'tokenable_type',
    ];

    protected $casts = [
        'abilities' => 'json',
        'expires_at' => 'datetime',
    ];

    public function getKeyAttribute()
    {
        return (string) $this->_id;
    }

    public static function findToken($token)
    {
        if (!str_contains($token, '|')) {
            return static::where('token', hash('sha256', $token))->first();
        }

        [$id, $plaintext] = explode('|', $token, 2);
        $instance = static::find($id);

        if ($instance && hash_equals($instance->token, hash('sha256', $plaintext))) {
            return $instance;
        }
    }

    public function tokenable()
    {
        return $this->morphTo('tokenable');
    }

    public function can($ability)
    {
        return in_array('*', $this->abilities ?? []) ||
               in_array($ability, $this->abilities ?? []);
    }

    public function cant($ability)
    {
        return !$this->can($ability);
    }
}