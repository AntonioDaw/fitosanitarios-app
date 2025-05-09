<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreParcelaRequest extends FormRequest
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
            'nombre' => 'required|string|max:255|unique:parcelas,nombre',
            'numero_parcela' => 'required|integer|min:1|max:999|unique:parcelas,numero_parcela',
            'area' => 'required|numeric|gt:0',
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
            'numero_parcela.required' => 'El numero de parcela es obligatorio.',
            'numero_parcela' => 'El numero de parcela debe ser único.',
            'area.required' => 'El area es obligatorio.',
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
