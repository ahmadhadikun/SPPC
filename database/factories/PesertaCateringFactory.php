<?php

namespace Database\Factories;

use App\Models\PesertaCatering;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesertaCateringFactory extends Factory
{
    protected $model = PesertaCatering::class;

    public function definition(): array
    {
        return [
            'idSantri' => null,
            'periode' => fake()->randomElement([
                '2026/2027',
                '2027/2028',
            ]),
            'status' => true,
        ];
    }
}