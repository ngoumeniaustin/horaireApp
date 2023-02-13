<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRoom>
 */
class ClassRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'idClassRoom' => fake()->unique()->bothify("?###"),
            'classType' => 'Labo',
            'places' => fake()->numberBetween(3, 10),
            'examPlaces' => fake()->numberBetween(6, 10)
        ];
    }
}
