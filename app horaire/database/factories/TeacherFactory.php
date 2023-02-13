<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */ 
   
    public function definition() 
    {
     
        return [ 
            'idTeacher' => fake()->numberBetween(1,1),
            'lastname' => fake()->lastName(),
            'firstname' => fake()->firstName(),
            'acronym' => fake()->word()
        ];
    }
   
    /*
    public function definition()
    {
        return [
            'idTeacher' => fake()->numberBetween(3, 20),
            'lastname' => Str::random(10),
            'firstname' => Str::random(10),
            'acronym' => Str::random(10),
        ];
    }
    */
}
