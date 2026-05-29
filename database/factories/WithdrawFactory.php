<?php

namespace Database\Factories;

use App\Models\Withdraw;
use Illuminate\Database\Eloquent\Factories\Factory;

class WithdrawFactory extends Factory
{
    protected $model = Withdraw::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'amount' => fake()->randomFloat(2, 10000, 1000000),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}