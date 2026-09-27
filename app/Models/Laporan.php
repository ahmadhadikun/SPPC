<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $primaryKey = 'idLaporan';

    protected $fillable = [
        'idPengguna',
        'tanggal',
        'jenis',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pengguna()
    {
        return $this->belongsTo(
            Pengguna::class,
            'idPengguna',
            'idPengguna'
        );
    }
}