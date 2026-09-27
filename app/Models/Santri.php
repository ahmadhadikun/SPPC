<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santris';
    protected $primaryKey = 'idSantri';

    protected $fillable = [
        'nis',
        'nama_santri',
        'kamar',
    ];

    // Relasi ke PengambilanLauk
    public function pengambilanLauks()
    {
        return $this->hasMany(PengambilanLauk::class, 'santri_id', 'idSantri');
    }

    // Relasi ke Presensi
    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'santri_id', 'idSantri');
    }

    // Relasi ke QrCode
    public function qrCode()
    {
        return $this->hasOne(QrCode::class, 'santri_id', 'idSantri');
    }

    // Relasi ke PesertaCatering
    public function pesertaCatering()
    {
        return $this->hasOne(PesertaCatering::class, 'santri_id', 'idSantri');
    }
}