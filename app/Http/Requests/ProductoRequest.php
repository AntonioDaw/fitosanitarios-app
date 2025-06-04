<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // O cambia la lógica de autorización si quieres
    }

    public function rules(): array
    {
        $productoId = $this->route('producto');

        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[0-9a-zA-ZÀ-ÿ\s]+$/u',
                Rule::unique('productos', 'nombre')->ignore($productoId),
            ],
            'sustancia_activa' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9À-ÿ\s,.-]+$/u'
            ],
            'presentacion' => [
                'nullable',
                'string',
                'in:polvo,grano,liquido'
            ],
            'uso' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'precio' => [
                'required',
                'numeric',
                'min:0',
            ],
            'estado' => [
                'required',
                'boolean'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre es demasiado largo (máx 255).',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.unique' => 'El nombre ya existe.',

            'sustancia_activa.required' => 'La sustancia activa es obligatoria.',
            'sustancia_activa.string' => 'La sustancia activa debe ser texto.',
            'sustancia_activa.max' => 'La sustancia activa es demasiado larga (máx 255).',
            'sustancia_activa.regex' => 'La sustancia activa solo puede contener letras, números, espacios, coma, punto o guion.',

            'presentacion.in' => 'La presentación debe ser: polvo, grano o liquido.',

            'uso.string' => 'El uso debe ser texto.',
            'uso.max' => 'El uso es demasiado largo (máx 1000).',

            'precio.required' => 'El precio es obligatorio.',
            'precio.regex' => 'El precio debe ser un número válido con hasta dos decimales.',
            'precio.min' => 'El precio debe ser cero o mayor.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.boolean' => 'El estado debe ser verdadero o falso.',
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
