<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Santri;
use App\Models\Catering;
use App\Models\QrCode;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Support\Str;

echo "Santri count: " . Santri::count() . "\n";
echo "Catering count: " . Catering::count() . "\n";
echo "QR count: " . QrCode::count() . "\n";
echo "Presensi count: " . Presensi::count() . "\n";

// Ensure at least 1 test santri with QR code exists
if (Santri::count() === 0) {
    $s = Santri::create([
        'nama' => 'Ahmad Fulan',
        'kelas' => '10A',
        'asrama' => 'Al-Falah',
    ]);
    QrCode::create([
        'id_santri' => $s->id_santri,
        'kode_qr' => 'QR-' . Str::uuid(),
    ]);
    echo "Created sample santri: " . $s->nama . "\n";
}

// Ensure today's catering exists for testing
$todayCatering = Catering::whereDate('tanggal', Carbon::today())->where('waktu', 'Siang')->first();
if (!$todayCatering) {
    $c = Catering::create([
        'tanggal' => Carbon::today(),
        'waktu' => 'Siang',
        'menu' => 'Ayam Goreng & Sayur Sop',
    ]);
    echo "Created sample catering for today (Siang): " . $c->menu . "\n";
}

$sampleSantri = Santri::with('qrCode')->first();
echo "Sample Santri: " . $sampleSantri->nama . " (QR: " . $sampleSantri->qrCode?->kode_qr . ")\n";
