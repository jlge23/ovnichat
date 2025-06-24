<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class MarcarMensajeComoLeidoJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $mensajeId;
    public $token;
    public $url;

    public function __construct(string $mensajeId)
    {
        $this->mensajeId = $mensajeId;
        $this->token = config('services.whatsapp.token');
        $this->url = config('services.whatsapp.url');
    }

    public function handle(): void
    {
        Http::withToken($this->token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($this->url, [
                'messaging_product' => 'whatsapp',
                'status' => 'read',
                'message_id' => $this->mensajeId
        ]);
    }
}
