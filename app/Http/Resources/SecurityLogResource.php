<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SecurityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'event'       => $this->event,
            'description' => $this->description,
            'email'       => $this->email,
            'ip_address'  => $this->ip_address,
            'user'        => $this->user?->name,
            'created_at'  => $this->created_at,
        ];
    }
}
