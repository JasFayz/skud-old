<?php

namespace Modules\Visitor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInviteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start' => ['required', 'string'],
            'end' => ['required', 'string'],
            'responsible_user' => ['required', 'numeric'],
            'target_user' => ['required', 'numeric'],
            'comment' => ['nullable', 'string'],
            'approved_by' => ['nullable', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return \Auth::check();
    }
}
