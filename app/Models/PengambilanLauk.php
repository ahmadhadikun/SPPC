<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengambilanLauk extends Model
{
    use HasFactory;

    protected $table = 'pengambilan_lauks';

    protected $primaryKey = 'idPengambilan';

    protected $fillable = [
        'idSantri',
        'idCatering',
        'waktuAmbil',
        'statusAmbil',
    ];

    protected $casts = [
        'waktuAmbil' => 'datetime',
        'statusAmbil' => 'boolean',
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