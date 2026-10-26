<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Elektronik',
                'ATK',
                'Furniture',
                'Peralatan Lab',
                'Perlengkapan Kantor',
            ]),
            'description' => fake()->sentence(),
        ];
    }
}