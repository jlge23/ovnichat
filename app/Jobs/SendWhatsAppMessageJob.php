<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to, $text, $msgId;

    public function __construct($to, $text, $msgId)
    {
        $this->to = $to;
        $this->text = $text;
        $this->msgId = $msgId;
    }

    public function handle()
    {
        Log::info("Ejecutando SendWhatsAppMessageJob para {$this->to}");
        $data = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $this->to,
            "type" => "text",
            'context' => [
                'message_id' => $this->msgId
            ],
            "text" => ["body" => $this->text]
        ];
        Log::info($data);
        Log::info("Enviando solicitud a WhatsApp...");
        $response = Http::withToken(config('services.whatsapp.token'))
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post(config('services.whatsapp.url'), $data);

        // Validar si la solicitud falló
        if ($response->failed()) {
            Log::error("Error al enviar el mensaje a WhatsApp: [SendWhatsAppMessage] " . $response->body());
        }else{
            Log::info("Enviando solicitud a WhatsApp... [[SendWhatsAppMessage] - Exitoso]");
        }
    }
}
