<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;


class ApiMessengerController extends Controller
{
    // Método para la verificación inicial del webhook
    public function verificar(Request $request)
    {
        $token_verificacion = config('services.messenger.local_verify_token');

        if (
            $request->get('hub_mode') === 'subscribe' &&
            $request->get('hub_verify_token') === $token_verificacion
        ) {
            return response($request->get('hub_challenge'), 200);
        }

        return response('Verificación fallida', 403);
    }

    // Método para procesar los eventos entrantes
    public function recibirEvento(Request $request)
    {
        $payload = $request->all();
        Log::info('Evento Messenger recibido: ' . json_encode($payload));

        // Aquí podrías procesar el mensaje, por ejemplo:
        $messaging = $payload['entry'][0]['messaging'][0] ?? null;

        if ($messaging && isset($messaging['message']['text'])) {
            $psid = $messaging['sender']['id'];
            $mensaje = $messaging['message']['text'];

            $this->responderTexto($psid, "¡Gracias por tu mensaje! Escribiste: $mensaje");
        }

        return response('EVENT_RECEIVED', 200);
    }

    // Método para enviar una respuesta al usuario
    private function responderTexto($psid, $mensaje)
    {
        $token = config('services.messenger.token');

        Http::withToken($token)->post(config('services.messenger.url'), [
            'recipient' => ['id' => $psid],
            'message' => ['text' => $mensaje],
        ]);
    }
    //Metodo con tecnica para recibir nuevos mensajes y validarlos en 24 horas
    public function recibirMensaje($datos){
        $psid = $datos['sender']['id']; // o el identificador del usuario
        $mensaje = $datos['message']['text'] ?? null;

        // Revisa si hay un mensaje de este usuario en las últimas 24h
        $ultimo = Mensaje::where('psid', $psid)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->latest()
            ->first();

        $nuevoMensaje = new Mensaje([//falta crear esta tabla
            'plataforma' => 'messenger', // o whatsapp
            'psid' => $psid,
            'mensaje' => $mensaje,
            'tipo_mensaje' => 'texto',
            'leido' => false,
            'inicio_conversacion' => $ultimo ? null : now()
        ]);

        $nuevoMensaje->save();
    }

}
