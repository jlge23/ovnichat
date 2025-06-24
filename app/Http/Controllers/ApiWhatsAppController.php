<?php

namespace App\Http\Controllers;


use App\Events\WhatsappEvent;
use App\Jobs\SendWhatsAppMessageJob;
use App\Jobs\MarcarMensajeComoLeidoJob;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\WitAiTrait;

class ApiWhatsAppController extends Controller
{
    use WitAiTrait;
    public $token;
    public $phoneId;
    public $version;
    public $local_verify_token;
    public $url;
    // Constructor de la clase
    public function __construct(){ //estos parametros estan en el archivo config/services.php
        $this->token = config('services.whatsapp.token');
        $this->phoneId = config('services.whatsapp.phone_id');
        $this->version = config('services.whatsapp.version');
        $this->local_verify_token = config('services.whatsapp.local_verify_token');
        $this->url = config('services.whatsapp.url');
    }
    // Metodo de prueba para la API de WhatApp de su plantilla Hello_word
    public function whsend(){
        try {
            $payload = [
                "messaging_product" => "whatsapp",
                "to" => "593983774093",
                "type" => "template",
                "template" => [
                    "name" => "hello_world",
                    "language" => [
                        "code" => "en_US"
                    ]
                ]
            ];
            $message = Http::withToken($this->token)->post( $this->url, $payload)->throw()->json();
            return response()->json([
                'success' => true,
                'data' => $message
            ], 200);
        } catch (Exception  $e) {
            return response()->json([
                'success' => false,
                'data' => $e->getMessage()
            ], 500);
        }
    }
    // Metodo para Autenticación de Laravel con la API de WhatsApp
    public function whget(Request $request){
        try {
            $msg = $request->query();

            $mode = $msg["hub_mode"];
            $challenge = $msg["hub_challenge"];
            $verify_token = $msg["hub_verify_token"];
            if(isset($this->local_verify_token) && isset($verify_token)){
                if($mode == 'subscribe' && $this->local_verify_token === $verify_token){
                    return response($challenge, 200)->header('content-type','text-plain');
                }
            }
            throw new Exception('Invalid request');
        }catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => $e->getMessage()
            ], 500);
        }
    }
    // Metodo para enviar mensajes simples hacia WhatsApp
    public function sendWhatsAppMessageText($to, $text){
        SendWhatsAppMessageJob::dispatch($to, $text);
        return response()->json(['success' => true, 'message' => 'Mensaje en proceso de envío'], 200);
    }
    // Metodo para marcar los mensajes como leidos en la API de WhatsApp
    private function markMessageAsRead($msg_id){
        try {
            $data = [
                "messaging_product" => "whatsapp",
                "status" => "read",
                "message_id" => $msg_id
            ];
            Http::withToken($this->token)->withHeaders(['Content-Type' => 'application/json'])->post($this->url, $data);
        } catch (Exception $e) {
            event(new WhatsappEvent("Error al marcar mensaje como leído: " . $e->getMessage()));
        }
    }
    // Metodo manejará los mensajes de estado que WhatsApp envía
    private function processStatusMessage($val){
        $timestamp = data_get($val, 'statuses.0.timestamp');
        $status = data_get($val, 'statuses.0.status');
        if ($timestamp && $status) {
            $fecha = date("Y-m-d H:i:s", $timestamp);
            $mensaje = "Estado del mensaje: $status - Fecha: $fecha";
            event(new WhatsappEvent($mensaje));
            return response()->json([
                'success' => true,
                'message' => $mensaje,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Mensaje desconocido',
        ], 400);
    }
    // Metodo para manejar distintos tipos de mensajes
    private function processMessage($val){
        switch (data_get($val, 'messages.0.type')) {
            case 'text':
                return data_get($val, 'messages.0.text.body');
                return;
            case 'audio': return "Audio recibido: " . data_get($val, 'messages.0.audio.mime_type');
            case 'image': return "Imagen recibida: " . data_get($val, 'messages.0.image.mime_type');
            case 'video': return "Video recibido: " . data_get($val, 'messages.0.video.mime_type');
            case 'contacts':
                return "Contacto recibido: " . strtoupper(trim(data_get($val, 'messages.0.contacts.0.name.first_name')))
                    . " " . strtoupper(trim(data_get($val, 'messages.0.contacts.0.name.last_name'))) . " - "
                    . trim("+".data_get($val, 'contacts.0.wa_id'));
            case 'interactive':
                $selectedOption = data_get($val, 'messages.0.interactive.button_reply.id');
                return "El usuario seleccionó la opción: " . $selectedOption;
            default:
                return "Mensaje no identificado.";
        }
    }
    //ESTE METODO RECIBE TODAS LAS INTERACCIONES DE WHATSAPP
    public function postrwhget(Request $request){
        try {
            $val = $request->input('entry.0.changes.0.value');
            if (!$request->has('entry.0.changes.0.value.messages')) {
                // No es un mensaje del usuario, probablemente es un evento tipo "message_delivered"
                return $this->processStatusMessage($val);
            }
            $mensaje = $request->input('entry.0.changes.0.value.messages.0.text.body');
            $msg_id = $request->input('entry.0.changes.0.value.messages.0.id');
            $nombre = $request->input('entry.0.changes.0.value.contacts.0.profile.name');
            $numCli = $request->input('entry.0.changes.0.value.contacts.0.wa_id');
            $mensaje = $this->processMessage($val);
            event(new WhatsappEvent($nombre." dice: ".$mensaje));
            if ($request->has('entry.0.changes.0.value.0.messages.type.interactive')) {
                $this->handleInteractiveResponse($numCli, $nombre, $request->has('entry.0.changes.0.value.0.messages.type.interactive.button_reply.id'));
            } else {
                //$this->TemplateOpciones($numCli, $nombre, false);
                //SendMenuOptionsJob::dispatch($numCli, $nombre, false);
                //event(new WhatsappEvent($mensaje));
                //$this->WitIaEval($numCli,$nombre,$mensaje);//Esta funcionó muy bien
                $this->llama($numCli,$nombre,$mensaje);
            }
            dispatch(new MarcarMensajeComoLeidoJob($msg_id,$this->token,$this->url));

        } catch (Exception $e) {
            event(new WhatsappEvent("Error inesperado: " . $e->getMessage()));
        }
    }
    public function WitIaEval($numCli,$nombre,$mensaje){
        /* Log::info("pregunta: ".$mensaje);
        $analisis = $this->detectarIntencion($mensaje);
        $respuesta = match ($analisis['intencion']) {
            'saludo' => 'Hola '.$nombre.'. ¿En qué te puedo ayudar?',
            'consulta_horario' => 'Atendemos de 9 a.m. a 6 p.m. de lunes a viernes.',
            'consulta_ubicacion' => 'Estamos ubicados en Guayaquil, cerca del malecón. ¿Quieres que te comparta el mapa?',
            'consulta_precio' => 'Nuestros precios varían según el producto. ¿Cuál te interesa?',
            'pedido_asistencia' => 'Claro, dime cuál es el problema y te ayudo enseguida.',
            'agradecimiento' => '¡Un gusto ayudarte! Si necesitas algo más, aquí estoy.',
            'despedida' => '¡Hasta luego '.$nombre.'! Que tengas un buen día.',
            'consulta_forma_pago' => 'Aceptamos transferencias, efectivo y tarjeta. ¿Cómo prefieres pagar?',
            'confirmacion_pedido' => 'Perfecto, te lo confirmo. Enseguida te paso los detalles.',
            'cancelacion' => 'Listo, hemos cancelado tu pedido. Avísanos si necesitas algo más.',
            'reclamo' => 'Lamento eso. Vamos a solucionarlo lo antes posible. ¿Me das más detalles?',
            'consulta_tiempo_entrega' => 'En Guayaquil entregamos el mismo día. ¿Dónde estás ubicado tú?',
            'consulta_garantia' => 'Sí, ofrecemos garantía. ¿Sobre qué producto necesitas información?',
            'consulta_promocion' => '¡Tenemos varias promos! ¿Qué producto te interesa revisar?',
            'disponibilidad_producto' => function () use ($mensaje) {
                $busqueda = '';
                $texto = Str::lower($mensaje);
                // Buscar productos que coincidan parcial o totalmente en nombre o descripción
                $productos = Producto::whereRaw("LOWER(nombre) LIKE ?", ["%{$texto}%"])
                                    ->orWhereRaw("LOWER(descripcion) LIKE ?", ["%{$texto}%"])
                                    ->get();

                foreach ($productos as $producto) {
                    if ($producto->stock_actual > 0) {
                        $busqueda = "Sí tenemos {$producto->nombre} ({$producto->descripcion}) disponible.\nPrecios: [{$producto->precio_detal} c/u], \npor caja: [{$producto->precio_mayor}] \ncantidad: ({$producto->unidades_por_embalaje}). \n¿Te gustaría que te lo separe?";
                    } else {
                        $busqueda = "{$producto->nombre} está agotado por ahora. ¿Quieres que te avise cuando vuelva?";
                    }
                }

                if($busqueda){
                    return $busqueda;
                }else{
                    return 'Déjame revisar si tenemos stock. ¿Qué producto estás buscando exactamente?';
                }
            },default => 'Lo siento '.$nombre.', no entendí bien. ¿Podrías repetirlo con otras palabras?',
        };

        // Ejecuta la función si es una función anónima
        log::alert("tipo de dato: ".gettype($respuesta));
        $respuesta = is_callable($respuesta) ? $respuesta() : $respuesta;
        $response = response()->json([
                'respuesta' => $respuesta,
                'confianza' => $analisis['confianza'],
                'intencion_detectada' => $analisis['intencion'],
            ]);
        log::info("Respuesta de la IA: ".$respuesta. "- Confianza:[".$analisis['confianza']."] - Intencion: ".$analisis['intencion']);
        //event(new WhatsappEvent($respuesta. "- Confianza:[".$analisis['confianza']."] - Intencion: ".$analisis['intencion']));
        //$this->sendWhatsAppMessageText($numCli,$respuesta);
        return; */
    }

    public function llama($numCli,$nombre,$mensaje)
    {
        try {
            Log::info($nombre. 'Escribe: ', [$mensaje]);
            $instruccion = config("services.ollama.prefix");
            $prompt = $instruccion . ' ' . $mensaje;

            $respuesta = Http::post(config("services.ollama.url"), [
                'model' => config("services.ollama.model"),
                'prompt' => $prompt,
            ]);
            $datos = $respuesta->json();

            if (isset($datos['error'])) {
                throw new \Exception($datos['error']);
            }
            $fragmentos = explode("\n", $respuesta->body());
            $texto = '';
            foreach ($fragmentos as $linea) {
                if (trim($linea) === '') continue;

                $chunk = json_decode($linea, true);
                if (isset($chunk['response'])) {
                    $texto .= $chunk['response'];
                }
            }
            // Si quieres limpiar caracteres mal codificados
            $texto = mb_convert_encoding($texto, 'UTF-8', 'auto');

            if (trim($texto) === '') {
                $texto = 'Respuesta vacía del modelo.';
            }
            Log::info('Responde Ollama: ', [$texto]);
            event(new WhatsappEvent("IA: "." - ".$texto));
            $this->sendWhatsAppMessageText($numCli,$texto);
        } catch (\Throwable $e) {
            Log::error('Error con Ollama: ' . $e->getMessage());
        }
    }
}
