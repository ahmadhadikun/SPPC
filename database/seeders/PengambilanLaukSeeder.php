<?php

namespace Database\Seeders;

use App\Models\PengambilanLauk;
use App\Models\Santri;
use App\Models\Lauk;
use Illuminate\Database\Seeder;

class PengambilanLaukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $santris = Santri::all();
        $lauks = Lauk::all();

        foreach ($santris as $santri) {
            PengambilanLauk::create([
                'santri_id' => $santri->id,
                'lauk_id' => $lauks->random()->id,
                'tanggal' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}