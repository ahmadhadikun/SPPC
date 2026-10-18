<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santris';

    // Primary key sesuai database
    protected $primaryKey = 'idSantri';

    protected $fillable = [
        'nis',
        'nama_santri',
        'kamar',
    ];

    // Relasi ke Presensi
    public function presensis()
    {
        return $this->hasMany(
            Presensi::class,
            'idSantri',
            'idSantri'
        );
    }

    // Relasi ke QR Code
    public function qrCode()
    {
        return $this->hasOne(
            QrCode::class,
            'santri_id',
            'idSantri'
        );
    }
}
