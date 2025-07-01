<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Intent;

class IntentSeeder extends Seeder
{
    public function run()
    {
        $intents = [
            [ 'name' => 'solicitar_consulta_medica', 'description' => 'Solicitar una nueva consulta médica', 'priority' => 0 ],
            [ 'name' => 'consultar_tipo_consulta', 'description' => 'Consultar los tipos de consulta médica disponibles', 'priority' => 0 ],
            [ 'name' => 'consultar_medico', 'description' => 'Consultar disponibilidad de médicos', 'priority' => 0 ],
            [ 'name' => 'reagendar_consulta', 'description' => 'Reagendar una consulta médica existente', 'priority' => 0 ],
            [ 'name' => 'cancelar_consulta', 'description' => 'Cancelar una consulta médica agendada', 'priority' => 0 ],
            [ 'name' => 'consultar_resultados_examenes', 'description' => 'Consultar los resultados de exámenes médicos', 'priority' => 0 ],
            [ 'name' => 'consultar_seguro_aceptado', 'description' => 'Consultar seguros médicos aceptados', 'priority' => 0 ],
            [ 'name' => 'crear_reserva', 'description' => 'Crear una nueva reserva', 'priority' => 0 ],
            [ 'name' => 'consultar_reserva', 'description' => 'Consultar los detalles de una reserva', 'priority' => 0 ],
            [ 'name' => 'modificar_reserva', 'description' => 'Modificar una reserva existente', 'priority' => 0 ],
            [ 'name' => 'cancelar_reserva', 'description' => 'Cancelar una reserva', 'priority' => 0 ],
            [ 'name' => 'consultar_disponibilidad', 'description' => 'Consultar disponibilidad para una reserva o servicio', 'priority' => 0 ],
            [ 'name' => 'consultar_politicas_reserva', 'description' => 'Consultar políticas relacionadas con reservas', 'priority' => 0 ],
            [ 'name' => 'consultar_estado_pedido', 'description' => 'Consultar el estado actual de un pedido', 'priority' => 0 ],
            [ 'name' => 'reporte_problema_producto', 'description' => 'Reportar un problema con un producto', 'priority' => 0 ],
            [ 'name' => 'solicitar_devolucion', 'description' => 'Solicitar la devolución de un producto', 'priority' => 0 ],
            [ 'name' => 'consultar_politicas_servicio', 'description' => 'Consultar políticas del servicio al cliente', 'priority' => 0 ],
            [ 'name' => 'reclamo_facturacion', 'description' => 'Realizar un reclamo por problemas de facturación', 'priority' => 0 ],
            [ 'name' => 'actualizar_datos_cliente', 'description' => 'Actualizar información del cliente', 'priority' => 0 ],
            [ 'name' => 'contactar_agente', 'description' => 'Solicitar contacto con un agente de servicio', 'priority' => 0 ],
            [ 'name' => 'buscar_vehiculo', 'description' => 'Buscar un vehículo disponible en la concesionaria', 'priority' => 0 ],
            [ 'name' => 'agendar_test_drive', 'description' => 'Agendar una prueba de manejo', 'priority' => 0 ],
            [ 'name' => 'consultar_financiamiento', 'description' => 'Consultar opciones de financiamiento para vehículos', 'priority' => 0 ],
            [ 'name' => 'cotizar_vehiculo', 'description' => 'Obtener una cotización de vehículo', 'priority' => 0 ],
            [ 'name' => 'consultar_servicios_postventa', 'description' => 'Consultar servicios postventa disponibles', 'priority' => 0 ],
            [ 'name' => 'agendar_servicio_tecnico', 'description' => 'Agendar un servicio técnico para un vehículo', 'priority' => 0 ],
            [ 'name' => 'buscar_producto', 'description' => 'Buscar productos disponibles', 'priority' => 0 ],
            [ 'name' => 'consultar_precio', 'description' => 'Consultar el precio de un producto', 'priority' => 0 ],
            [ 'name' => 'realizar_pedido', 'description' => 'Realizar un pedido en la cafetería', 'priority' => 0 ],
            [ 'name' => 'consultar_promociones', 'description' => 'Consultar promociones vigentes', 'priority' => 0 ],
            [ 'name' => 'reservar_evento', 'description' => 'Reservar un evento en la cafetería', 'priority' => 0 ],
            [ 'name' => 'consultar_membresias', 'description' => 'Consultar tipos de membresía disponibles', 'priority' => 0 ],
            [ 'name' => 'contactar_personal', 'description' => 'Contactar al personal del local', 'priority' => 0 ],
            [ 'name' => 'reservar_cita', 'description' => 'Reservar una cita en el salón de belleza', 'priority' => 0 ],
            [ 'name' => 'consultar_servicios', 'description' => 'Consultar servicios ofrecidos por el salón', 'priority' => 0 ],
            [ 'name' => 'cancelar_cita', 'description' => 'Cancelar una cita previamente reservada', 'priority' => 0 ],
            [ 'name' => 'opinion_servicio', 'description' => 'Dejar una opinión sobre el servicio recibido', 'priority' => 0 ],
            [ 'name' => 'buscar_paquete_turistico', 'description' => 'Buscar paquetes turísticos disponibles', 'priority' => 0 ],
            [ 'name' => 'consultar_precio_paquete', 'description' => 'Consultar precios de paquetes turísticos', 'priority' => 0 ],
            [ 'name' => 'reserva_paquete', 'description' => 'Reservar un paquete turístico', 'priority' => 0 ],
            [ 'name' => 'consultar_itinerario', 'description' => 'Consultar itinerario de viaje', 'priority' => 0 ],
            [ 'name' => 'asesoria_viaje', 'description' => 'Solicitar asesoría personalizada para un viaje', 'priority' => 0 ],
            [ 'name' => 'buscar_propiedad', 'description' => 'Buscar una propiedad disponible', 'priority' => 0 ],
            [ 'name' => 'agendar_visita', 'description' => 'Agendar una visita a una propiedad', 'priority' => 0 ],
            [ 'name' => 'publicar_propiedad', 'description' => 'Publicar una nueva propiedad', 'priority' => 0 ],
            [ 'name' => 'realizar_compra', 'description' => 'Realizar la compra de un producto', 'priority' => 0 ],
            [ 'name' => 'consultar_envio', 'description' => 'Consultar el estado del envío de un pedido', 'priority' => 0 ],
            [ 'name' => 'postventa_soporte', 'description' => 'Solicitar soporte postventa', 'priority' => 0 ],
            [ 'name' => 'buscar_promociones', 'description' => 'Buscar promociones activas', 'priority' => 0 ],
            [ 'name' => 'consultar_detalle_promocion', 'description' => 'Consultar detalles de una promoción específica', 'priority' => 0 ],
            [ 'name' => 'filtrar_promociones', 'description' => 'Filtrar promociones según preferencias', 'priority' => 0 ],
            [ 'name' => 'consultar_cupones', 'description' => 'Consultar cupones disponibles', 'priority' => 0 ],
            [ 'name' => 'consultar_promociones_personalizadas', 'description' => 'Consultar promociones personalizadas según el historial de compras', 'priority' => 0 ],
            [ 'name' => 'consultar_formas_aplicar_promocion', 'description' => 'Consultar las formas de aplicar una promoción', 'priority' => 0 ],
        ];
        Intent::insert($intents);
    }
}
