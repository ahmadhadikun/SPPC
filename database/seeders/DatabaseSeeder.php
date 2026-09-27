<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use App\Models\Santri;
use App\Models\Catering;
use App\Models\PesertaCatering;
use App\Models\QRCode;
use App\Models\Presensi;
use App\Models\PengambilanLauk;
use App\Models\Laporan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. DATA PENGGUNA
        // ==========================================

        $penggunas = Pengguna::factory()->count(3)->create();

        // ==========================================
        // 2. DATA SANTRI
        // ==========================================

        $santris = Santri::factory()->count(10)->create();

        // ==========================================
        // 3. DATA CATERING
        // ==========================================

        $caterings = Catering::factory()->count(5)->create();

        // ==========================================
        // 4. PESERTA CATERING
        // ==========================================

        foreach ($santris as $santri) {
            PesertaCatering::factory()->create([
                'idSantri' => $santri->idSantri,
            ]);
        }

        // ==========================================
        // 5. QR CODE
        // ==========================================

        foreach ($santris as $santri) {
            QRCode::factory()->create([
                'idSantri' => $santri->idSantri,
            ]);
        }

        // ==========================================
        // 6. PRESENSI
        // ==========================================

        foreach ($santris as $santri) {
            Presensi::factory()->create([
                'idSantri' => $santri->idSantri,
                'idCatering' => $caterings->random()->idCatering,
            ]);
        }

        // ==========================================
        // 7. PENGAMBILAN LAUK
        // ==========================================

        foreach ($santris as $santri) {
            PengambilanLauk::factory()->create([
                'idSantri' => $santri->idSantri,
                'idCatering' => $caterings->random()->idCatering,
            ]);
        }

        // ==========================================
        // 8. LAPORAN
        // ==========================================

        foreach ($penggunas as $pengguna) {
            Laporan::factory()->create([
                'idPengguna' => $pengguna->idPengguna,
            ]);
        }
    }
}