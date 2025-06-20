<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Administrador
        User::create([
            'name' => 'Admin',
            'pin_code' => '1234',
            'role' => 'admin',
        ]);

        // Meseros
        User::create([
            'name' => 'Mesero 1',
            'pin_code' => '5678',
            'role' => 'mesero',
        ]);
        
        User::create([
            'name' => 'Mesero 2',
            'pin_code' => '9012',
            'role' => 'mesero',
        ]);

        // Cajero
        User::create([
            'name' => 'Cajero',
            'pin_code' => '3456',
            'role' => 'cajero',
        ]);
        
        // Cocinero
        User::create([
            'name' => 'Cocinero',
            'pin_code' => '7890',
            'role' => 'cocinero',
        ]);
    }
}