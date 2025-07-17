<?php

namespace Database\Seeders;

use App\Models\Entitie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntitieSeeder extends Seeder
{
    public function run()
    {
        //Entities
        $entities = [
            ['entity' => 'especialidad', 'description' => 'Especialidad médica solicitada'],
            ['entity' => 'fecha', 'description' => 'Fecha para reserva, evento o servicio'],
            ['entity' => 'hora', 'description' => 'Hora solicitada para reserva, evento o servicio'],
            ['entity' => 'modalidad', 'description' => 'Modalidad del servicio (presencial o virtual)'],
            ['entity' => 'sucursal', 'description' => 'Sucursal o local seleccionado'],
            ['entity' => 'nombre_medico', 'description' => 'Nombre del médico'],
            ['entity' => 'id_consulta', 'description' => 'Identificador de la consulta médica'],
            ['entity' => 'tipo_consulta', 'description' => 'Tipo de consulta médica'],
            ['entity' => 'motivo_reprogramacion', 'description' => 'Motivo para reprogramar una consulta'],
            ['entity' => 'motivo_cancelacion', 'description' => 'Razón para cancelar una consulta, cita o reserva'],
            ['entity' => 'tipo_examen', 'description' => 'Tipo de examen médico'],
            ['entity' => 'fecha_examen', 'description' => 'Fecha del examen médico'],
            ['entity' => 'canal_entrega', 'description' => 'Canal de entrega de resultados o servicios'],
            ['entity' => 'compañia_seguro', 'description' => 'Nombre de la aseguradora'],
            ['entity' => 'tipo_seguro', 'description' => 'Tipo de seguro médico'],
            ['entity' => 'tipo_reserva', 'description' => 'Tipo de reserva (mesa, sala, etc.)'],
            ['entity' => 'cantidad_personas', 'description' => 'Cantidad de personas en la reserva o evento'],
            ['entity' => 'codigo_reserva', 'description' => 'Código de identificación de una reserva'],
            ['entity' => 'nueva_fecha', 'description' => 'Nueva fecha para modificación de reserva'],
            ['entity' => 'nueva_hora', 'description' => 'Nueva hora para modificación de reserva'],
            ['entity' => 'nueva_cantidad_personas', 'description' => 'Nuevo número de personas para reserva'],
            ['entity' => 'fecha_reserva', 'description' => 'Fecha en la que se hizo la reserva'],
            ['entity' => 'tipo_politica', 'description' => 'Tipo de política relacionada con el servicio'],
            ['entity' => 'formato', 'description' => 'Formato de visualización o entrega'],
            ['entity' => 'numero_pedido', 'description' => 'Número de pedido o compra'],
            ['entity' => 'canal_compra', 'description' => 'Canal a través del cual se hizo la compra'],
            ['entity' => 'estado_actual', 'description' => 'Estado actual del pedido o servicio'],
            ['entity' => 'nombre_producto', 'description' => 'Nombre del producto involucrado'],
            ['entity' => 'tipo_problema', 'description' => 'Tipo de problema reportado'],
            ['entity' => 'fecha_compra', 'description' => 'Fecha en que se realizó la compra'],
            ['entity' => 'motivo_devolucion', 'description' => 'Razón de la devolución del producto'],
            ['entity' => 'fecha_entrega', 'description' => 'Fecha de entrega del producto o servicio'],
            ['entity' => 'formato_consulta', 'description' => 'Formato en que se desea la consulta'],
            ['entity' => 'numero_factura', 'description' => 'Número de la factura asociada'],
            ['entity' => 'motivo_reclamo', 'description' => 'Motivo del reclamo realizado'],
            ['entity' => 'fecha_factura', 'description' => 'Fecha en que se emitió la factura'],
            ['entity' => 'campo_actualizar', 'description' => 'Campo específico a actualizar en el perfil'],
            ['entity' => 'valor_nuevo', 'description' => 'Nuevo valor que se desea establecer'],
            ['entity' => 'motivo_contacto', 'description' => 'Razón principal del contacto con el agente'],
            ['entity' => 'medio_preferido', 'description' => 'Medio de comunicación preferido por el cliente'],
            ['entity' => 'marca', 'description' => 'Marca del vehículo o producto'],
            ['entity' => 'modelo', 'description' => 'Modelo del vehículo o artículo'],
            ['entity' => 'tipo_vehiculo', 'description' => 'Tipo de vehículo requerido'],
            ['entity' => 'rango_precio', 'description' => 'Rango de precio buscado'],
            ['entity' => 'año', 'description' => 'Año del modelo del vehículo'],
            ['entity' => 'codigo_vehiculo', 'description' => 'Código único de identificación del vehículo'],
            ['entity' => 'ubicacion_concesionario', 'description' => 'Ubicación física del concesionario'],
            ['entity' => 'tipo_financiamiento', 'description' => 'Tipo de financiamiento solicitado'],
            ['entity' => 'plazo', 'description' => 'Duración del financiamiento o plan'],
            ['entity' => 'cuota_inicial', 'description' => 'Monto inicial del pago de financiamiento'],
            ['entity' => 'banco', 'description' => 'Banco o entidad financiera involucrada'],
            ['entity' => 'color', 'description' => 'Color preferido del vehículo'],
            ['entity' => 'extras', 'description' => 'Elementos adicionales o accesorios del vehículo'],
            ['entity' => 'metodo_contacto', 'description' => 'Forma de contacto preferida'],
            ['entity' => 'servicio', 'description' => 'Servicio requerido en postventa o belleza'],
            ['entity' => 'vigencia', 'description' => 'Vigencia de una promoción o servicio'],
            ['entity' => 'patente', 'description' => 'Matrícula o identificación del vehículo']
        ];

        Entitie::insert($entities);

    }
}
