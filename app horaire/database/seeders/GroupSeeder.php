<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Group::insertGroup('E13', 3);
        DB::table('groups')->insert([
            'idGroupe' => 'E13',
            'bloc' => 3,
            'created_at' => '18-06-12 10:34:09',
            'updated_at' => '18-06-12 10:34:09',
        ]);
        DB::table('groups')->insert([
            'idGroupe' => 'A121',
            'bloc' => 1,
            'created_at' => '18-06-12 10:34:09',
            'updated_at' => '18-06-12 10:34:09',
        ]);
        DB::table('groups')->insert([
            'idGroupe' => 'C112',
            'bloc' => 2,
            'created_at' => '18-06-12 10:34:09',
            'updated_at' => '18-06-12 10:34:09',
        ]);
        DB::table('groups')->insert([
            'idGroupe' => 'E12',
            'bloc' => 3,
            'created_at' => '18-06-12 10:34:09',
            'updated_at' => '18-06-12 10:34:09',
        ]);

    }
}
