<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Seances')->insert([
            'idSeance' => 2,
            'heuredebut' => '8:00',
            'heureFin' => '10:00',
            'duree' => '2:00',
            'date' => '2022-11-22',
            
        ]);
        DB::table('Seances')->insert([
            'idSeance' => 3,
            'heuredebut' => '8:00',
            'heureFin' => '10:00',
            'duree' => '2:00',
            'date' => '2022-11-23',
            
        ]);
        DB::table('Seances')->insert([
            'idSeance' => 4,
            'heuredebut' => '8:00',
            'heureFin' => '10:00',
            'duree' => '2:00',
            'date' => '2022-11-24',
            
        ]);
        DB::table('Seances')->insert([
            'idSeance' => 5,
            'heuredebut' => '8:00',
            'heureFin' => '10:00',
            'duree' => '2:00',
            'date' => '2022-11-25',
            
        ]);
    }
}
