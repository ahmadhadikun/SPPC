<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Santri;
use App\Models\Lauk;

class PengambilanLauk extends Model
{
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function lauk()
    {
        return $this->belongsTo(Lauk::class);
    }
}
 