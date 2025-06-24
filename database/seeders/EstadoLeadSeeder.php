<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoLead;

class EstadoLeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EstadoLead::insert([
            ['codigo' => 'nuevo', 'nombre' => 'Nuevo contacto'],
            ['codigo' => 'interesado', 'nombre' => 'Interesado'],
            ['codigo' => 'calificado', 'nombre' => 'Calificado'],
            ['codigo' => 'convertido', 'nombre' => 'Convertido'],
            ['codigo' => 'sin_interes', 'nombre' => 'Sin interés'],
        ]);

    }
}
