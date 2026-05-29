<?php

// database/factories/RtFactory.php

namespace Database\Factories;

use App\Models\Rt;
use Illuminate\Database\Eloquent\Factories\Factory;

class RtFactory extends Factory
{
    protected $model = Rt::class;

    public function definition()
    {
        return [
            'name' => str_pad((string) fake()->numberBetween(1, 20), 2, '0', STR_PAD_LEFT),
            'rw' => str_pad((string) fake()->numberBetween(1, 10), 2, '0', STR_PAD_LEFT),
            'alamat' => fake()->address(),
        ];
    }
}
