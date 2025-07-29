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
            ['modelonegocio' => 'consultas_medicas', 'description' => 'Agenda y gestión de consultas médicas'],
            ['modelonegocio' => 'reservas', 'description' => 'Reservas de espacios, citas o servicios'],
            ['modelonegocio' => 'atencion_cliente', 'description' => 'Soporte postventa y atención a usuarios'],
            ['modelonegocio' => 'concesionaria', 'description' => 'Venta, test drives y financiamiento de vehículos'],
            ['modelonegocio' => 'cafeteria_y_mas', 'description' => 'Cafetería, panadería, eventos y membresías'],
            ['modelonegocio' => 'salon_belleza', 'description' => 'Servicios de belleza, estética y promociones'],
            ['modelonegocio' => 'agencia_viajes', 'description' => 'Paquetes turísticos, asesoría y reservas'],
            ['modelonegocio' => 'bienes_raices', 'description' => 'Compra, arriendo y publicación de propiedades'],
            ['modelonegocio' => 'ventas_productos', 'description' => 'Catálogo, ventas, envíos y soporte postventa'],
            ['modelonegocio' => 'promociones', 'description' => 'Gestión de campañas, cupones y descuentos']
        ];

        foreach ($models as $data) {
            \App\Models\BusinessModel::create($data);
        }

    }
}
