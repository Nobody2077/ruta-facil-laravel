<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecorridoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'nombre'       => $this->nombre,
            'codigo'       => $this->codigo,
            'origen'       => $this->origen,
            'destino'      => $this->destino,
            'descripcion'  => $this->descripcion,
            'tarifa_bs'    => $this->tarifa_bs,
            'activo'       => $this->activo,
            'paradas_count' => $this->whenCounted('paradas'),
            'category'     => new CategoryResource($this->whenLoaded('category')),
            'paradas'      => ParadaResource::collection($this->whenLoaded('paradas')),
        ];
    }
}
