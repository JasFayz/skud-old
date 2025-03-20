<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'email' => ['required', 'email',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('email', $this->email)
                        ->where('company_id', $this->company_id);
                })],
            'first_name' => 'required|string|alpha|max:255|regex:/(^[A-Za-z ]+$)+/',
            'last_name' => 'required|string|alpha|max:255|regex:/(^[A-Za-z ]+$)+/',
            'patronymic' => ['nullable', 'string', 'max:255', 'regex:/(^[A-Za-z \']+$)+/'],
            'role_id' => 'required|numeric',
            'photo' => 'nullable|image|max:512',
            'pinfl' => ['nullable', 'string', 'min:14', 'max:15']
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function messages()
    {
        return [
            'first_name.regex' => 'The :attribute should contains letter',
            'last_name.regex' => 'The :attribute should contains letter',
            'patronymic.regex' => 'The :attribute should contains letter'
        ];
    }

    public function prepareForValidation()
    {
        return $this->merge([
            'company_id' => $this->company_id ?? $this->user()->copmany_id
        ]);
    }
}
