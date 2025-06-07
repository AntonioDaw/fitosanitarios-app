<?php

namespace App\Http\Requests;

use App\Models\UnidadProducto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AplicacionRequest extends FormRequest
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
    public function rules()
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'tratamiento_id' => ['required', 'exists:tratamientos,id'],
            'litros' => ['required', 'numeric', 'min:0.01'],
            'sectors' => ['required', 'array', 'min:1'],
            'sectors.*.id' => ['required', 'exists:sectors,id'],
            'sectors.*.litros_aplicados' => ['required', 'numeric', 'min:0.01'],
            'unidad_productos' => ['required', 'array', 'min:1'],
            'unidad_productos.*.id' => ['required', 'exists:unidad_productos,id'],
            'unidad_productos.*.estado' => ['required', 'integer'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->input('unidad_productos', []) as $index => $item) {
                $unidad = UnidadProducto::find($item['id']);
                if ($unidad && $unidad->estado >= $item['estado']) {
                    $validator->errors()->add(
                        "unidad_productos.$index.estado",
                        "No puedes revertir el estado hacia atras."
                    );
                }
            }
        });
    }
    public function messages()
    {
        return [
            // USUARIO
            'user_id.required' => 'Debes seleccionar un responsable de la aplicación.',
            'user_id.exists' => 'El responsable seleccionado no es válido.',

            // TRATAMIENTO
            'tratamiento_id.required' => 'Debes indicar qué tratamiento se aplicó.',
            'tratamiento_id.exists' => 'El tratamiento seleccionado no es válido.',

            // LITROS
            'litros.required' => 'Debes indicar la cantidad total de litros aplicados.',
            'litros.numeric' => 'La cantidad de litros debe ser un valor numérico.',
            'litros.min' => 'La cantidad de litros debe ser mayor que cero.',

            // SECTORES
            'sectors.required' => 'Debes seleccionar al menos un sector donde se aplicó el tratamiento.',
            'sectors.array' => 'El formato de los sectores no es válido.',
            'sectors.min' => 'Debes seleccionar al menos un sector.',
            'sectors.*.id.required' => 'Falta uno de los sectores seleccionados.',
            'sectors.*.id.exists' => 'Uno de los sectores seleccionados no es válido.',
            'sectors.*.litros_aplicados.required' => 'Debes indicar cuántos litros se aplicaron en cada sector.',
            'sectors.*.litros_aplicados.numeric' => 'Los litros aplicados en cada sector deben ser un número.',
            'sectors.*.litros_aplicados.min' => 'Los litros aplicados deben ser mayores que cero.',

            // UNIDADES DE PRODUCTO
            'unidad_producto.required' => 'Debes añadir al menos una unidad de producto utilizada.',
            'unidad_productos.array' => 'El formato de las unidades de producto no es válido.',
            'unidad_productos.min' => 'Debes incluir al menos una unidad de producto.',
            'unidad_productos.*.id.required' => 'Falta una de las unidades de producto seleccionadas.',
            'unidad_productos.*.id.exists' => 'Una de las unidades de producto no es válida.',
            'unidad_productos.*.estado.required' => 'Debes indicar el estado en que quedó cada unidad de producto.',
            'unidad_productos.*.estado.integer' => 'El estado de cada unidad debe ser un número válido.',
        ];
    }

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
