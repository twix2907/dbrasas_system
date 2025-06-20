<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run()
    {
        // Crear 8 mesas
        for ($i = 1; $i <= 8; $i++) {
            Table::create([
                'number' => $i,
                'capacity' => rand(2, 6), // Capacidad aleatoria entre 2 y 6
                'status' => 'disponible',
            ]);
        }
    }
}