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
        $proveedores = [
            ['nombre' => 'NO APLICA', 'contacto' => '', 'telefono' => '', 'email' => ''],
            ['nombre' => 'THE CARMEN ALIMENTOS C.A.', 'contacto' => 'S/N', 'telefono' => '+584241954394', 'email' => 'thecarmenalimentos@gmail.com']
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create([
                'nombre' => $proveedor['nombre'],
                'contacto' => $proveedor['contacto'],
                'telefono' => $proveedor['telefono'],
                'email' => $proveedor['email']
            ]);
        }
    }
}
