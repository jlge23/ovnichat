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
        Marca::create(['marca' => 'NO APLICA']);
        Marca::create(['marca' => 'GLORIA']);
        Marca::create(['marca' => 'CAFFAS']);
        Marca::create(['marca' => 'AREL']);
        Marca::create(['marca' => 'TERRA HISPANICA']);
        Marca::create(['marca' => 'PÁFIA']);
    }
}
