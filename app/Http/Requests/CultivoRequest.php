<?php

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CultivoRequest extends FormRequest
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
        return [
                'nombre' => 'required|string|max:255',
                'tipo_id' => 'required|exists:tipos,id', // valida que el tipo exista
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
            'nombre' => 'required|string|max:255|unique:cultivos,nombre',
            'nombre.unique' => 'El nombre debe ser único.',
            'tipo_id.required' => 'El tipo es obligatorio.',
            'tipo_id.exists' => 'El tipo no existe.',
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
