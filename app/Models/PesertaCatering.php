<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaCatering extends Model
{
    use HasFactory;

    protected $table = 'peserta_caterings';
    protected $primaryKey = 'idPeserta';

    protected $fillable = [
        'santri_id',
        'periode',
        'status',
    ];

    // Relasi ke Santri
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id', 'idSantri');
    }
}