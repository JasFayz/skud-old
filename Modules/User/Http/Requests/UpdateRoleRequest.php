<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'grade' => 'required|min:1|max:9',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
