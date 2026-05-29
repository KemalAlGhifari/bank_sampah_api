<?php

namespace Database\Seeders;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepositSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get();

        Deposit::factory()
            ->count(50)
            ->state(function () use ($users) {
                return [
                    'user_id' => $users->random()->id,
                ];
            })
            ->create();
    }
}