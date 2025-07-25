<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Intent;
use Cloudstudio\Ollama\Facades\Ollama;
use App\Traits\UsesOllamaOptions;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use App\Traits\UsesSystemsOptions;
use Illuminate\Support\Str;
use App\Helpers\SecureInputIAHelper;
use App\Models\BusinessModel;

class TestController extends Controller{

    use UsesOllamaOptions, UsesSystemsOptions;

    public function index(){
        return view('test');
    }

    public function testia(Request $request){
        $tools = [
            [
                "type" => "function",
                "function" => [
                    "name" => "disponibilidad_producto",
                    "description" => "Consulta información de productos",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "producto" => [
                                "type" => "string",
                                "description" => "Intent para ver productos"
                            ],
                        ],
                        "required" => ["producto"],
                    ]
                ]
            ],
        ];
        $msg = SecureInputIAHelper::sanitizarMensaje($request->input('msg'));
        if (!SecureInputIAHelper::entradaSegura($msg)) {
            $output = 'Mensaje bloqueado por seguridad. Intenta usar lenguaje natural.';
            return view('test', compact('output'));
        }

        $modoTecnico = Str::contains(Str::lower($msg), ['producto','productos']);//falta el filtro debido para que se dispare el tools
        $response = Ollama::agent($this->construirSystemPrompt())
        ->model('llama3.2')
        ->options($this->ollamaOptions());
        if ($modoTecnico) {
            $response->tools($tools);
        }
        $response = $response->chat([
            ['role' => 'user', 'content' => $msg]
        ]);
        if (!empty($response['message']['tool_calls'])) {
            foreach ($response['message']['tool_calls'] as $toolCall) {
                if ($toolCall['function']['name'] === 'disponibilidad_producto') {
                    $args = $toolCall['function']['arguments'];
                    $resultado = Producto::disponibilidad_producto(
                            $args['producto'] ?? null,
                    );
                    //$output = $resultado;
                    $output = json_encode($response);
                    return $output;
                    return view('test', compact('output'));
                }
            }
        }else{
            $output = json_encode($response);
            //$output = $response['message']['content'];
            return $output;
            return view('test', compact('output'));
        }

    }

}
