<?php

namespace Database\Seeders;

use App\Models\UnidadMedida;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnidadMedida::create(['nombre' => 'No aplica', 'simbolo' => 'N/A']);
        UnidadMedida::create(['nombre' => 'Milímetro [0.001 metros]', 'simbolo' => 'mm']);
        UnidadMedida::create(['nombre' => 'Centímetro [0.01 metros]', 'simbolo' => 'cm']);
        UnidadMedida::create(['nombre' => 'Decímetro [0.1 metros]', 'simbolo' => 'dm']);
        UnidadMedida::create(['nombre' => 'Metros', 'simbolo' => 'm']);
        UnidadMedida::create(['nombre' => 'Decámetro [10 metros]', 'simbolo' => 'dam']);
        UnidadMedida::create(['nombre' => 'Hectómetro [100 metros]', 'simbolo' => 'hm']);
        UnidadMedida::create(['nombre' => 'Kilómetros [1000 metros]', 'simbolo' => 'km']);
        UnidadMedida::create(['nombre' => 'Milímetro cuadrado [0.000001 metros cuadrados]', 'simbolo' => 'mm²']);
        UnidadMedida::create(['nombre' => 'Centímetro cuadrado [0.0001 metros cuadrados]', 'simbolo' => 'cm²']);
        UnidadMedida::create(['nombre' => 'Decímetro cuadrado [0.01 metros cuadrados]', 'simbolo' => 'dm²']);
        UnidadMedida::create(['nombre' => 'Metros cuadrados', 'simbolo' => 'm²']);
        UnidadMedida::create(['nombre' => 'Decámetro cuadrado [10 metros cuadrados]', 'simbolo' => 'dam²']);
        UnidadMedida::create(['nombre' => 'Hectómetro cuadrado [100 metros cuadrados]', 'simbolo' => 'hm²']);
        UnidadMedida::create(['nombre' => 'Kilómetro cuadrado [1,000,000 metros cuadrados]', 'simbolo' => 'km²']);
        UnidadMedida::create(['nombre' => 'Nanogramos [1/1000 de un microgramo]', 'simbolo' => 'ng']);
        UnidadMedida::create(['nombre' => 'Microgramos [1/1000 de un miligramo]', 'simbolo' => 'µg']);
        UnidadMedida::create(['nombre' => 'Miligramos [1/1000 de un gramo]', 'simbolo' => 'mg']);
        UnidadMedida::create(['nombre' => 'Gramo [1/1000 de un kilogramo]', 'simbolo' => 'g']);
        UnidadMedida::create(['nombre' => 'Kilogra mos', 'simbolo' => 'kg']);
        UnidadMedida::create(['nombre' => 'Mililitro [1/1000]', 'simbolo' => 'ml']);
        UnidadMedida::create(['nombre' => 'Centilitro [1/100]', 'simbolo' => 'cl']);
        UnidadMedida::create(['nombre' => 'Decilitro [1/10]', 'simbolo' => 'dl']);
        UnidadMedida::create(['nombre' => 'Litro', 'simbolo' => 'l']);
        UnidadMedida::create(['nombre' => 'Decámetro cúbico [100 litros]', 'simbolo' => 'dam³']);
        UnidadMedida::create(['nombre' => 'Hectómetro cúbico [1000 litros]', 'simbolo' => 'hm³']);
        UnidadMedida::create(['nombre' => 'Kilómetro cúbico [1.000.000 litros]', 'simbolo' => 'km³']);
    }
}
