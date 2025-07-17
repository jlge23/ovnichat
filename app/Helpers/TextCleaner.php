<?php

namespace App\Helpers;

class TextCleaner
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
}
