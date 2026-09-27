<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Santri;
use App\Models\Lauk;

class PengambilanLauk extends Model
{
      use HasFactory;

    protected $fillable = [
        'santri_id',
        'lauk_id',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function lauk()
    {
        return $this->belongsTo(Lauk::class);
    }
}
