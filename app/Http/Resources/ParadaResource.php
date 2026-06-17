<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParadaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'orden'      => $this->orden,
            'nombre'     => $this->nombre,
            'referencia' => $this->referencia,
            'latitud'    => $this->latitud,
            'longitud'   => $this->longitud,
        ];
    }
}
