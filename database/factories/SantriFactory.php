<?php

namespace Database\Factories;

use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

class SantriFactory extends Factory
{
    protected $model = Santri::class;

    public function definition(): array
    {
        return [
            'NISN' => fake()->unique()->numerify('##########'),
            'namaSantri' => fake()->name(),
            'kamar' => 'Kamar ' . fake()->numberBetween(1, 10),
        ];
    }
}