<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengambilanLauk;

class Santri extends Model
{
    protected $fillable = [
        'nis',
        'nama',
        'kamar',
        'kelas',
    ];
    
    public function pengambilanLauks()
    {
        return $this->hasMany(PengambilanLauk::class);
    }
}
