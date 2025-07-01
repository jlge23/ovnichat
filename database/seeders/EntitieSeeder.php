<?php

namespace Database\Seeders;

use App\Models\Entitie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntitieSeeder extends Seeder
{
    public function run()
    {
        $entities = [
            ['name' => 'especialidad', 'description' => 'Especialidad médica solicitada'],
            ['name' => 'fecha', 'description' => 'Fecha para reserva, evento o servicio'],
            ['name' => 'hora', 'description' => 'Hora solicitada para reserva, evento o servicio'],
            ['name' => 'modalidad', 'description' => 'Modalidad del servicio (presencial o virtual)'],
            ['name' => 'sucursal', 'description' => 'Sucursal o local seleccionado'],
            ['name' => 'nombre_medico', 'description' => 'Nombre del médico'],
            ['name' => 'id_consulta', 'description' => 'Identificador de la consulta médica'],
            ['name' => 'tipo_consulta', 'description' => 'Tipo de consulta médica'],
            ['name' => 'motivo_reprogramacion', 'description' => 'Motivo para reprogramar una consulta'],
            ['name' => 'motivo_cancelacion', 'description' => 'Razón para cancelar una consulta, cita o reserva'],
            ['name' => 'tipo_examen', 'description' => 'Tipo de examen médico'],
            ['name' => 'fecha_examen', 'description' => 'Fecha del examen médico'],
            ['name' => 'canal_entrega', 'description' => 'Canal de entrega de resultados o servicios'],
            ['name' => 'compañia_seguro', 'description' => 'Nombre de la aseguradora'],
            ['name' => 'tipo_seguro', 'description' => 'Tipo de seguro médico'],
            ['name' => 'tipo_reserva', 'description' => 'Tipo de reserva (mesa, sala, etc.)'],
            ['name' => 'cantidad_personas', 'description' => 'Cantidad de personas en la reserva o evento'],
            ['name' => 'codigo_reserva', 'description' => 'Código de identificación de una reserva'],
            ['name' => 'nueva_fecha', 'description' => 'Nueva fecha para modificación de reserva'],
            ['name' => 'nueva_hora', 'description' => 'Nueva hora para modificación de reserva'],
            ['name' => 'nueva_cantidad_personas', 'description' => 'Nuevo número de personas para reserva'],
            ['name' => 'fecha_reserva', 'description' => 'Fecha en la que se hizo la reserva'],
            ['name' => 'tipo_politica', 'description' => 'Tipo de política relacionada con el servicio'],
            ['name' => 'formato', 'description' => 'Formato de visualización o entrega'],
            ['name' => 'numero_pedido', 'description' => 'Número de pedido o compra'],
            ['name' => 'canal_compra', 'description' => 'Canal a través del cual se hizo la compra'],
            ['name' => 'estado_actual', 'description' => 'Estado actual del pedido o servicio'],
            ['name' => 'nombre_producto', 'description' => 'Nombre del producto involucrado'],
            ['name' => 'tipo_problema', 'description' => 'Tipo de problema reportado'],
            ['name' => 'fecha_compra', 'description' => 'Fecha en que se realizó la compra'],
            ['name' => 'motivo_devolucion', 'description' => 'Razón de la devolución del producto'],
            ['name' => 'fecha_entrega', 'description' => 'Fecha de entrega del producto o servicio'],
            ['name' => 'formato_consulta', 'description' => 'Formato en que se desea la consulta'],
            ['name' => 'numero_factura', 'description' => 'Número de la factura asociada'],
            ['name' => 'motivo_reclamo', 'description' => 'Motivo del reclamo realizado'],
            ['name' => 'fecha_factura', 'description' => 'Fecha en que se emitió la factura'],
            ['name' => 'campo_actualizar', 'description' => 'Campo específico a actualizar en el perfil'],
            ['name' => 'valor_nuevo', 'description' => 'Nuevo valor que se desea establecer'],
            ['name' => 'motivo_contacto', 'description' => 'Razón principal del contacto con el agente'],
            ['name' => 'medio_preferido', 'description' => 'Medio de comunicación preferido por el cliente'],
            ['name' => 'marca', 'description' => 'Marca del vehículo o producto'],
            ['name' => 'modelo', 'description' => 'Modelo del vehículo o artículo'],
            ['name' => 'tipo_vehiculo', 'description' => 'Tipo de vehículo requerido'],
            ['name' => 'rango_precio', 'description' => 'Rango de precio buscado'],
            ['name' => 'año', 'description' => 'Año del modelo del vehículo'],
            ['name' => 'codigo_vehiculo', 'description' => 'Código único de identificación del vehículo'],
            ['name' => 'ubicacion_concesionario', 'description' => 'Ubicación física del concesionario'],
            ['name' => 'tipo_financiamiento', 'description' => 'Tipo de financiamiento solicitado'],
            ['name' => 'plazo', 'description' => 'Duración del financiamiento o plan'],
            ['name' => 'cuota_inicial', 'description' => 'Monto inicial del pago de financiamiento'],
            ['name' => 'banco', 'description' => 'Banco o entidad financiera involucrada'],
            ['name' => 'color', 'description' => 'Color preferido del vehículo'],
            ['name' => 'extras', 'description' => 'Elementos adicionales o accesorios del vehículo'],
            ['name' => 'metodo_contacto', 'description' => 'Forma de contacto preferida'],
            ['name' => 'servicio', 'description' => 'Servicio requerido en postventa o belleza'],
            ['name' => 'vigencia', 'description' => 'Vigencia de una promoción o servicio'],
            ['name' => 'patente', 'description' => 'Matrícula o identificación del vehículo']
        ];

        Entitie::insert($entities);
    }
}
