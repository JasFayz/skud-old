<?php

namespace Modules\Skud\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TerminalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'ip' => 'required|ip',
            'floor' => 'nullable|numeric',
            'port' => 'nullable|numeric|',
            'mode' => 'required|boolean|',
            'token' => 'required|string',
            'status' => 'required|boolean'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
