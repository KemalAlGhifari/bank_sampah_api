<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'title' => fake()->randomElement([
                'Saldo Bertambah',
                'Setoran Diproses',
                'Withdraw Diperiksa',
                'Jadwal Baru Tersedia',
            ]),
            'message' => fake()->sentence(16),
            'is_read' => fake()->boolean(35),
        ];
    }
}