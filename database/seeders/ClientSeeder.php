<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
            'nom' => 'Yao Akissi',
            'phone' => '0102030405',
            'image' => 'lien de l\'image',
            'sexe' => 'h',
        ]);
        DB::table('clients')->insert([
            'nom' => 'Kouadio Hervé',
            'phone' => '0102030405',
            'image' => 'lien de l\'image',
            'sexe' => 'f',
        ]);
    }
}
