<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_catering',
        'total_hadir',
        'total_tidak_hadir',
    ];

    public function catering()
    {
        return $this->belongsTo(
            Catering::class,
            'id_catering',
            'id_catering'
        );
    }
}
