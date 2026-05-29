<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'title' => fake()->randomElement([
                'Penjemputan Sampah',
                'Jadwal Setor',
                'Kunjungan Bank Sampah',
                'Pengecekan RT',
            ]),
            'date' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'time' => fake()->time('H:i:s'),
            'alamat' => fake()->address(),
            'notes' => fake()->sentence(12),
        ];
    }
}