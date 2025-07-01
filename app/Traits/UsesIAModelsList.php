<?php

namespace App\Traits;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

trait UsesIAModelsList
{
    public function modelosLocales(){
        $proceso = new Process(['ollama', 'list'], null, [
            'USERPROFILE' => getenv('USERPROFILE') ?: 'C:\\Users\\ASUS'
        ]);
        $proceso->run();

        if (!$proceso->isSuccessful()) {
            throw new ProcessFailedException($proceso);
        }

        $salida = $proceso->getOutput();
        $modelos = explode("\n", trim($salida));

        $nombres = collect($modelos)
        ->skip(1)
        ->map(function ($linea) {
            return explode(' ', trim($linea))[0];
        })
        ->values()
        ->toArray();
        foreach($nombres as $modelo){
            $data['modelos'][]['name'] = $modelo;
        }
        //return response()->json($nombres);
        return $nombres;
    }
}



