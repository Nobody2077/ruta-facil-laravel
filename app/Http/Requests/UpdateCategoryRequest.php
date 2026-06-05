<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100'],
            'slug'        => ['nullable', 'string', 'max:120', Rule::unique('categories', 'slug')->ignore($this->route('category'))],
            'color'       => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.max'      => 'El nombre no puede superar 100 caracteres.',
            'slug.unique'   => 'Ese slug ya está en uso.',
            'color.max'     => 'El color no puede superar 20 caracteres.',
        ];
    }
}
