<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Database\Seeder;

class WithdrawSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->get();

        Withdraw::factory()
            ->count(30)
            ->state(function () use ($users) {
                return [
                    'user_id' => $users->random()->id,
                ];
            })
            ->create();
    }
}