<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('teacher')->insert([
            'idTeacher' => 2,
            'lastname' => 'John',
            'firstname' => 'Doe',
            'acronym' => 'JDO',
        ]);
        DB::table('teacher')->insert([
            'idTeacher' => 3,
            'lastname' => 'johnna ',
            'firstname' => 'toead',
            'acronym' => 'JTO',
        ]);
        DB::table('teacher')->insert([
            'idTeacher' => 4,
            'lastname' => 'John',
            'firstname' => 'Cena',
            'acronym' => 'JCE',
        ]);
        DB::table('teacher')->insert([
            'idTeacher' => 5,
            'lastname' => 'Kye',
            'firstname' => 'Dominguez',
            'acronym' => 'KDO',
        ]);
        DB::table('teacher')->insert([
            'idTeacher' => 6,
            'lastname' => 'Brooke',
            'firstname' => 'Acosta',
            'acronym' => 'BAC',
        ]);
        DB::table('teacher')->insert([
            'idTeacher' => 7,
            'lastname' => 'Ailish',
            'firstname' => 'Mcallum',
            'acronym' => 'AMC',
        ]);
        DB::table('teacher')->insert([
            'idTeacher' => 8,
            'lastname' => 'Luis',
            'firstname' => 'Maddox',
            'acronym' => 'LMA',
        ]);

    }
}
