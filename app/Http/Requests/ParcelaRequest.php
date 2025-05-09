<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ParcelaRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $parcelaId = $this->route('id'); // o 'id' según cómo se llama en tu ruta

        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('parcelas', 'nombre')->ignore($parcelaId),
            ],
            'numero_parcela' => [
                'required',
                'integer',
                'min:1',
                'max:999',
                Rule::unique('parcelas', 'numero_parcela')->ignore($parcelaId),
            ],
            'area' => [
                'required',
                'numeric',
                'gt:0',
            ],
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
