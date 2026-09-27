<?php

namespace Database\Factories;
use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Santri>
 */
class SantriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => fake()->unique()->numerify('##########'),
            'nama' => fake()->name(),
            'kamar' => 'Kamar ' . fake()->numberBetween(1, 20),
            'kelas' => fake()->randomElement([
                'VII',
                'VIII',
                'IX',
                'X',
                'XI',
                'XII',
            ]),
        ];
    }
}