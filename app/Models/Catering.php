<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catering extends Model
{
    use HasFactory;

    protected $table = 'catering';

    protected $primaryKey = 'id_catering';

    protected $fillable = [
        'tanggal',
        'waktu',
        'menu',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }
    public function presensis()
    {
        return $this->hasMany(
            \App\Models\Presensi::class,
            'id_catering',
            'id_catering'
        );
    }
}
