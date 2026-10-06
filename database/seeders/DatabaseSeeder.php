<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Santri;
use App\Models\Catering;
use App\Models\PesertaCatering;
use App\Models\QrCode;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Pengguna Multi-Role
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@sppc.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $pengurus = User::create([
            'name' => 'Pengurus Catering',
            'email' => 'pengurus@sppc.com',
            'password' => Hash::make('password123'),
            'role' => 'catering',
        ]);

        $pengasuh = User::create([
            'name' => 'Pengasuh Pesantren',
            'email' => 'pengasuh@sppc.com',
            'password' => Hash::make('password123'),
            'role' => 'pengasuh',
        ]);

        // 2. Buat Data Santri Initial
        $santri1 = Santri::create([
            'nis' => '2026001',
            'nama_santri' => 'Ahmad Hadikun',
            'kamar' => 'Kamar A-01',
        ]);

        $santri2 = Santri::create([
            'nis' => '2026002',
            'nama_santri' => 'M. Irfan Attamami',
            'kamar' => 'Kamar A-02',
        ]);

        // 3. Buat Data QR Code Santri
        QrCode::create([
            'santri_id' => $santri1->idSantri,
            'kode_qr' => 'QR-SANTRI-2026001',
            'status' => true,
        ]);

        QrCode::create([
            'santri_id' => $santri2->idSantri,
            'kode_qr' => 'QR-SANTRI-2026002',
            'status' => true,
        ]);

        // 4. Registrasi Status Peserta Catering
        PesertaCatering::create([
            'santri_id' => $santri1->idSantri,
            'periode' => 'September 2026',
            'status' => true,
        ]);

        PesertaCatering::create([
            'santri_id' => $santri2->idSantri,
            'periode' => 'September 2026',
            'status' => true,
        ]);

        // 5. Buat Jadwal & Menu Catering Hari Ini
        Catering::create([
            'user_id' => $admin->id,
            'tanggal' => now()->toDateString(),
            'sesi' => 'Makan Siang',
            'menu' => 'Ayam Goreng, Sayur Sop, & Tempe',
        ]);
    }
}