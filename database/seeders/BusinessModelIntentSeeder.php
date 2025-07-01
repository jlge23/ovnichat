<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessModelIntentSeeder extends Seeder
{
    public function run()
    {
        $map = [
            'consultas_medicas' => [
                'solicitar_consulta_medica',
                'consultar_tipo_consulta',
                'consultar_medico',
                'reagendar_consulta',
                'cancelar_consulta',
                'consultar_resultados_examenes',
                'consultar_seguro_aceptado',
            ],
            'reservas' => [
                'crear_reserva',
                'consultar_reserva',
                'modificar_reserva',
                'cancelar_reserva',
                'consultar_disponibilidad',
                'consultar_politicas_reserva',
            ],
            'atencion_cliente' => [
                'consultar_estado_pedido',
                'reporte_problema_producto',
                'solicitar_devolucion',
                'consultar_politicas_servicio',
                'reclamo_facturacion',
                'actualizar_datos_cliente',
                'contactar_agente',
            ],
            'concesionaria' => [
                'buscar_vehiculo',
                'consultar_disponibilidad',
                'agendar_test_drive',
                'consultar_financiamiento',
                'cotizar_vehiculo',
                'consultar_servicios_postventa',
                'agendar_servicio_tecnico',
            ],
            'cafeteria_y_mas' => [
                'buscar_producto',
                'consultar_precio',
                'realizar_pedido',
                'consultar_disponibilidad',
                'consultar_promociones',
                'reservar_evento',
                'consultar_membresias',
                'contactar_personal',
            ],
            'salon_belleza' => [
                'reservar_cita',
                'consultar_servicios',
                'consultar_precio',
                'cancelar_cita',
                'consultar_disponibilidad',
                'consultar_promociones',
                'opinion_servicio',
            ],
            'agencia_viajes' => [
                'buscar_paquete_turistico',
                'consultar_precio_paquete',
                'reserva_paquete',
                'consultar_itinerario',
                'cancelar_reserva',
                'consultar_promociones',
                'asesoria_viaje',
            ],
            'bienes_raices' => [
                'buscar_propiedad',
                'consultar_disponibilidad',
                'agendar_visita',
                'contactar_agente',
                'consultar_financiamiento',
                'publicar_propiedad',
            ],
            'ventas_productos' => [
                'buscar_producto',
                'consultar_precio',
                'consultar_disponibilidad',
                'realizar_compra',
                'consultar_envio',
                'postventa_soporte',
            ],
            'promociones' => [
                'buscar_promociones',
                'consultar_detalle_promocion',
                'filtrar_promociones',
                'consultar_cupones',
                'consultar_promociones_personalizadas',
                'consultar_formas_aplicar_promocion',
            ]
        ];

        foreach ($map as $businessModel => $intents) {
            $businessModelId = DB::table('business_models')->where('name', $businessModel)->value('id');

            foreach ($intents as $intentName) {
                $intentId = DB::table('intents')->where('name', $intentName)->value('id');

                if ($businessModelId && $intentId) {
                    DB::table('business_model_intent')->insert([
                        'business_model_id' => $businessModelId,
                        'intent_id' => $intentId,
                    ]);
                }
            }
        }
    }

}
