<?php

// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\Rt;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $rts = Rt::factory()->count(10)->create();

        User::factory()
            ->count(99)
            ->state(function () use ($rts) {
                return [
                    'rt_id' => $rts->random()->id,
                ];
            })
            ->create();

        User::factory()->state([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '089699773627',
            'nik' => '0000000000000001',
            'alamat' => 'Alamat Admin',
            'role' => 'admin',
            'total_kg' => 0,
            'saldo' => 0,
            'rt_id' => $rts->first()->id,
        ])->create();
    }
}
