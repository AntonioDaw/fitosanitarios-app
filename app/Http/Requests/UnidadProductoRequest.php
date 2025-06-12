<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UnidadProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    if ($this->isMethod('post')) {
        // Reglas para crear (POST)
        return [
            'producto_id' => ['required', 'exists:productos,id'],
            'proveedor_id' => ['required', 'exists:proveedors,id']
        ];
    }

    if ($this->isMethod('put') || $this->isMethod('patch')) {
        // Reglas para actualizar (PUT/PATCH) solo estado
        return [
            'estado' => ['required', 'integer', Rule::in([0,1,2])],
        ];
    }

    // Por defecto, no reglas
    return [];
}

    public function messages(): array
    {
        return [
            'producto_id.required' => 'Debe indicar a qué producto pertenece.',
            'producto_id.exists' => 'El producto seleccionado no es válido.',

            'proveedor_id.required' => 'Debe indicar un proveedor.',
            'proveedor_id.exists' => 'El proveedor seleccionado no es válido.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser valido.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'message' => 'Datos erróneos. Por favor verifica los campos.',
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
