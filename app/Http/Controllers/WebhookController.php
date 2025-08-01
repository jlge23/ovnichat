<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\WhatsappEvent;
use App\Jobs\GetWhatsAppAudioJob;
use App\Jobs\MarcarMensajeComoLeidoJob;
use App\Jobs\ProcessEmbeddingJob;
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
            // No es un mensaje del usuario, probablemente es un evento tipo "message_delivered"
            if (!$request->has('entry.0.changes.0.value.messages')) {

                    $val = $request->input('entry.0.changes.0.value');
                    return $this->processStatusMessage($val);
            }
            // Es un mensaje de respuesta de tipo texto
            if ($request->has('entry.0.changes.0.value.messages.0.text')) {
                $mensaje = $request->input('entry.0.changes.0.value.messages.0.text.body');
                $msg_id = $request->input('entry.0.changes.0.value.messages.0.id');

            }

            // Es un mensaje de respuesta de tipo audio
            if ($request->has('entry.0.changes.0.value.messages.0.audio')) {
                $audio = $request->input('entry.0.changes.0.value.messages.0.audio.id');
                $mimetype = $request->input('entry.0.changes.0.value.messages.0.audio.mime_type');
                $timestamp = $request->input('entry.0.changes.0.value.messages.0.timestamp');
                $numCli = $request->input('entry.0.changes.0.value.messages.0.from');
                $msg_id = $request->input('entry.0.changes.0.value.messages.0.id');
                $nombre = $request->input('entry.0.changes.0.value.contacts.0.profile.name');
                dispatch(new MarcarMensajeComoLeidoJob($msg_id));
                dispatch(new GetWhatsAppAudioJob($numCli, $audio, $mimetype, $nombre, $timestamp));
                return;
            }
            // Es un mensaje interactivo
            if ($request->has('entry.0.changes.0.value.messages.0.interactive')) {
                //mensaje tipo list_reply
                if ($request->has('entry.0.changes.0.value.messages.0.interactive.list_reply')) {
                    $mensaje = $request->input('entry.0.changes.0.value.messages.0.interactive.list_reply.id');//id categoria
                    $msg_id = $request->input('entry.0.changes.0.value.messages.0.context.id');
                }
                //mensaje tipo button_reply
                if ($request->has('entry.0.changes.0.value.messages.0.interactive.button_reply')) {
                    $button_reply_id = $request->input('entry.0.changes.0.value.messages.0.interactive.button_reply.id');//id categoria
                    $button_reply_title = $request->input('entry.0.changes.0.value.messages.0.interactive.button_reply.title');//Titulo de boton
                    $msg_id = $request->input('entry.0.changes.0.value.messages.0.context.id');
                }
            }
            $nombre = $request->input('entry.0.changes.0.value.contacts.0.profile.name');
            $numCli = $request->input('entry.0.changes.0.value.contacts.0.wa_id');
            //event(new WhatsappEvent($nombre." dice: ".$mensaje));
            dispatch(new MarcarMensajeComoLeidoJob($msg_id));
            //ProcessOllamaIAJob::dispatch($numCli,$nombre,$mensaje,$msg_id);
            //ProcessWitAIJob::dispatch($numCli,$nombre,$mensaje,$msg_id);
            ProcessEmbeddingJob::dispatch($numCli,$nombre,$mensaje,$msg_id);
        }catch (Exception $e) {
            //event(new WhatsappEvent("Error inesperado: " . $e->getMessage()));
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
