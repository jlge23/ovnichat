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
        Embalaje::create(['tipo_embalaje' => 'N/A', 'descripcion' => 'No aplica']);
        Embalaje::create(['tipo_embalaje' => 'Caja', 'descripcion' => 'Caja de cartón']);
        Embalaje::create(['tipo_embalaje' => 'Bolsa', 'descripcion' => 'Bolsa de plástico']);
        Embalaje::create(['tipo_embalaje' => 'Saco', 'descripcion' => 'Saco de tela']);
        Embalaje::create(['tipo_embalaje' => 'Bulto', 'descripcion' => 'Bulto de plástico']);
        Embalaje::create(['tipo_embalaje' => 'Contenedor', 'descripcion' => 'Contenedor de plástico']);
        Embalaje::create(['tipo_embalaje' => 'Fardo', 'descripcion' => 'Fardo de tela']);
        Embalaje::create(['tipo_embalaje' => 'Barril', 'descripcion' => 'Barril de plástico']);
    }
}
