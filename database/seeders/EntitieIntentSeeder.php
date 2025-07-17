<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntitieIntentSeeder extends Seeder
{
    public function run()
    {
        $map = [
            'solicitar_consulta_medica' => ['especialidad', 'fecha', 'hora', 'modalidad', 'sucursal'],
            'consultar_tipo_consulta' => ['tipo_consulta'],
            'consultar_medico' => ['especialidad', 'nombre_medico', 'fecha', 'hora'],
            'reagendar_consulta' => ['id_consulta', 'fecha', 'hora', 'motivo_reprogramacion'],
            'cancelar_consulta' => ['id_consulta', 'motivo_cancelacion'],
            'consultar_resultados_examenes' => ['tipo_examen', 'fecha_examen', 'canal_entrega'],
            'consultar_seguro_aceptado' => ['compañia_seguro', 'tipo_seguro'],

            'crear_reserva' => ['tipo_reserva', 'fecha', 'hora', 'cantidad_personas', 'sucursal'],
            'consultar_reserva' => ['codigo_reserva'],
            'modificar_reserva' => ['codigo_reserva', 'nueva_fecha', 'nueva_hora', 'nueva_cantidad_personas'],
            'cancelar_reserva' => ['codigo_reserva', 'motivo_cancelacion'],
            'consultar_disponibilidad' => ['tipo_reserva', 'fecha', 'hora', 'sucursal'],
            'consultar_politicas_reserva' => ['tipo_politica', 'formato'],

            'consultar_estado_pedido' => ['numero_pedido', 'canal_compra'],
            'reporte_problema_producto' => ['nombre_producto', 'tipo_problema', 'fecha_compra'],
            'solicitar_devolucion' => ['numero_pedido', 'motivo_devolucion', 'fecha_entrega'],
            'consultar_politicas_servicio' => ['tipo_politica', 'formato_consulta'],
            'reclamo_facturacion' => ['numero_factura', 'motivo_reclamo', 'fecha_factura'],
            'actualizar_datos_cliente' => ['campo_actualizar', 'valor_nuevo'],
            'contactar_agente' => ['motivo_contacto', 'medio_preferido'],

            'buscar_vehiculo' => ['marca', 'modelo', 'tipo_vehiculo', 'rango_precio', 'año'],
            'consultar_disponibilidad' => ['codigo_vehiculo', 'ubicacion_concesionario'],
            'agendar_test_drive' => ['codigo_vehiculo', 'fecha', 'hora', 'sucursal'],
            'consultar_financiamiento' => ['tipo_financiamiento', 'plazo', 'cuota_inicial', 'banco'],
            'cotizar_vehiculo' => ['codigo_vehiculo', 'color', 'extras', 'metodo_contacto'],
            'consultar_servicios_postventa' => ['servicio', 'vigencia'],
            'agendar_servicio_tecnico' => ['patente', 'servicio', 'fecha', 'hora'],

            'buscar_producto' => ['categoria_producto', 'ingrediente', 'tipo_bebida', 'tamaño', 'nombre_producto', 'rango_precio'],
            'consultar_precio' => ['nombre_producto', 'moneda'],
            'realizar_pedido' => ['productos', 'cantidad', 'tipo_consumo', 'fecha', 'sucursal'],
            'consultar_promociones' => ['tipo_promocion', 'fecha_validez'],
            'reservar_evento' => ['tipo_evento', 'fecha_evento', 'hora_evento', 'cantidad_personas', 'sucursal'],
            'consultar_membresias' => ['tipo_membresia', 'beneficios', 'costo'],
            'contactar_personal' => ['tema_consulta', 'medio_contacto'],

            'reservar_cita' => ['servicio', 'fecha', 'hora', 'profesional', 'sucursal'],
            'consultar_servicios' => ['categoria_servicio'],
            'cancelar_cita' => ['cita_id', 'motivo_cancelacion'],
            'consultar_promociones' => ['tipo_promocion', 'fecha_validez'],
            'opinion_servicio' => ['cita_id', 'valoracion'],

            'buscar_paquete_turistico' => ['destino', 'fecha_salida', 'duracion', 'tipo_viaje', 'rango_precio'],
            'consultar_precio_paquete' => ['paquete_id', 'moneda'],
            'reserva_paquete' => ['paquete_id', 'cantidad_personas', 'nombre_pasajero', 'metodo_pago'],
            'consultar_itinerario' => ['paquete_id'],
            'cancelar_reserva' => ['reserva_id', 'motivo_cancelacion', 'fecha_reserva'],
            'consultar_promociones' => ['promocion_tipo', 'fecha_validez'],
            'asesoria_viaje' => ['intereses_usuario', 'origen', 'fecha_estimativa'],

            'buscar_propiedad' => ['tipo_propiedad', 'ubicacion', 'rango_precio', 'operacion', 'habitaciones', 'baños', 'area_minima'],
            'agendar_visita' => ['codigo_propiedad', 'fecha_visita', 'hora_visita', 'modalidad_visita'],
            'contactar_agente' => ['nombre_agente', 'medio_contacto', 'motivo'],
            'consultar_financiamiento' => ['tipo_financiamiento', 'entidad_bancaria', 'plazo', 'cuota_aproximada'],
            'publicar_propiedad' => ['tipo_propiedad', 'ubicacion', 'precio', 'contacto_propietario'],

            'realizar_compra' => ['nombre_producto', 'cantidad', 'direccion_envio'],
            'consultar_envio' => ['numero_orden', 'estado_envio', 'empresa_envio'],
            'postventa_soporte' => ['problema', 'tipo_solicitud'],

            'buscar_promociones' => ['categoria', 'tipo_promocion', 'fecha_validez', 'canal_venta'],
            'consultar_detalle_promocion' => ['nombre_promocion', 'beneficio', 'vigencia', 'condiciones'],
            'filtrar_promociones' => ['rango_descuento', 'moneda', 'origen_promocion'],
            'consultar_cupones' => ['tipo_cupon', 'valor', 'restricciones', 'fecha_expiracion'],
            'consultar_promociones_personalizadas' => ['tipo_usuario', 'historial_compras', 'preferencias'],
            'consultar_formas_aplicar_promocion' => ['medio_aplicacion', 'dispositivo', 'pasos_requeridos'],
        ];

        foreach ($map as $intentName => $entityNames) {
            $intentId = DB::table('intents')->where('intent', $intentName)->value('id');

            foreach ($entityNames as $entityName) {
                $entityId = DB::table('entities')->where('entity', $entityName)->value('id');

                if ($intentId && $entityId) {
                    DB::table('entitie_intent')->insert([
                        'intent_id' => $intentId,
                        'entitie_id' => $entityId,
                    ]);
                }
            }
        }
    }
}
