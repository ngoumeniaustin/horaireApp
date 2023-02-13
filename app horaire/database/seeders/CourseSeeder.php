<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('course')->insert([
            'idCourse' => 2,
            'acro' => 'PRJG5',
            'libelle' => 'gestion de projet 5',
            'typeCourse' => 'LABO',
        ]);
        DB::table('course')->insert([
            'idCourse' => 3,
            'acro' => 'MATH 1',
            'libelle' => 'Mathematique 1',
            'typeCourse' => 'THEORIE',
        ]);
        DB::table('course')->insert([
            'idCourse' => 4,
            'acro' => 'ANA1',
            'libelle' => 'Analyse 1',
            'typeCourse' => 'THEORIE',
        ]);
        DB::table('course')->insert([
            'idCourse' => 5,
            'acro' => 'DEV3',
            'libelle' => 'Developement 3',
            'typeCourse' => 'LABO',
        ]);
        DB::table('course')->insert([
            'idCourse' => 6,
            'acro' => 'SYS4',
            'libelle' => 'systeme 4',
            'typeCourse' => 'LABO',
        ]);
        DB::table('course')->insert([
            'idCourse' => 7,
            'acro' => 'DON3',
            'libelle' => 'persistance des donnees 3',
            'typeCourse' => 'THEORIE',
        ]);
    }
}
