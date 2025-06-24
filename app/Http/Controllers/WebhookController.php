<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\WhatsappEvent;
use App\Jobs\MarcarMensajeComoLeidoJob;
use App\Jobs\ProcessOllamaIAJob;
use App\Jobs\ProcessWitAIJob;
use Illuminate\Support\Facades\Log;
use Exception;

class WebhookController extends Controller
{
    public function verify(Request $request){
        try {
            $msg = $request->query();
            $mode = $msg["hub_mode"];
            $challenge = $msg["hub_challenge"];
            $verify_token = $msg["hub_verify_token"];
            $local_verify_token = config('services.whatsapp.local_verify_token');
            if(isset($local_verify_token) && isset($verify_token)){
                if($mode == 'subscribe' && $local_verify_token === $verify_token){
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

    // WebHook WhatsApp
    public function handleWhatsapp(Request $request){
        try {
            if (!$request->has('entry.0.changes.0.value.messages')) {
                    // No es un mensaje del usuario, probablemente es un evento tipo "message_delivered"
                    $val = $request->input('entry.0.changes.0.value');
                    return $this->processStatusMessage($val);
            }
            $mensaje = $request->input('entry.0.changes.0.value.messages.0.text.body');
            $msg_id = $request->input('entry.0.changes.0.value.messages.0.id');
            $nombre = $request->input('entry.0.changes.0.value.contacts.0.profile.name');
            $numCli = $request->input('entry.0.changes.0.value.contacts.0.wa_id');
            event(new WhatsappEvent($nombre." dice: ".$mensaje));
            //Log::info($nombre." dice: ".$mensaje);
            dispatch(new MarcarMensajeComoLeidoJob($msg_id));
            //ProcessOllamaIAJob::dispatch($numCli,$nombre,$mensaje);
            ProcessWitAIJob::dispatch($numCli,$nombre,$mensaje);
        }catch (Exception $e) {
            event(new WhatsappEvent("Error inesperado: " . $e->getMessage()));
        }
    }

    // Metodo manejará los mensajes de estado que WhatsApp envía
    private function processStatusMessage($val){
        $timestamp = data_get($val, 'statuses.0.timestamp');
        $status = data_get($val, 'statuses.0.status');
        if ($timestamp && $status) {
            $fecha = date("Y-m-d H:i:s", $timestamp);
            $mensaje = "Estado del mensaje: $status - Fecha: $fecha";
            //event(new WhatsappEvent($mensaje));
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

    // WebHook Messenger
    public function handleMessenger(Request $request){

    }
}
