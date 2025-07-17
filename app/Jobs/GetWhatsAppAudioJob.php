<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\WhatsappEvent;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Type\Integer;

class GetWhatsAppAudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $telefono;
    protected string $audioId;
    protected string $mimetype;
    protected string $nombre;
    protected $timestamp;

    public function __construct(string $telefono, string $audioId, string $mimetype, string $nombre, $timestamp)
    {
        $this->telefono = $telefono;
        $this->audioId = $audioId;
        $this->mimetype = $mimetype;
        $this->nombre = $nombre;
        $this->timestamp = $timestamp;
    }


    public function handle(): void
    {
        /* // Obtenemos los datos del contacto
        $url = config('services.whatsapp.url') . config('services.whatsapp.version') . "/" .config('services.whatsapp.phone_id'). "/contacts";
        $contactResponse = Http::withToken(config('services.whatsapp.token'))
        ->get($url, [
            'contacts' => $this->telefono
        ]);
        $contactData = $contactResponse->json();
        if (isset($contactData['contacts'][0]['profile']['name'])) {
            $nombre = $contactData['contacts'][0]['profile']['name'];
        } else {
            $nombre = 'Desconocido';
        } */

        // Obtenemos el URL del archivo
        $mediaInfo = Http::withToken(config('services.whatsapp.token'))
        ->get(config('services.whatsapp.urlAudio').$this->audioId);
        $mediaUrl = $mediaInfo['url'];
        Log::info($mediaUrl);
        // Descargar el audio

        $audioBinary = Http::withToken(config('services.whatsapp.token'))
            ->withHeaders(['Accept' => 'application/octet-stream'])
            ->get($mediaUrl)->body();
        // Guardar audio en el storage audio
        $extensiones = [
            'audio/ogg' => 'ogg',
            'audio/mpeg' => 'mp3',
            'audio/amr' => 'amr',
            'audio/wav' => 'wav',
        ];

        $extension = $extensiones[$this->mimetype] ?? 'bin';
        $audioFilename = "audio_".$this->timestamp."_".$this->telefono.".".$extension;
        Storage::disk('audio')->put($audioFilename, $audioBinary);
        Log::info($audioFilename);
        // enviar audio a Whisper para convertirlo a texto
        $ruta = Storage::disk('audio')->path($audioFilename); // Ruta completa al archivo
        // 🧪 Verificar si el archivo existe
        if (!file_exists($ruta)) {
            throw new \Exception("El archivo de audio no existe: $ruta");
        }
        Log::info($ruta);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', config('services.ollama.urlGenerate'), [
            'multipart' => [
                [
                    'name' => 'model',
                    'contents' => config('services.ollama.whisper')
                ],
                [
                    'name' => 'audio',
                    'contents' => fopen($ruta, 'r'),
                    'filename' => $audioFilename
                ]
            ]
        ]);

        if (!$response) {
            throw new \Exception("Error al conectar con Whisper: " . $response->body());
        }
        Log::info($response);
        //$transcripcion = $response->json();
        //event(new WhatsappEvent($nombre." dice: ".$mensaje));

    }
}
