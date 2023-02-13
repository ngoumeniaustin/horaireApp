<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;


class ClassroomSeanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ClassroomSeance')->insert([
            'id' => 2,
            'idClassroom' => 1,
            'idSeance' => 2,            
        ]);
        DB::table('ClassroomSeance')->insert([
            'id' => 3,
            'idClassroom' => 2,
            'idSeance' => 5,            
        ]);
        DB::table('ClassroomSeance')->insert([
            'id' => 4,
            'idClassroom' => 3,
            'idSeance' => 3,            
        ]);
    }
}
