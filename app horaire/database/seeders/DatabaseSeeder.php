<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Teacher;
use App\Models\Type;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();


        // Type::factory()->create([
        //     'name' => "labo",
			
		// ]);

		

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
       $this->call([
            CourseSeeder::class,
            GroupSeeder::class,
            TeacherSeeder::class,
            ClassroomSeeder::class,
            SeancesSeeder::class,
            ClassroomSeanceSeeder::class,
            CourseSeanceSeeder::class,
            GroupSeanceSeeder::class,
            TeacherSeanceSeeder::class,
            TypeSeeder::class,
       ]);

    }
}
