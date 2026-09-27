<?php

namespace Database\Factories;

use App\Models\Laporan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanFactory extends Factory
{
    protected $model = Laporan::class;

    public function definition(): array
    {
        return [
            'idPengguna' => null,
            'tanggal' => fake()->date(),
            'jenis' => fake()->randomElement([
                'Laporan Presensi',
                'Laporan Catering',
                'Laporan Pengambilan Lauk',
            ]),
        ];
    }
}