<?php

namespace Modules\Visitor\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class GuestResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'patronymic' => $this->patronymic,
            'company_name' => $this->company_name,
            'passport_name' => $this->passport_name,
            'is_vip' => $this->is_vip
        ];
    }
}
