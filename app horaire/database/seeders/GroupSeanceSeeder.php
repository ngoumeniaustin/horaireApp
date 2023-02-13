<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('GroupSeance')->insert([
            'id' => 1,
            'idGroupe' => 'E13',
            'idSeance' => 2,
            
        ]);
        DB::table('GroupSeance')->insert([
            'id' => 3,
            'idGroupe' => 'A121',
            'idSeance' => 3,
            
        ]);
        DB::table('GroupSeance')->insert([
            'id' => 4,
            'idGroupe' => 'C112',
            'idSeance' => 4,
            
        ]);
        
        
    }
}
