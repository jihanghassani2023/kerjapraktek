<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKepalaToko()
    {
        return $this->role === 'kepala_toko';
    }

    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    public function hasAccess()
    {
        return in_array($this->role, ['admin', 'kepala_toko', 'teknisi']);
    }
}
