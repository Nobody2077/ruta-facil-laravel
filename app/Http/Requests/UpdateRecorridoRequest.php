<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecorridoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'         => ['nullable', 'integer', 'exists:categories,id'],
            'nombre'              => ['required', 'string', 'max:160'],
            'codigo'              => ['nullable', 'string', 'max:30', Rule::unique('recorridos', 'codigo')->ignore($this->route('recorrido'))],
            'origen'              => ['required', 'string', 'max:120'],
            'destino'             => ['required', 'string', 'max:120'],
            'descripcion'         => ['nullable', 'string'],
            'tarifa_bs'           => ['nullable', 'numeric', 'min:0'],
            'activo'              => ['nullable', 'boolean'],

            // Detalle: al menos una parada
            'paradas'             => ['required', 'array', 'min:1'],
            'paradas.*.orden'     => ['nullable', 'integer', 'min:1'],
            'paradas.*.nombre'    => ['required', 'string', 'max:120'],
            'paradas.*.referencia' => ['nullable', 'string', 'max:160'],
            'paradas.*.latitud'   => ['nullable', 'numeric', 'between:-90,90'],
            'paradas.*.longitud'  => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'          => 'El nombre del recorrido es obligatorio.',
            'codigo.unique'            => 'Ese código de recorrido ya está en uso.',
            'origen.required'          => 'El origen es obligatorio.',
            'destino.required'         => 'El destino es obligatorio.',
            'paradas.required'         => 'Debes registrar al menos una parada.',
            'paradas.min'              => 'Debes registrar al menos una parada.',
            'paradas.*.nombre.required' => 'Cada parada necesita un nombre.',
        ];
    }
}
