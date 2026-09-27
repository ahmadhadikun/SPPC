<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PengambilanLauk;

class Santri extends Model
{
       use HasFactory;
       
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
