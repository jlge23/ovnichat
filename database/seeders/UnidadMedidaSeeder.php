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
        UnidadMedida::create(['unidad' => 'No aplica', 'simbolo' => 'N/A']);
        UnidadMedida::create(['unidad' => 'Milímetro [0.001 metros]', 'simbolo' => 'mm']);
        UnidadMedida::create(['unidad' => 'Centímetro [0.01 metros]', 'simbolo' => 'cm']);
        UnidadMedida::create(['unidad' => 'Decímetro [0.1 metros]', 'simbolo' => 'dm']);
        UnidadMedida::create(['unidad' => 'Metros', 'simbolo' => 'm']);
        UnidadMedida::create(['unidad' => 'Decámetro [10 metros]', 'simbolo' => 'dam']);
        UnidadMedida::create(['unidad' => 'Hectómetro [100 metros]', 'simbolo' => 'hm']);
        UnidadMedida::create(['unidad' => 'Kilómetros [1000 metros]', 'simbolo' => 'km']);
        UnidadMedida::create(['unidad' => 'Milímetro cuadrado [0.000001 metros cuadrados]', 'simbolo' => 'mm²']);
        UnidadMedida::create(['unidad' => 'Centímetro cuadrado [0.0001 metros cuadrados]', 'simbolo' => 'cm²']);
        UnidadMedida::create(['unidad' => 'Decímetro cuadrado [0.01 metros cuadrados]', 'simbolo' => 'dm²']);
        UnidadMedida::create(['unidad' => 'Metros cuadrados', 'simbolo' => 'm²']);
        UnidadMedida::create(['unidad' => 'Decámetro cuadrado [10 metros cuadrados]', 'simbolo' => 'dam²']);
        UnidadMedida::create(['unidad' => 'Hectómetro cuadrado [100 metros cuadrados]', 'simbolo' => 'hm²']);
        UnidadMedida::create(['unidad' => 'Kilómetro cuadrado [1,000,000 metros cuadrados]', 'simbolo' => 'km²']);
        UnidadMedida::create(['unidad' => 'Nanogramos [1/1000 de un microgramo]', 'simbolo' => 'ng']);
        UnidadMedida::create(['unidad' => 'Microgramos [1/1000 de un miligramo]', 'simbolo' => 'µg']);
        UnidadMedida::create(['unidad' => 'Miligramos [1/1000 de un gramo]', 'simbolo' => 'mg']);
        UnidadMedida::create(['unidad' => 'Gramo [1/1000 de un kilogramo]', 'simbolo' => 'g']);
        UnidadMedida::create(['unidad' => 'Kilogra mos', 'simbolo' => 'kg']);
        UnidadMedida::create(['unidad' => 'Mililitro [1/1000]', 'simbolo' => 'ml']);
        UnidadMedida::create(['unidad' => 'Centilitro [1/100]', 'simbolo' => 'cl']);
        UnidadMedida::create(['unidad' => 'Decilitro [1/10]', 'simbolo' => 'dl']);
        UnidadMedida::create(['unidad' => 'Litro', 'simbolo' => 'l']);
        UnidadMedida::create(['unidad' => 'Decámetro cúbico [100 litros]', 'simbolo' => 'dam³']);
        UnidadMedida::create(['unidad' => 'Hectómetro cúbico [1000 litros]', 'simbolo' => 'hm³']);
        UnidadMedida::create(['unidad' => 'Kilómetro cúbico [1.000.000 litros]', 'simbolo' => 'km³']);
    }
}
