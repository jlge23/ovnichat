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
        Embalaje::create(['embalaje' => 'N/A', 'descripcion' => 'No aplica']);
        Embalaje::create(['embalaje' => 'Caja', 'descripcion' => 'Caja de cartón']);
        Embalaje::create(['embalaje' => 'Bolsa', 'descripcion' => 'Bolsa de plástico']);
        Embalaje::create(['embalaje' => 'Saco', 'descripcion' => 'Saco de tela']);
        Embalaje::create(['embalaje' => 'Bulto', 'descripcion' => 'Bulto de plástico']);
        Embalaje::create(['embalaje' => 'Contenedor', 'descripcion' => 'Contenedor de plástico']);
        Embalaje::create(['embalaje' => 'Fardo', 'descripcion' => 'Fardo de tela']);
        Embalaje::create(['embalaje' => 'Barril', 'descripcion' => 'Barril de plástico']);
    }
}
