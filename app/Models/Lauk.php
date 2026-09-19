<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengambilanLauk;

class Lauk extends Model
{
    protected $fillable = [
        'nama',
        'stok',
        'deskripsi',
    ];

    public function pengambilanLauks()
    {
        return $this->hasMany(PengambilanLauk::class);
    }
}