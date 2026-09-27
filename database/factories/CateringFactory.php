<?php

namespace Database\Factories;

use App\Models\Catering;
use Illuminate\Database\Eloquent\Factories\Factory;

class CateringFactory extends Factory
{
    protected $model = Catering::class;

    public function definition(): array
    {
        return [
            'tanggal' => fake()->date(),
            'sesi' => fake()->randomElement([
                'Pagi',
                'Siang',
                'Malam',
            ]),
            'menu' => fake()->randomElement([
                'Nasi Ayam',
                'Nasi Ikan',
                'Nasi Telur',
                'Nasi Sayur',
            ]),
        ];
    }
}