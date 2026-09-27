<?php

namespace Database\Factories;

use App\Models\Santri;
use App\Models\Lauk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengambilanLauk>
 */
class PengambilanLaukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'santri_id' => Santri::factory(),
            'lauk_id' => Lauk::factory(),
            'tanggal' => fake()->date(),
        ];
    }
}