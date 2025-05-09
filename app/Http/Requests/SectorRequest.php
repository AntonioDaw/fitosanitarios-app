<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sectorId = $this->route('id');

        return [
            'numero_sector' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('sectors')
                    ->where(function ($query) {
                        return $query->where('parcela_id', $this->input('parcela_id'));
                    })
                    ->ignore($sectorId),
            ],
            'parcela_id' => [
                'required',
                'exists:parcelas,id',
            ],
        ];
    }
}
