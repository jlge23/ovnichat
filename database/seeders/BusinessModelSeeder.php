<?php

namespace Database\Seeders;

use App\Models\BusinessModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            ['name' => 'consultas_medicas', 'description' => 'Agenda y gestión de consultas médicas'],
            ['name' => 'reservas', 'description' => 'Reservas de espacios, citas o servicios'],
            ['name' => 'atencion_cliente', 'description' => 'Soporte postventa y atención a usuarios'],
            ['name' => 'concesionaria', 'description' => 'Venta, test drives y financiamiento de vehículos'],
            ['name' => 'cafeteria_y_mas', 'description' => 'Cafetería, panadería, eventos y membresías'],
            ['name' => 'salon_belleza', 'description' => 'Servicios de belleza, estética y promociones'],
            ['name' => 'agencia_viajes', 'description' => 'Paquetes turísticos, asesoría y reservas'],
            ['name' => 'bienes_raices', 'description' => 'Compra, arriendo y publicación de propiedades'],
            ['name' => 'ventas_productos', 'description' => 'Catálogo, ventas, envíos y soporte postventa'],
            ['name' => 'promociones', 'description' => 'Gestión de campañas, cupones y descuentos']
        ];

        foreach ($models as $data) {
            \App\Models\BusinessModel::create($data);
        }

    }
}
