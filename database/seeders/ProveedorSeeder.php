<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //['nombre','contacto','telefono','email'];
        Proveedor::create(['proveedor' => 'NO APLICA','contacto' => '','telefono' => '','email' => '']);
        Proveedor::create(['proveedor' => 'THE CARMEN ALIMENTOS C.A.','contacto' => 'S/N','telefono' => '+584241954394','email' => 'thecarmenalimentos@gmail.com']);
    }
}
