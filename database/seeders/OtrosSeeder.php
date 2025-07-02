<?php

namespace Database\Seeders;

use App\Models\BusinessModel;
use App\Models\Entitie;
use App\Models\Intent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OtrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $intents = [
            ['name' => 'saludo', 'description' => 'Saludo inicial', 'priority' => 1],
            ['name' => 'despedida', 'description' => 'Despedida del usuario', 'priority' => 1],
            ['name' => 'agradecimiento', 'description' => 'Expresión de gratitud', 'priority' => 1],
            ['name' => 'nosotros', 'description' => 'Consulta sobre la empresa', 'priority' => 1],
            ['name' => 'reclamo', 'description' => 'Reclamo o queja', 'priority' => 1],
            ['name' => 'consulta_horario', 'description' => 'Consulta de horarios', 'priority' => 1],
            ['name' => 'consulta_ubicacion', 'description' => 'Consulta de ubicación', 'priority' => 1],
            ['name' => 'consulta_precio', 'description' => 'Consulta de precios', 'priority' => 1],
            ['name' => 'consulta_promocion', 'description' => 'Consulta de promociones', 'priority' => 1],
            ['name' => 'consulta_forma_pago', 'description' => 'Consulta sobre formas de pago', 'priority' => 1],
            ['name' => 'consulta_tiempo_entrega', 'description' => 'Consulta sobre tiempo de entrega', 'priority' => 1],
            ['name' => 'consulta_garantia', 'description' => 'Consulta sobre garantía', 'priority' => 1],
            ['name' => 'pedido_asistencia', 'description' => 'Solicitud de asistencia', 'priority' => 1],
            ['name' => 'cancelacion', 'description' => 'Cancelación de pedido', 'priority' => 1],
            ['name' => 'preguntar_producto', 'description' => 'Consulta general sobre productos', 'priority' => 1],
            ['name' => 'disponibilidad_producto', 'description' => 'Consulta de disponibilidad de producto', 'priority' => 1],
        ];

        foreach ($intents as $intent) {
            \App\Models\Intent::firstOrCreate(['name' => $intent['name']], $intent);
        }

        $entities = [
            'nombre_producto',
            'cantidad',
            'peso_presentacion',
            'presentacion',
            'marca',
            'categoria_producto',
            'tipo_servicio',
            'sucursal',
            'ciudad',
            'modo_envio',
            'medio_pago',
            'banco',
            'plazo',
            'referencia_pedido',
            'motivo_reclamo',
            'motivo_cancelacion',
            'tipo_asistencia',
            'medio_contacto'
        ];

        foreach ($entities as $nombre) {
            if (!Entitie::where('name', $nombre)->exists()) {
                Entitie::create([
                    'name' => $nombre,
                    'description' => ucfirst(str_replace('_', ' ', $nombre))
                ]);
                echo "✅ Insertada entity: {$nombre}\n";
            } else {
                echo "🟡 Entity ya existe: {$nombre}\n";
            }
        }

        $businessModels = BusinessModel::all();
        $intents = Intent::whereIn('name', array_column($intents, 'name'))->get();

        foreach ($businessModels as $model) {
            foreach ($intents as $intent) {
                DB::table('business_model_intent')->updateOrInsert([
                    'business_model_id' => $model->id,
                    'intent_id' => $intent->id,
                ]);
            }
        }


        $map = [
            'reclamo' => ['motivo_reclamo', 'referencia_pedido'],
            'consulta_horario' => ['tipo_servicio'],
            'consulta_ubicacion' => ['sucursal', 'ciudad', 'tipo_servicio'],
            'consulta_precio' => ['nombre_producto', 'marca', 'presentacion', 'peso_presentacion', 'categoria_producto'],
            'consulta_promocion' => ['nombre_producto', 'categoria_producto'],
            'consulta_forma_pago' => ['medio_pago', 'banco', 'plazo'],
            'consulta_tiempo_entrega' => ['nombre_producto', 'ciudad', 'modo_envio'],
            'consulta_garantia' => ['nombre_producto'],
            'pedido_asistencia' => ['tipo_asistencia', 'medio_contacto'],
            'cancelacion' => ['referencia_pedido', 'motivo_cancelacion'],
            'preguntar_producto' => ['nombre_producto', 'categoria_producto', 'marca', 'presentacion'],
            'disponibilidad_producto' => ['nombre_producto', 'cantidad', 'marca', 'categoria_producto', 'presentacion', 'peso_presentacion']
        ];

        foreach ($map as $intentName => $entityNames) {
            $intent = Intent::where('name', $intentName)->first();

            if (!$intent) {
                echo "🟥 Intent no encontrado: {$intentName}\n";
                continue;
            }

            foreach ($entityNames as $entityName) {
                $entity = Entitie::where('name', $entityName)->first();

                if (!$entity) {
                    echo "🟥 Entitie no encontrada: {$entityName}\n";
                    continue;
                }

                $exists = DB::table('entitie_intent')
                    ->where('intent_id', $intent->id)
                    ->where('entitie_id', $entity->id)
                    ->exists();

                if (!$exists) {
                    DB::table('entitie_intent')->insert([
                        'intent_id' => $intent->id,
                        'entitie_id' => $entity->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    echo "✅ Relación creada: {$intentName} → {$entityName}\n";
                } else {
                    echo "🟡 Ya existe: {$intentName} → {$entityName}\n";
                }
            }
        }

    }
}
