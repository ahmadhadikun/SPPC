<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Catering extends Model
{
    use HasFactory;

    protected $table = 'caterings';

    protected $primaryKey = 'idCatering';

    protected $fillable = [
        'tanggal',
        'sesi',
        'menu',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function presensis()
    {
        return $this->hasMany(
            Presensi::class,
            'idCatering',
            'idCatering'
        );
    }

    public function pengambilanLauks()
    {
        return $this->hasMany(
            PengambilanLauk::class,
            'idCatering',
            'idCatering'
        );
    }
}