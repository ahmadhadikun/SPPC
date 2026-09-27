<?php

namespace Database\Factories;

use App\Models\QRCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class QRCodeFactory extends Factory
{
    protected $model = QRCode::class;

    public function definition(): array
    {
        return [
            'idSantri' => null,
            'kodeQR' => fake()->unique()->bothify('QR-####-????'),
            'status' => true,
        ];
    }
}