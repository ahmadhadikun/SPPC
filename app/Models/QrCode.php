<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $table = 'qrcode';

    protected $primaryKey = 'id_qrcode';

    protected $fillable = [
        'id_santri',
        'kode_qr',
    ];

    // Relasi ke Santri
    public function santri()
    {
        return $this->belongsTo(
            Santri::class,
            'id_santri',
            'id_santri'
        );
    }
}
