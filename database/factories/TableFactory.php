<?php

namespace Database\Factories;

use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class TableFactory extends Factory
{
    protected $model = Table::class;

    public function definition()
    {
        return [
            'description' => $this->faker->sentence,
            'number_chairs' => $this->faker->numberBetween(1, 10),
        ];
    }
}

