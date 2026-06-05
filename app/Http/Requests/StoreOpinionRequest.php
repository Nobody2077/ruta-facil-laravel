<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOpinionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'name'       => ['required', 'string', 'max:120'],
            'route'      => ['required', 'string', 'max:160'],
            'message'    => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El nombre es obligatorio.',
            'name.max'           => 'El nombre no puede superar 120 caracteres.',
            'route.required'     => 'La ruta o zona es obligatoria.',
            'route.max'          => 'La ruta no puede superar 160 caracteres.',
            'message.required'   => 'La opinion no puede estar vacia.',
            'message.max'        => 'La opinion no puede superar 2000 caracteres.',
            'project_id.exists'  => 'El proyecto seleccionado no existe.',
        ];
    }
}
