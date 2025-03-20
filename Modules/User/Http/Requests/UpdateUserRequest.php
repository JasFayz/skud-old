<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'password' => 'nullable|string|confirmed',
            'photo' => 'nullable|image|max:512',
            'pinfl' => ['nullable', 'string', 'min:14', 'max:15']
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
