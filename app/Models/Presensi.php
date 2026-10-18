<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';

    protected $primaryKey = 'idPresensi';

    protected $fillable = [
        'santri_id',
        'catering_id',
        'tanggal',
        'waktu',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(
            Santri::class,
            'santri_id',
            'idSantri'
        );
    }

    public function catering()
    {
        return $this->belongsTo(
            Catering::class,
            'catering_id',
            'idCatering'
        );
    }

    public function pengambilanLauk()
    {
        return $this->hasOne(
            PengambilanLauk::class,
            'catering_id',
            'catering_id'
        );
    }
}
