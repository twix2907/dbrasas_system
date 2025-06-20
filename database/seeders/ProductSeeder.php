<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Parrilla (categoría 1)
        Product::create([
            'name' => 'Anticucho',
            'description' => 'Brocheta de corazón de res a la parrilla',
            'price' => 15.00,
            'category_id' => 1,
            'status' => 'disponible',
        ]);
        
        Product::create([
            'name' => 'Pollo a la Parrilla',
            'description' => '1/4 de pollo a la parrilla con papas',
            'price' => 18.50,
            'category_id' => 1,
            'status' => 'disponible',
        ]);

        // Frituras (categoría 2)
        Product::create([
            'name' => 'Chicharrón de Cerdo',
            'description' => 'Porción de chicharrón de cerdo con yuca',
            'price' => 22.00,
            'category_id' => 2,
            'status' => 'disponible',
        ]);

        // Bebidas (categoría 3)
        Product::create([
            'name' => 'Inca Kola',
            'description' => 'Gaseosa personal 500ml',
            'price' => 5.00,
            'category_id' => 3,
            'status' => 'disponible',
        ]);
        
        Product::create([
            'name' => 'Chicha Morada',
            'description' => 'Vaso de chicha morada natural',
            'price' => 6.50,
            'category_id' => 3,
            'status' => 'disponible',
        ]);

        // Postres (categoría 4)
        Product::create([
            'name' => 'Mazamorra Morada',
            'description' => 'Postre tradicional peruano',
            'price' => 8.00,
            'category_id' => 4,
            'status' => 'disponible',
        ]);

        // Entradas (categoría 5)
        Product::create([
            'name' => 'Tequeños',
            'description' => '6 unidades de tequeños de queso',
            'price' => 12.00,
            'category_id' => 5,
            'status' => 'disponible',
        ]);
    }
}