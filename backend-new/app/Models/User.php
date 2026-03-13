<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

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

    protected $hidden = [
        'password',
    ];

    const ROLE_ADMIN_GLOBAL = 'admin_global';
    const ROLE_ADMIN_KANTIN = 'admin_kantin';
    const ROLE_PEMBELI = 'pembeli';
}