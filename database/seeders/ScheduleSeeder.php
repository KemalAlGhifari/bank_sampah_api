<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get();

        Schedule::factory()
            ->count(30)
            ->state(function () use ($users) {
                $user = $users->random();

                return [
                    'user_id' => $user->id,
                    'rt_id' => $user->rt_id,
                    'type' => 'user',
                ];
            })
            ->create();
    }
}