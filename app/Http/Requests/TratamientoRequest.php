<?php

namespace App\Http\Requests;

use App\Models\Cultivo;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class TratamientoRequest extends FormRequest
{
    public function authorize(): bool
    {

        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_id' => ['required', 'exists:tipos,id'],
            'descripcion' => ['required', 'string'],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*.id' => ['required', 'integer', 'exists:productos,id'],
            'productos.*.cantidad_por_100_litros' => ['required', 'numeric', 'min:0.01'],

            'cultivos' => ['required', 'array', 'min:1'],
            'cultivos.*' => ['integer', 'exists:cultivos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_id.required' => 'El tipo de cultivo es obligatorio.',
            'tipo_id.exists' => 'El tipo de cultivo seleccionado no es válido.',

            'descripcion.string' => 'La descripción debe ser un texto.',
            'descripcion.required' => 'Descripcion es obligatorio.',

            'productos.required' => 'Debe incluir al menos un producto.',
            'productos.array' => 'El campo productos debe ser una lista.',
            'productos.*.id.required' => 'Cada producto debe tener un ID.',
            'productos.*.id.integer' => 'El ID del producto debe ser un número entero.',
            'productos.*.id.exists' => 'Uno o más productos seleccionados no existen.',
            'productos.*.cantidad_por_100_litros.required' => 'Debe indicar la cantidad por 100 litros para cada producto.',
            'productos.*.cantidad_por_100_litros.numeric' => 'La cantidad debe ser un número.',
            'productos.*.cantidad_por_100_litros.min' => 'La cantidad debe ser mayor a 0.',

            'cultivos.required' => 'Debe seleccionar al menos un cultivo.',
            'cultivos.array' => 'El campo cultivos debe ser una lista.',
            'cultivos.*.exists' => 'Uno o más cultivos seleccionados no existen.',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tipoId = $this->input('tipo_id');
            $cultivos = $this->input('cultivos', []);

            if ($tipoId && is_array($cultivos)) {
                $invalidCultivos = Cultivo::whereIn('id', $cultivos)
                    ->where('tipo_id', '!=', $tipoId)
                    ->pluck('id');

                if ($invalidCultivos->isNotEmpty()) {
                    $validator->errors()->add(
                        'cultivos',
                        'Algunos cultivos seleccionados no pertenecen al tipo de cultivo indicado.'
                    );
                }
            }
        });
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
