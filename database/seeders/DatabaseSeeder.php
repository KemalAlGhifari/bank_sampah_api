<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Seeders\CategorySeeder;
use Database\Seeders\DepositSeeder;
use Database\Seeders\NotificationSeeder;
use Database\Seeders\ScheduleSeeder;
use Database\Seeders\WithdrawSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            DepositSeeder::class,
            WithdrawSeeder::class,
            ScheduleSeeder::class,
            NotificationSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
