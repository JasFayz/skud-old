<?php

namespace Modules\Skud\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZoneRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'is_calc_attend' => 'required|bool',
            'zone_type' => 'required|string',
            'floor_id' => 'nullable|numeric',
            'terminals' => 'required|array|min:1',
            'company_id' => 'nullable|numeric'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
