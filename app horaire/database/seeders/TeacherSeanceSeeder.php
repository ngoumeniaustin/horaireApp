<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('TeacherSeance')->insert([
            'id' => 1,
            'idTeacher' => 2,
            'idseance' => 2,           
        ]);
        DB::table('TeacherSeance')->insert([
            'id' => 2,
            'idTeacher' => 3,
            'idseance' => 3,           
        ]);
        DB::table('TeacherSeance')->insert([
            'id' => 3,
            'idTeacher' => 3,
            'idseance' => 3,           
        ]);
    }
}
