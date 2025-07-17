<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppInteractiveButtonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to, $header, $title, $buttons;

    public function __construct($to, $header, $title, $buttons)
    {
        $this->to = $to;
        $this->header = $header;
        $this->title = $title;
        $this->buttons = $buttons;
    }

    public function handle()
    {
        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $this->to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => [
                    'text' => '¿En qué puedo ayudarte hoy?'
                ],
                'action' => [
                    'buttons' => [
                        [
                            'type' => 'reply',
                            'reply' => [
                                'id' => $this->title,
                                'title' => $this->buttons
                            ]
                        ],
                    ]
                ]
            ]
        ];


/* {
  'type': 'button_reply',
  'button_reply': {
    'id': 'opcion_2',
    'title': 'Hablar con asesor'
  }
} */

        $response = Http::withToken(config('services.whatsapp.token'))
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post(config('services.whatsapp.url'), $data);

        // Validar si la solicitud falló
        if ($response->failed()) {
            Log::error('Error al enviar el mensaje a WhatsApp: [SendWhatsAppInteractiveListJob] ' . $response->body());
        }else{
            Log::info('Enviando solicitud a WhatsApp... [[SendWhatsAppInteractiveListJob] - Exitoso]');
        }
    }
}
