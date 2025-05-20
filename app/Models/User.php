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
        'alamat',
        'jabatan',
    ];



    /**
     * disembunyikan untuk serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * A dikonversi tipenya.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * pengguna adalah admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     *  pengguna adalah kepala toko
     *
     * @return bool
     */
    public function isKepalaToko()
    {
        return $this->role === 'kepala_toko';
    }

    /**
     * engguna adalah teknisi
     *
     * @return bool
     */
    public function isTeknisi()
    {
        return $this->role === 'teknisi' || $this->role === 'kepala teknisi';
    }

    /**
     * engguna memiliki akses ke sistem
     *
     * @return bool
     */
    public function hasAccess()
    {
        $allowedRoles = ['admin', 'kepala_toko', 'teknisi'];
        return in_array($this->role, $allowedRoles);
    }
}
