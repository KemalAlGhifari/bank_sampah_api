<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            ['name' => 'Botol Plastik', 'price_per_kg' => 1500],
            ['name' => 'Plastik Campur', 'price_per_kg' => 1200],
            ['name' => 'Kardus', 'price_per_kg' => 800],
            ['name' => 'Kertas HVS', 'price_per_kg' => 1000],
            ['name' => 'Kaleng Aluminium', 'price_per_kg' => 3500],
            ['name' => 'Kaleng Besi', 'price_per_kg' => 1800],
            ['name' => 'Botol Kaca', 'price_per_kg' => 900],
            ['name' => 'Minyak Jelantah', 'price_per_kg' => 2500],
            ['name' => 'Pakaian Bekas', 'price_per_kg' => 700],
            ['name' => 'Elektronik Kecil', 'price_per_kg' => 4000],
        ];

        $category = fake()->unique()->randomElement($categories);

        return [
            'name' => $category['name'],
            'price_per_kg' => $category['price_per_kg'],
        ];
    }
}