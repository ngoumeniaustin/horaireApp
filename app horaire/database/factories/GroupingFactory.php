<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grouping>
 */
class GroupingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'idRegroupement' => fake()->regexify('[A-Z]'),
            'idGroup' => [fake()->regexify('[A-Z]{1}[1-9]{4}')],

        ];
    }
}
