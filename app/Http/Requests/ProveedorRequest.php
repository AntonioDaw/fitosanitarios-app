<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ProveedorRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        $proveedorId = $this->route('proveedor'); // asegúrate del nombre usado en la ruta (puede ser 'id' también)


        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('proveedors', 'nombre')->ignore($proveedorId),
                'regex:/^[\pL\s\-\.\']+$/u' // Letras, espacios, guiones, puntos, comillas
            ],
            'nif' => [
                'required',
                'string',
                'max:20',
                'regex:/^([KLMXYZ]\d{7}[A-Z]|[ABCDEFGHJNPQRSUVW]\d{7}[0-9A-J]|[0-9]{8}[A-Z])$/i',
                Rule::unique('proveedors', 'nif')->ignore($proveedorId),
            ],
            'direccion' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\w\s\-\.,ºª°#]+$/u', // Letras, números y caracteres comunes de direcciones
            ],
            'telefono' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^\+?[0-9\s\-\(\)]+$/', // Teléfonos internacionales, con paréntesis, espacios o guiones
            ],
            'email' => [
                'nullable',
                'email:rfc,dns', // Validación más estricta (verifica el formato + dominio válido)
                'max:100',
            ],
            'contacto' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[\pL\s\-\.]+$/u',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe un proveedor con ese nombre.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios, guiones y puntos.',

            'nif.required' => 'El NIF es obligatorio.',
            'nif.unique' => 'Ya existe un proveedor con ese NIF.',
            'nif.regex' => 'El NIF tiene que tener este formato 12345678A.',

            'direccion.regex' => 'La dirección contiene caracteres no permitidos.',
            'telefono.regex' => 'El teléfono no es válido.',
            'email.email' => 'El correo electrónico no es válido.',
            'contacto.regex' => 'El nombre del contacto contiene caracteres no válidos.',
        ];
    }


    /**
     * Personaliza la respuesta cuando la validación falla.
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'message' => 'Datos erróneos. Por favor verifica los campos.',
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
