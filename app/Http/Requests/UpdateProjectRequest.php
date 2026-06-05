<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'        => ['required', 'integer', 'exists:categories,id'],
            'title'              => ['required', 'string', 'max:160'],
            'slug'               => ['nullable', 'string', 'max:180', Rule::unique('projects', 'slug')->ignore($this->route('project'))],
            'origin'             => ['required', 'string', 'max:120'],
            'destination'        => ['required', 'string', 'max:120'],
            'description'        => ['required', 'string'],
            'status'             => ['nullable', 'string', Rule::in(['planificado', 'activo', 'pausado', 'archivado'])],
            'estimated_minutes'  => ['nullable', 'integer', 'min:1'],
            'distance_km'        => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'    => 'La categoría es obligatoria.',
            'category_id.exists'      => 'La categoría seleccionada no existe.',
            'title.required'          => 'El título del proyecto es obligatorio.',
            'title.max'               => 'El título no puede superar 160 caracteres.',
            'slug.unique'             => 'Ese slug ya está en uso.',
            'origin.required'         => 'El origen es obligatorio.',
            'destination.required'    => 'El destino es obligatorio.',
            'description.required'    => 'La descripción es obligatoria.',
            'status.in'               => 'El estado debe ser planificado, activo, pausado o archivado.',
            'estimated_minutes.min'   => 'Los minutos estimados deben ser al menos 1.',
            'distance_km.min'         => 'La distancia no puede ser negativa.',
        ];
    }
}
