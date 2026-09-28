<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'penggunas';

    protected $primaryKey = 'idPengguna';

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function laporans()
    {
        return $this->hasMany(
            Laporan::class,
            'idPengguna',
            'idPengguna'
        );
    }
}