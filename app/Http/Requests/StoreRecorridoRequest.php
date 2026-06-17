<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecorridoRequest extends FormRequest
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
            'codigo'              => ['nullable', 'string', 'max:30', Rule::unique('recorridos', 'codigo')],
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
            'nombre.max'               => 'El nombre no puede superar 160 caracteres.',
            'codigo.unique'            => 'Ese código de recorrido ya está en uso.',
            'origen.required'          => 'El origen es obligatorio.',
            'destino.required'         => 'El destino es obligatorio.',
            'category_id.exists'       => 'La categoría seleccionada no existe.',
            'tarifa_bs.min'            => 'La tarifa no puede ser negativa.',
            'paradas.required'         => 'Debes registrar al menos una parada.',
            'paradas.min'              => 'Debes registrar al menos una parada.',
            'paradas.*.nombre.required' => 'Cada parada necesita un nombre.',
            'paradas.*.nombre.max'     => 'El nombre de la parada no puede superar 120 caracteres.',
            'paradas.*.latitud.between' => 'La latitud debe estar entre -90 y 90.',
            'paradas.*.longitud.between' => 'La longitud debe estar entre -180 y 180.',
        ];
    }
}
