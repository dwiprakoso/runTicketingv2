<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'tgl_lahir',
        'tgl_lahir_anak',
        'email',
        'no_hp',
        'nik',
        'gol_darah',
        'alamat',
        'komunitas',
        'kontak_darurat_name',
        'kontak_darurat_no',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}