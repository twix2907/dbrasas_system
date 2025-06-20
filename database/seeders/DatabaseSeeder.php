<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            TableSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}