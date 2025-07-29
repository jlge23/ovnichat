<?php

namespace App\Helpers;

class TextCleanerHelp
{
    /**
     * Limpia cadenas JSON que contienen caracteres escapados doblemente,
     * como \\u00f3 → ó
     */
    public static function decodeUnicodeEscapedString(string $rawText): string
    {
        // Eliminar comillas si vienen encapsuladas
        if (str_starts_with($rawText, '"') && str_ends_with($rawText, '"')) {
            $rawText = trim($rawText, '"');
        }

        // Reemplaza doble barra por una sola
        $jsonFormatted = '"' . str_replace('\\\\u', '\\u', $rawText) . '"';

        // Decodifica a texto legible
        $decoded = json_decode($jsonFormatted);

        return $decoded ?? $rawText;
    }

    public static function normalizarTexto($texto) {
        $texto = strtolower($texto); // todo en minúsculas
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto); // elimina tildes
        $texto = preg_replace('/[^\w\s]/u', '', $texto); // elimina signos de puntuación
        $texto = preg_replace('/\s+/', ' ', $texto); // normaliza espacios
        return trim($texto);
    }
}
