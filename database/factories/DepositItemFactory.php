<?php

namespace Database\Factories;

use App\Models\DepositItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepositItemFactory extends Factory
{
    protected $model = DepositItem::class;

    public function definition(): array
    {
        return [
            'deposit_id' => 1,
            'category_id' => 1,
            'weight' => fake()->randomFloat(2, 0.5, 15),
            'subtotal' => fake()->randomFloat(2, 500, 50000),
        ];
    }
}