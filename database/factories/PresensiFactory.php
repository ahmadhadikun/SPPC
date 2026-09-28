<?php

namespace Database\Factories;

use App\Models\Presensi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PresensiFactory extends Factory
{
    protected $model = Presensi::class;

    public function definition(): array
    {
        return [
            'idSantri' => null,
            'idCatering' => null,
            'tanggal' => fake()->date(),
            'waktu' => fake()->time(),
            'status' => fake()->randomElement([
                'Hadir',
                'Tidak Hadir',
            ]),
        ];
    }
}