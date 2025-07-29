<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppInteractiveListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to, $header, $body, $footer, $list, $msgId;

    public function __construct($to, $header, $body, $footer, $list, $msgId)
    {
        $this->to = $to;
        $this->header = $header;
        $this->body = $body;
        $this->footer = $footer;
        $this->list = $list;
        $this->msgId = $msgId;
    }

    public function handle()
    {
        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $this->to,
            'type' => 'interactive',
            'context' => [
                'message_id' => $this->msgId
            ],
            'interactive' => [
                'type' => 'list',
                'header' => [
                    'type' => 'text',
                    'text' => $this->header
                ],
                'body' => [
                    'text' => $this->body
                ],
                'footer' => [
                    'text' => $this->footer
                ],
                'action' => [
                    'button' => 'Ver',
                    'sections' => [
                        [
                            'title' => $this->header,
                            'rows' => $this->list
                        ]
                    ]
                ]
            ]
        ];
        $response = Http::withToken(config('services.whatsapp.token'))
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post(config('services.whatsapp.url'), $data);

        // Validar si la solicitud falló
        if ($response->failed()) {
            Log::error("Error al enviar el mensaje a WhatsApp: [SendWhatsAppInteractiveListJob] " . $response->body());
        }else{
            Log::info("Enviando solicitud a WhatsApp... [[SendWhatsAppInteractiveListJob] - Exitoso]");
        }
    }
}
