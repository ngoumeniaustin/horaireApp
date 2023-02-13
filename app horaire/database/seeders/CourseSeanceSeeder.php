<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('CourseSeance')->insert([
            'id' => 2,
            'idCourse' => 3,
            'idSeance' => 2,
            
        ]);
        DB::table('CourseSeance')->insert([
            'id' => 3,
            'idCourse' => 2,
            'idSeance' => 2,
            
        ]);
        DB::table('CourseSeance')->insert([
            'id' => 4,
            'idCourse' => 5,
            'idSeance' => 3,
            
        ]);
    }
}
