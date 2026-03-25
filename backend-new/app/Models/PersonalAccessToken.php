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
        'expires_at',
        'tokenable_id',
        'tokenable_type',
    ];

    protected $casts = [
        'abilities' => 'json',
        'expires_at' => 'datetime',
    ];

    public function can($ability)
    {
        return in_array('*', $this->abilities ?? []) || in_array($ability, $this->abilities ?? []);
    }

    public function cant($ability)
    {
        return !$this->can($ability);
    }
}