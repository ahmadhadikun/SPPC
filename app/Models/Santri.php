<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri';

    protected $primaryKey = 'id_santri';

    protected $fillable = [
        'nama',
        'kelas',
        'asrama',
    ];

    // Relasi ke Presensi
    public function presensis()
    {
        return $this->hasMany(
            Presensi::class,
            'id_santri',
            'id_santri'
        );
    }

    // Relasi ke QR Code
    public function qrCode()
    {
        return $this->hasOne(
            QrCode::class,
            'id_santri',
            'id_santri'
        );
    }
}
