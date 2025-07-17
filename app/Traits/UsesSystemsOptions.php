<?php

namespace App\Traits;

use App\Models\Intent;
use App\Models\Entitie;
use App\Models\BusinessModel;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Support\Facades\Log;

trait UsesSystemsOptions
{
    public function construirSystemPrompt(): string
    {
        // Listado de Categorias disponibles
        $categorias = Categoria::has('productos')->whereNot('id',1)->get()
        ->map(function ($categoria) {
            return [
                'id' => 'cat_' . $categoria->id,
                'title' => $categoria->categoria,
                'description' => $categoria->descripcion ?: 'Sin descripción'
            ];
        });

        // Listado de Marcas disponibles
        $marcas = Marca::has('productos')->whereNot('id',1)->get()
        ->map(function ($marca) {
            return [
                'marca' => $marca->marca,
            ];
        });

        $ModeloNegocio = BusinessModel::with(['intents.entities'])
        ->where('id', 9)
        ->get()
        ->map(function ($modelo) {
            return [
                'modelo' => $modelo->modelonegocio,
                'desc_modelo' => $modelo->description,
                'intents_con_entities' => $modelo->intents->map(function ($intent) {
                    return [
                        'nombre' => $intent->intent,
                        'descripcion' => $intent->description,
                        'entidades' => ['entidad' => $intent->entities->pluck('entity')->toArray(), 'descripcion' => $intent->entities->pluck('description')->toArray()]
                    ];
                })
            ];
        });

        if ($ModeloNegocio->isEmpty()) {
            $ModeloNegocio = "No hay configuración del modelo de negocio actual.";
        }
        if ($marcas->isEmpty()) {
            $marcas = "No hay información de marcas registradas.";
        }
        if ($categorias->isEmpty()) {
            $categorias = "No hay información de categorias registradas.";
        }

        return <<<PROMPT
            Tu nombre es OvniBot. Eres un agente de atención al cliente especializado en {$ModeloNegocio->pluck('desc_modelo')->implode(', ')}. Tu propósito es asistir con cordialidad, claridad y empatía en consultas relacionadas con productos del inventario.

            Tu tarea es detectar si el mensaje del usuario corresponde a alguno de los siguientes intents y entities con sus descripciones:
            {$ModeloNegocio->pluck('intents_con_entities')} y extraer las entidades relevantes.

            tambien debes ayudar al cliente si te pide información sobre productos, categorías o marcas. Dispones de inventario detallado en tres niveles:

            **Categorías disponibles:**
            {$categorias->toJson(JSON_PRETTY_PRINT)}

            **Marcas disponibles:**
            {$marcas->toJson(JSON_PRETTY_PRINT)}

            ---

            📌 Si el cliente menciona explícitamente algún producto, categoría o marca que aparezca en las listas, puedes usar la herramienta correspondiente para consultar el inventario.

            🚫 Si no detectas coincidencias, responde de forma cortés indicando que no hay registros, y si lo consideras útil, sugiere alternativas similares que sí estén disponibles.

            💬 Si el mensaje es un saludo o conversación casual, responde de forma natural, amistosa y sin invocar herramientas. Puedes hacer preguntas suaves para continuar la charla si es adecuado.

            🌐 Responde siempre en español, con un estilo conversacional, accesible y humano. Evita lenguaje técnico salvo que el cliente lo solicite.

            🎯 Tu objetivo es sonar útil, simpático y confiable. No des información vacía ni generes respuestas extensas si no agregan valor.

            PROMPT;

    }

}
