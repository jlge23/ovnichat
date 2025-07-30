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
        $unidades = [
            ['No aplica', 'N/A'],
            ['Milímetro [0.001 metros]', 'mm'],
            ['Centímetro [0.01 metros]', 'cm'],
            ['Decímetro [0.1 metros]', 'dm'],
            ['Metros', 'm'],
            ['Decámetro [10 metros]', 'dam'],
            ['Hectómetro [100 metros]', 'hm'],
            ['Kilómetros [1000 metros]', 'km'],
            ['Milímetro cuadrado [0.000001 metros cuadrados]', 'mm²'],
            ['Centímetro cuadrado [0.0001 metros cuadrados]', 'cm²'],
            ['Decímetro cuadrado [0.01 metros cuadrados]', 'dm²'],
            ['Metros cuadrados', 'm²'],
            ['Decámetro cuadrado [10 metros cuadrados]', 'dam²'],
            ['Hectómetro cuadrado [100 metros cuadrados]', 'hm²'],
            ['Kilómetro cuadrado [1,000,000 metros cuadrados]', 'km²'],
            ['Nanogramos [1/1000 de un microgramo]', 'ng'],
            ['Microgramos [1/1000 de un miligramo]', 'µg'],
            ['Miligramos [1/1000 de un gramo]', 'mg'],
            ['Gramo [1/1000 de un kilogramo]', 'g'],
            ['Kilogra mos', 'kg'],
            ['Mililitro [1/1000]', 'ml'],
            ['Centilitro [1/100]', 'cl'],
            ['Decilitro [1/10]', 'dl'],
            ['Litro', 'l'],
            ['Decámetro cúbico [100 litros]', 'dam³'],
            ['Hectómetro cúbico [1000 litros]', 'hm³'],
            ['Kilómetro cúbico [1.000.000 litros]', 'km³']
        ];

        foreach ($unidades as $unidad) {
            UnidadMedida::create(['nombre' => $unidad[0], 'simbolo' => $unidad[1]]);

        }
    }
}
