<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catering extends Model
{
    use HasFactory;

    protected $table = 'caterings';
    protected $primaryKey = 'idCatering';

    protected $fillable = [
        'user_id',
        'tanggal',
        'sesi',
        'menu',
    ];

    // Relasi ke User (Admin/Pengurus)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Presensi
    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'catering_id', 'idCatering');
    }

    // Relasi ke PengambilanLauk
    public function pengambilanLauks()
    {
        return $this->hasMany(PengambilanLauk::class, 'catering_id', 'idCatering');
    }
}