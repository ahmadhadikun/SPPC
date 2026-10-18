<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Presensi;

class Catering extends Model
{
    use HasFactory;

    protected $table = 'caterings';

    protected $primaryKey = 'idCatering';

    protected $fillable = [
        'user_id',
        'tanggal',
        'sesi',
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
            Presensi::class,
            'catering_id',
            'idCatering'
        );
    }
}
