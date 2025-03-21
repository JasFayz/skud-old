<?php

namespace Modules\Visitor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateGuestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', "regex:/(^[A-Za-z']+$)+/"],
            'last_name' => ['required', 'string', "regex:/(^[A-Za-z']+$)+/"],
            'patronymic' => ['nullable', 'string', "regex:/(^[A-Za-z ']+$)+/"],
            'company_name' => ['required', 'string'],
            'phone_number' => ['nullable', 'string'],
            'passport_number' => [
                Rule::excludeIf(filter_var($this->is_vip, FILTER_VALIDATE_BOOLEAN)),
                Rule::requiredIf((bool)filter_var($this->is_vip, FILTER_VALIDATE_BOOLEAN)), 'unique:guests,passport_number',
                (filter_var(!$this->is_vip, FILTER_VALIDATE_BOOLEAN) ? 'regex:/[A-Z]{2}[0-9]{7}/i' : 'nullable')
            ],
            'is_vip' => ['boolean'],
//            'photo' => ['sometimes', 'image'],

        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_vip' => filter_var($this->get('is_vip'), FILTER_VALIDATE_BOOLEAN),

        ]);
    }

    public function messages()
    {
        return [
            'first_name.regex' => 'Поле :attribute должно содержать только латинские буквы',
            'last_name.regex' => 'Поле :attribute должно содержать только латинские буквы',
            'patronymic.regex' => 'Поле :attribute должно содержать только латинские буквы',
            'passport_number.unique' => 'Серия паспорта уже существует',
            'passport_number.regex' => 'Неправильный формат серии паспорта'
        ]; // TODO: Change the autogenerated stub
    }
}
