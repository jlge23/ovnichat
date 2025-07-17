<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->codigo_sku,
            'producto' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio_detal' => $this->precio_detal,
            'precio_mayor' => $this->precio_mayor,
            'stock' => $this->stock_actual,
            'unidad' => $this->unidad_medida,
            'categoria' => optional($this->categoria)->nombre,
            'proveedor' => optional($this->proveedor)->nombre,
            'embalaje' => optional($this->embalaje)->tipo_embalaje,
            'active' => $this->active
        ];

    }
}
