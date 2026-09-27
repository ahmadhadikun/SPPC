<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QRCode extends Model
{
    use HasFactory;

    protected $table = 'qrcodes';

    protected $primaryKey = 'idQR';

    protected $fillable = [
        'idSantri',
        'kodeQR',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'idSantri', 'idSantri');
    }
}