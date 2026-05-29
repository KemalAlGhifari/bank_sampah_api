<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Deposit;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepositFactory extends Factory
{
    protected $model = Deposit::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'total_kg' => 0,
            'total_amount' => 0,
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Deposit $deposit) {
            $categories = Category::query()
                ->inRandomOrder()
                ->take(fake()->numberBetween(2, 4))
                ->get();

            if ($categories->isEmpty()) {
                return;
            }

            $totalKg = 0;
            $totalAmount = 0;

            foreach ($categories as $category) {
                $weight = fake()->randomFloat(2, 0.5, 15);
                $subtotal = round($weight * (float) $category->price_per_kg, 2);

                $deposit->depositItems()->create([
                    'category_id' => $category->id,
                    'weight' => $weight,
                    'subtotal' => $subtotal,
                ]);

                $totalKg += $weight;
                $totalAmount += $subtotal;
            }

            $deposit->update([
                'total_kg' => round($totalKg, 2),
                'total_amount' => round($totalAmount, 2),
            ]);
        });
    }
}