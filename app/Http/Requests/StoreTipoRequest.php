<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreTipoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Determina las validaciones que se aplican a la solicitud.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255|unique:tipos,nombre',
            'icono' => 'required|string|max:255|regex:/^([a-zA-Z0-9\-\_]+)\.png$/',
        ];
    }
    /**
     * Obtiene mensajes personalizados para las validaciones.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'El nombre debe ser único.',
            'icono.regex' => 'El icono debe ser un archivo .png y solo contener letras, números, guiones y guiones bajos.',
        ];
    }

    // Metodo para personalizar la respuesta de validación fallida
    protected function failedValidation(Validator $validator)
    {
        // Modificar el mensaje global
        $response = response()->json([
            'message' => 'Datos erróneos. Por favor verifica los campos.',
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
