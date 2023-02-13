<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Type;
class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        DB::table('types')->insert([
            'id'=>1,
            'name' => 'THEORIE'
        ]);
        DB::table('types')->insert([
            'id'=>2,
            'name' => 'LABO'
        ]);
    }
}