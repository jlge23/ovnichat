<?php

namespace Database\Seeders;

use App\Models\Embalaje;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmbalajeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $embalajes = [
            ['nombre' => 'N/A', 'descripcion' => 'No aplica'],
            ['nombre' => 'Caja', 'descripcion' => 'Caja de cartón'],
            ['nombre' => 'Bolsa', 'descripcion' => 'Bolsa de plástico'],
            ['nombre' => 'Saco', 'descripcion' => 'Saco de tela'],
            ['nombre' => 'Bulto', 'descripcion' => 'Bulto de plástico'],
            ['nombre' => 'Contenedor', 'descripcion' => 'Contenedor de plástico'],
            ['nombre' => 'Fardo', 'descripcion' => 'Fardo de tela'],
            ['nombre' => 'Barril', 'descripcion' => 'Barril de plástico'],
        ];

        foreach ($embalajes as $embalaje) {
            Embalaje::create(['nombre' => $embalaje['nombre'], 'descripcion' => $embalaje['descripcion']]);
        }
    }
}
