<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santris';

    protected $primaryKey = 'idSantri';

    protected $fillable = [
        'NISN',
        'namaSantri',
        'kamar',
    ];

    public function pesertaCatering()
    {
        return $this->hasOne(
            PesertaCatering::class,
            'idSantri',
            'idSantri'
        );
    }

    public function qrCode()
    {
        return $this->hasOne(
            QRCode::class,
            'idSantri',
            'idSantri'
        );
    }

    public function presensis()
    {
        return $this->hasMany(
            Presensi::class,
            'idSantri',
            'idSantri'
        );
    }

    public function pengambilanLauks()
    {
        return $this->hasMany(
            PengambilanLauk::class,
            'idSantri',
            'idSantri'
        );
    }
}