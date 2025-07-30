<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas = [
            'NO APLICA',
            'GLORIA',
            'CAFFAS',
            'AREL',
            'TERRA HISPANICA',
            'PAFIA',
        ];

        foreach ($marcas as $marca) {
            Marca::create(['nombre' => $marca]);
        }
    }
}
