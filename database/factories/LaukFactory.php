<?php

namespace Database\Factories;

use App\Models\Lauk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lauk>
 */
class LaukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement([
                'Ayam Goreng',
                'Ayam Bakar',
                'Telur Rebus',
                'Telur Goreng',
                'Ikan Goreng',
                'Ikan Bakar',
                'Tempe',
                'Tahu',
            ]),

            'stok' => fake()->numberBetween(10, 100),

            'deskripsi' => fake()->sentence(),
        ];
    }
}