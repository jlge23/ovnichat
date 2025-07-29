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
            ['codigo' => 'nuevo', 'estado' => 'Nuevo contacto'],
            ['codigo' => 'interesado', 'estado' => 'Interesado'],
            ['codigo' => 'calificado', 'estado' => 'Calificado'],
            ['codigo' => 'convertido', 'estado' => 'Convertido'],
            ['codigo' => 'sin_interes', 'estado' => 'Sin interés'],
        ]);

    }
}
