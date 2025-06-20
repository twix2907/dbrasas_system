<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'pin_code' => $this->faker->numerify('######'),
            'role' => $this->faker->randomElement(['mesero', 'cajero', 'admin', 'cocinero']),
        ];
    }
}