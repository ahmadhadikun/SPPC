<?php

namespace Database\Factories;

use App\Models\PengambilanLauk;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengambilanLaukFactory extends Factory
{
    protected $model = PengambilanLauk::class;

    public function definition(): array
    {
        return [
            'idSantri' => null,
            'idCatering' => null,
            'waktuAmbil' => fake()->dateTime(),
            'statusAmbil' => true,
        ];
    }
}