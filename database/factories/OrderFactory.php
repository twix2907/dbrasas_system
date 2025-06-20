<?php
namespace Database\Factories;

use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'table_id' => Table::factory(),
            'user_id' => User::factory()->state(['role' => 'mesero']),
            'status' => 'activa',
            'total' => 0,
        ];
    }
}