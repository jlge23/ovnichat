<?php

namespace App\Http\Controllers;

use App\Jobs\Llama32Job;
use App\Jobs\PhiJob;
use App\Jobs\ProcessGemmaIAJob;
use App\Models\Mensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\UsesOllamaOptions;
use App\Traits\UsesIAModelsList;


class MensajeController extends Controller
{
    use UsesOllamaOptions, UsesIAModelsList;

    public function empezar(){ //la inicia GEMMA2B
        $mensaje = "Hola, como estas?, que opinas sobre las guerras entre hombres? se breve en tu respuestas";
        //Llama32Job::dispatch('GEMMA2B',$mensaje);
        PhiJob::dispatch('GEMMA2B',$mensaje);
    }
    public function terminar(){
        $mensaje = "Gracias por la conversacion, debo irme. Hasta pronto!";
        //Llama32Job::dispatch('GEMMA2B',$mensaje);
        PhiJob::dispatch('GEMMA2B',$mensaje);
    }
    public function llama(Request $request){
        Log::info($request->input('prompt'));

        $respuesta = json_decode(Http::timeout(100)->post(config("services.ollama.url"), [
                'model'  => $request->input('model'),
                'prompt' => "Human: ".$request->input('prompt')."\nAssistant:",
                'system' => config('services.ollama.prefix'),
                'stream' => false,
                'options' => $this->ollamaOptions()
        ]),true);
        Log::info($respuesta['response']);
        $r = $respuesta['response'];
        $modelos = $this->modelosLocales();
        return view('welcome', compact('r','modelos'));
    }
}
