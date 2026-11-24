<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $primaryKey = 'id_presensi';

    protected $fillable = [
        'id_santri',
        'id_catering',
        'status',
        'waktu_scan',
    ];

    protected $casts = [
        'waktu_scan' => 'datetime',
    ];

    // Relasi ke Santri
    public function santri()
    {
        return $this->belongsTo(
            Santri::class,
            'id_santri',
            'id_santri'
        );
    }

    // Relasi ke Catering
    public function catering()
    {
        return $this->belongsTo(
            Catering::class,
            'id_catering',
            'id_catering'
        );
    }

    // Relasi ke Pengambilan Lauk
    public function pengambilanLauk()
    {
        return $this->hasOne(
            PengambilanLauk::class,
            'id_presensi',
            'id_presensi'
        );
    }
}
