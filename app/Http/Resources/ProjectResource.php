<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'origin'             => $this->origin,
            'destination'        => $this->destination,
            'status'             => $this->status,
            'estimated_minutes'  => $this->estimated_minutes,
            'distance_km'        => $this->distance_km,
            'category'           => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
