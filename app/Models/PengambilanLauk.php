<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengambilanLauk extends Model
{
    use HasFactory;

    protected $table = 'pengambilan_lauk';

    protected $primaryKey = 'id_pengambilan';

    protected $fillable = [
        'id_presensi',
        'jumlah_lauk',
    ];

    // Relasi ke Presensi
    public function presensi()
    {
        return $this->belongsTo(
            Presensi::class,
            'id_presensi',
            'id_presensi'
        );
    }

    // Relasi ke Santri melalui Presensi
    public function santri()
    {
        return $this->hasOneThrough(
            Santri::class,
            Presensi::class,
            'id_presensi',
            'id_santri',
            'id_presensi',
            'id_santri'
        );
    }

    // Relasi ke Catering melalui Presensi
    public function catering()
    {
        return $this->hasOneThrough(
            Catering::class,
            Presensi::class,
            'id_presensi',
            'id_catering',
            'id_presensi',
            'id_catering'
        );
    }
}
