<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('commandes')->insert([
            'numero' => 'N°'.date('d-m').random_int(1, 200),
            'model' => 'Chemise',
            'description' => 'Chemise col Coupé',
            'mesure' => 'lien de l\'image',
            'delais' => date('Y/m/d'),
            'total' => 5000,
            'reste' => 2000,
            'user_id' => 1,
            'client_id' => 1
        ]);
        DB::table('commandes')->insert([
            'numero' => 'N°'.date('d-m').random_int(1,200),
            'model' => 'Robe',
            'description' => 'Description du model',
            'mesure' => 'lien de l\'image',
            'delais' => date('Y/m/d'),
            'total' => 5000,
            'reste' => 2000,
            'user_id' => 1,
            'client_id' => 1
        ]);
    }
}
