<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email', 
        'password',
        'phone',        // tambah ini
        'id_number',    // NIK/KTP untuk refund
        'birthdate', 
        'avatar'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];
}