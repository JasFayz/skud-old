<?php

namespace Modules\HR\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStaffRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'patronymic' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'department_id' => 'nullable|numeric',
//            'division_id' => 'required|numeric',
            'position_id' => 'nullable|numeric',
            'role_id' => 'required|numeric',
            'mobile_phone' => 'nullable|string',
            'work_phone' => 'nullable|string',
            'car_number' => 'nullable|string',
            'devices' => 'nullable|string',
            'photo' => ['nullable', 'image', 'max:512'],
            'watchlist_photo' => ['nullable', 'image', 'max:512'],
            'pinfl' => ['nullable', 'string', 'min:14', 'max:15']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        $this->merge(['company_id' => auth()->user()->company_id]);
    }
}
