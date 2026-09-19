<?php

namespace Database\Seeders;

use App\Models\Lauk;
use Illuminate\Database\Seeder;

class LaukSeeder extends Seeder
{
    public function run(): void
    {
        Lauk::create([
            'nama' => 'Ayam Goreng',
            'stok' => 50,
            'deskripsi' => 'Ayam goreng',
        ]);

        Lauk::create([
            'nama' => 'Telur',
            'stok' => 30,
            'deskripsi' => 'Telur rebus',
        ]);
    }
}