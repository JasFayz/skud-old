<?php

namespace Modules\Visitor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateInviteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start' => ['required', 'string'],
            'end' => ['required', 'string'],
            'responsible_user' => ['required', 'numeric'],
            'target_user' => ['required', 'numeric'],
            'comment' => ['nullable', 'string'],
//            'zones' => ['required', 'array']
            'terminals' => ['nullable', 'array']
        ];
    }

    public function authorize(): bool
    {
        return Auth::check();
    }
}
