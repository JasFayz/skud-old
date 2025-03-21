<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Rules\CurrentPasswordRule;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'current_password' => ['required', new CurrentPasswordRule()],
            'password' => 'required|confirmed'
        ];
    }
}
