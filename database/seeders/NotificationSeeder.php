<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get();

        Notification::factory()
            ->count(100)
            ->state(function () use ($users) {
                return [
                    'user_id' => $users->random()->id,
                ];
            })
            ->create();
    }
}