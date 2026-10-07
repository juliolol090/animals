<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $species = [
            ['name' => 'leon'],
            ['name' => 'elefante'],
            ['name' => 'pez payaso'],
            ['name' => 'gato'],
            ['name' => 'perro'],
            ['name' => 'conejo'],
            ['name' => 'caballo'],
            ['name' => 'oso'],
            ['name' => 'pajaro'],
            ['name' => 'tortuga'],
        ];

        foreach ($species as $specie) {
            DB::insert(
                'INSERT INTO species (name) VALUES (?)',
                [$specie['name']]
            );
        }

        
    }
}
