<?php

namespace App\Http\Controllers;

use App\Models\Canale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CanaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /* public function index()
    {
        $instruccion = config("services.ollama.prefix");
            $prompt = "conoces algo sobre la biblia en hebreo?";

            $respuesta = Http::post(config("services.ollama.url"), [
                'model' => config("services.ollama.model"),
                'prompt' => $prompt,
            ]);
            $fragmentos = explode("\n", $respuesta->body());
            $texto = '';
            foreach ($fragmentos as $linea) {
                if (trim($linea) === '') continue;

                $chunk = json_decode($linea, true);
                if (isset($chunk['response'])) {
                    $texto .= $chunk['response'];
                }
            }
            return $texto;

    } */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Canale $canale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Canale $canale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Canale $canale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Canale $canale)
    {
        //
    }
}
