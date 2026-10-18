<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengambilanLauk extends Model
{
    use HasFactory;

    protected $table = 'pengambilan_lauks';

    protected $primaryKey = 'idPengambilan';

    protected $fillable = [
        'santri_id',
        'catering_id',
        'user_id',
        'waktu_ambil',
        'status_ambil',
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

    // Relasi ke User (Pengurus)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}