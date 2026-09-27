<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesertaCatering extends Model
{
    use HasFactory;

    protected $table = 'peserta_caterings';

    protected $primaryKey = 'idPeserta';

    protected $fillable = [
        'idSantri',
        'periode',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function santri()
    {
        return $this->belongsTo(
            Santri::class,
            'idSantri',
            'idSantri'
        );
    }
}