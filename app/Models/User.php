<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Atribut yang harus disembunyikan untuk serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus dikonversi tipenya.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Cek apakah pengguna adalah admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah pengguna adalah kepala toko
     *
     * @return bool
     */
    public function isKepalaToko()
    {
        return $this->role === 'kepala_toko';
    }

    /**
     * Cek apakah pengguna adalah teknisi
     *
     * @return bool
     */
    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    /**
     * Cek apakah pengguna memiliki akses ke sistem
     *
     * @return bool
     */
    public function hasAccess()
    {
        $allowedRoles = ['admin', 'kepala_toko', 'teknisi'];
        return in_array($this->role, $allowedRoles);
    }
}