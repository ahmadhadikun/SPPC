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

    // Relasi ke Santri
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id', 'idSantri');
    }

    // Relasi ke Catering
    public function catering()
    {
        return $this->belongsTo(Catering::class, 'catering_id', 'idCatering');
    }
}