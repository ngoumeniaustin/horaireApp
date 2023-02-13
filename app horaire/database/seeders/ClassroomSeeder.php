<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ClassRoom;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Classroom')->insert([
            'idClassroom' => 2,
            'classType' => 'LABO',
            'places' => 20,
            'examplaces' => 20,
        ]);
        DB::table('Classroom')->insert([
            'idClassroom' => 3,
            'classType' => 'AUDITOIRE',
            'places' => 400,
            'examplaces' => 400,
        ]);
        DB::table('Classroom')->insert([
            'idClassroom' => 4,
            'classType' => 'THEORIE',
            'places' => 30,
            'examplaces' => 30,
        ]);
    }
}
