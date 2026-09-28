<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';

    protected $primaryKey = 'idPresensi';

    protected $fillable = [
        'idSantri',
        'idCatering',
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
            'idSantri',
            'idSantri'
        );
    }

    public function catering()
    {
        return $this->belongsTo(
            Catering::class,
            'idCatering',
            'idCatering'
        );
    }
}